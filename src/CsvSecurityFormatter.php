<?php

namespace InThere\CsvSecurityFormatter;

/**
 *  A class to prevent harmful user generated content in the csv export.
 * @see http://georgemauer.net/2017/10/07/csv-injection.html
 */
class CsvSecurityFormatter
{
    /**
     * Determines if function calls must be removed or just escaped.
     * @var bool
     */
    private $escape = true;

    /**
     * Holds the characters which are csv quotes.
     * @var array
     */
    private $quoteCharacters = ['"', '\''];

    /**
     * Holds the characters which might trigger a formula.
     * @var array
     */
    private $formulaTriggeringCharacters = ['=', '-', '+', '@'];

    /**
     * CsvSecurityFormatter constructor.
     * @param bool $escape Determines if function calls must be removed or just escaped.
     */
    public function __construct($escape = true)
    {
        $this->escape = $escape;
    }

    /**
     * Returns the formula pattern.
     * @return string
     */
    private function getPattern()
    {
        return sprintf(
            '/^([%s]?)([%s])/',
            preg_quote(implode('', $this->quoteCharacters)),
            preg_quote(implode('', $this->formulaTriggeringCharacters))
        );
    }

    /**
     * Determines if the value contains a formula.
     * @param string $value
     * @return bool
     */
    private function containsFormula($value)
    {
        return preg_match($this->getPattern(), $value) != false;
    }

    /**
     * Removes the formula from the value.
     * @param string $value
     * @return string|null
     */
    private function removeFormula($value)
    {
        while ($this->containsFormula($value)) {
            $value = preg_replace($this->getPattern(), '$1', $value);
        }

        return $value;
    }

    /**
     * Escapes the formula characters.
     * @param string $value
     * @return string|null
     */
    private function escapeFormula($value)
    {
        return preg_replace($this->getPattern(), "$1\t$2", $value);
    }

    /**
     * Protect the column.
     * @param string $value
     * @return null|string
     */
    private function protectValue($value)
    {
        // When the value doesn't contain a formula, it's already safe for export
        if (! $this->containsFormula($value)) {
            return $value;
        }

        // Just remove the formula when requested
        if (! $this->escape) {
            return $this->removeFormula($value);
        }

        // Otherwise we escape the formula
        return $this->escapeFormula($value);
    }

    /**
     * Protect the row.
     * @param array $row
     * @return array
     */
    public function __invoke(array $row)
    {
        return array_map([$this, 'protectValue'], $row);
    }
}
