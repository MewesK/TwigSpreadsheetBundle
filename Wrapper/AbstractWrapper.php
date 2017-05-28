<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

/**
 * Class AbstractWrapper
 *
 * @package MewesK\TwigSpreadsheetBundle\Wrapper
 */
abstract class AbstractWrapper
{
    /**
     * Calls the matching mapping callable for each property.
     *
     * @param array $properties
     * @param array $mappings
     * @throws \RuntimeException
     */
    protected function setProperties(array $properties, array $mappings)
    {
        foreach ($properties as $key => $value) {
            if (is_array($value) && is_array($mappings[$key])) {
                if (isset($mappings[$key]['__multi']) && $mappings[$key]['__multi'] === true) {
                    /**
                     * @var array $value
                     */
                    foreach ($value as $_key => $_value) {
                        $this->setPropertiesByKey($_key, $_value, $mappings[$key]);
                    }
                } else {
                    $this->setProperties($value, $mappings[$key]);
                }
            } elseif (is_callable($mappings[$key])) {
                $mappings[$key]($value);
            } else {
                throw new \RuntimeException(sprintf('Invalid mapping with key "%s"', $key));
            }
        }
    }

    /**
     * @param string $key
     * @param array $properties
     * @param array $mappings
     * @throws \RuntimeException
     */
    private function setPropertiesByKey(string $key, array $properties, array $mappings)
    {
        foreach ($properties as $_key => $value) {
            if (isset($mappings[$_key])) {
                if (is_array($value)) {
                    $this->setPropertiesByKey($key, $value, $mappings[$_key]);
                } elseif(is_callable($mappings[$_key])) {
                    $mappings[$_key]($key, $value);
                } else {
                    throw new \RuntimeException(sprintf('Invalid mapping with key "%s"', $_key));
                }
            }
        }
    }
}
