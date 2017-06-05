<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

/**
 * Class BaseTokenParser.
 */
abstract class BaseTokenParser extends \Twig_TokenParser
{
    /**
     * @var int
     */
    const PARAMETER_TYPE_ARRAY = 0;

    /**
     * @var int
     */
    const PARAMETER_TYPE_VALUE = 1;

    /**
     * @param \Twig_Token $token
     *
     * @return array
     */
    public function configureParameters(\Twig_Token $token): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
    }

    /**
     * @return string
     */
    abstract public function getNode(): string;

    /**
     * @return bool
     */
    public function hasBody(): bool
    {
        return true;
    }

    /**
     * @param \Twig_Token $token
     *
     * @throws \Twig_Error_Syntax
     * @throws \InvalidArgumentException
     *
     * @return \Twig_node
     */
    public function parse(\Twig_Token $token)
    {
        // parse attributes
        $nodes = $this->parseParameters($this->configureParameters($token));

        // parse body
        if ($this->hasBody()) {
            $nodes['body'] = $this->parseBody();
        }

        // return node
        $nodeClass = $this->getNode();

        return new $nodeClass($nodes, $this->getAttributes(), $token->getLine(), $this->getTag());
    }

    /**
     * @param array $parameterConfiguration
     *
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     *
     * @return \Twig_Node_Expression[]
     */
    private function parseParameters(array $parameterConfiguration = []): array
    {
        // parse expressions
        $expressions = [];
        while (!$this->parser->getStream()->test(\Twig_Token::BLOCK_END_TYPE)) {
            $expressions[] = $this->parser->getExpressionParser()->parseExpression();
        }

        // end of expressions
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        // map expressions to parameters
        $parameters = [];
        foreach ($parameterConfiguration as $parameterName => $parameterOptions) {
            // try mapping expression
            $expression = reset($expressions);
            if ($expression !== false) {
                switch ($parameterOptions['type']) {
                    case self::PARAMETER_TYPE_ARRAY:
                        // check if expression is valid array
                        $valid = $expression instanceof \Twig_Node_Expression_Array;
                        break;
                    case self::PARAMETER_TYPE_VALUE:
                        // check if expression is valid value
                        $valid = !($expression instanceof \Twig_Node_Expression_Array);
                        break;
                    default:
                        throw new \InvalidArgumentException('Invalid parameter type');
                        break;
                }

                if ($valid) {
                    // set expression as parameter and remove it from expressions list
                    $parameters[$parameterName] = array_shift($expressions);
                    continue;
                }
            }

            // set default as parameter otherwise or throw exception if default is false
            if ($parameterOptions['default'] === false) {
                throw new \Twig_Error_Syntax('A required parameter is missing');
            }
            $parameters[$parameterName] = $parameterOptions['default'];
        }

        if (count($expressions) > 0) {
            throw new \Twig_Error_Syntax('Too many parameters');
        }

        return $parameters;
    }

    /**
     * @return \Twig_Node
     */
    private function parseBody(): \Twig_Node
    {
        // parse body
        $body = $this->parser->subparse(function (\Twig_Token $token) {
            return $token->test('end'.$this->getTag());
        },
            true
        );

        // end of body
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return $body;
    }
}
