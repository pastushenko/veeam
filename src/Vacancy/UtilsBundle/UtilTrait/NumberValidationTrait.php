<?php
namespace Vacancy\UtilsBundle\UtilTrait;

use Vacancy\UtilsBundle\Exception\WrongDataTypeException;

trait NumberValidationTrait
{
    /**
     * @param mixed $var
     * @throws WrongDataTypeException
     */
    public function checkIsInteger($var)
    {
        if (!is_numeric($var) or strlen((int) $var) != strlen($var)) {
            throw new WrongDataTypeException(sprintf(
                'Wrong data type. Expected integer, got "%s". Variable: %s.',
                gettype($var),
                var_export($var, true)
            ));
        }
    }
}