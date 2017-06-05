<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

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
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var array
     */
    protected $mappings;

    /**
     * BaseWrapper constructor.
     *
     * @param array             $context
     * @param \Twig_Environment $environment
     */
    public function __construct(array $context, \Twig_Environment $environment)
    {
        $this->context = $context;
        $this->environment = $environment;

        $this->attributes = [];
        $this->mappings = $this->configureMappings();
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
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
     * @param array       $mappings
     * @param string|null $column
     *
     * @throws \RuntimeException
     */
    protected function setProperties(array $properties, array $mappings, string $column = null)
    {
        foreach ($properties as $key => $value) {
            if (!isset($mappings[$key])) {
                throw new \RuntimeException(sprintf('No mapping found for key "%s"', $key));
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
                throw new \RuntimeException(sprintf('Invalid mapping for key "%s"', $key));
            }
        }
    }
}
