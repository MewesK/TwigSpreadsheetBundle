<?php

namespace Erelke\TwigSpreadsheetBundle\Wrapper;

use function is_array;
use function is_callable;
use RuntimeException;
use Twig\Environment as Twig_Environment;

/**
 * Class BaseWrapper.
 */
abstract class BaseWrapper
{
    /**
     * @var array
     */
    protected $context;

    /**
     * @var Twig_Environment
     */
    protected $environment;

    /**
     * @var array
     */
    protected $parameters;
    /**
     * @var array
     */
    protected $mappings;

    /**
     * BaseWrapper constructor.
     *
     * @param array             $context
     * @param Twig_Environment $environment
     */
    public function __construct(array $context, Twig_Environment $environment)
    {
        $this->context = $context;
        $this->environment = $environment;

        $this->parameters = [];
        $this->mappings = $this->configureMappings();
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    /**
     * @param array $mappings
     */
    public function setMappings(array $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @return array
     */
    protected function configureMappings(): array
    {
        return [];
    }

    /**
     * Calls the matching mapping callable for each property.
     *
     * @param array       $properties
     * @param array|null  $mappings
     * @param string|null $column
     *
     * @throws RuntimeException
     */
    protected function setProperties(array $properties, array $mappings = null, string $column = null)
    {
        if ($mappings === null) {
            $mappings = $this->mappings;
        }

        foreach ($properties as $key => $value) {
            if (!isset($mappings[$key])) {
                throw new RuntimeException(sprintf('Missing mapping for key "%s"', $key));
            }

            if (is_array($value) && is_array($mappings[$key])) {
                // recursion
                if (isset($mappings[$key]['__multi'])) {
                    // handle multi target structure (with columns)
                    /**
                     * @var array $value
                     */
                    foreach ($value as $_column => $_value) {
                        $this->setProperties($_value, $mappings[$key], $_column);
                    }
                } else {
                    // handle single target structure
                    $this->setProperties($value, $mappings[$key]);
                }
            } elseif (is_callable($mappings[$key])) {
                // call single and multi target mapping
                // if column is set it is used to get object from the callback in __multi
                $mappings[$key](
                    $value,
                    $column !== null ? $mappings['__multi']($column) : null
                );
            } else {
                throw new RuntimeException(sprintf('Invalid mapping for key "%s"', $key));
            }
        }
    }
}
