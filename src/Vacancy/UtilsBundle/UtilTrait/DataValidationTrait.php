<?php
namespace Vacancy\UtilsBundle\UtilTrait;

use Vacancy\UtilsBundle\Exception\WrongDataTypeException;

trait DataValidationTrait
{
    /**
     * @param mixed $var
     * @throws WrongDataTypeException
     */
    protected function checkIsInteger($var)
    {
        if (!is_numeric($var) or strlen((int) $var) != strlen($var)) {
            throw new WrongDataTypeException(sprintf(
                'Wrong data type. Expected integer, got "%s". Var: %s.',
                gettype($var),
                var_export($var, true)
            ));
        }
    }

    /**
     * @param mixed $var
     * @throws WrongDataTypeException
     */
    protected function checkIsString($var)
    {
        if (!is_string($var)) {
            throw new WrongDataTypeException(sprintf(
                'Wrong data type. Expected string, got "%s". Var: %s.',
                gettype($var),
                var_export($var, true)
            ));
        }
    }

    /**
     * @param mixed $var
     * @throws WrongDataTypeException
     */
    protected function checkIsArray($var)
    {
        if (!is_array($var)) {
            throw new WrongDataTypeException(sprintf(
                'Wrong data type. Expected array, got "%s". Var: %s.',
                gettype($var),
                var_export($var, true)
            ));
        }
    }
}