<?php
use Symfony\Component\HttpFoundation\Request;
use Vacancy\UtilsBundle\Exception\WrongDataTypeException;
use Vacancy\UtilsBundle\UtilTrait\NumberValidationTrait;

class FilterDto
{
    use NumberValidationTrait;

    const KEY_DEPARTMENT_ID = 'department';
    const KEY_LANGUAGE_ID = 'language';

    /** @var int */
    private $departmentId;
    /** @var int */
    private $languageId;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fetchDepartmentId($request);
        $this->fetchLanguageId($request);
    }

    /**
     * @return int
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    /**
     * @return int
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function fetchDepartmentId(Request $request)
    {
        $departmentId = $request->get(self::KEY_DEPARTMENT_ID);
        if (!$departmentId) {
            return;
        }

        $this->checkDepartmentId($departmentId);
        $this->departmentId = $departmentId;
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function fetchLanguageId(Request $request)
    {
        $languageId = $request->get(self::KEY_LANGUAGE_ID);
        if (!$languageId) {
            return;
        }

        $this->checkLanguageId($languageId);
        $this->languageId = $languageId;
    }

    /**
     * @param int $departmentId
     * @throws Exception
     */
    private function checkDepartmentId($departmentId)
    {
        try {
            $this->checkIsInteger($departmentId);
        } catch (WrongDataTypeException $ex) {
            throw new \Exception(sprintf('Wrong data in %s. %s', self::KEY_DEPARTMENT_ID, $ex->getMessage()));
        }
    }

    /**
     * @param int $languageId
     * @throws Exception
     */
    private function checkLanguageId($languageId)
    {
        try {
            $this->checkIsInteger($languageId);
        } catch (WrongDataTypeException $ex) {
            throw new \Exception(sprintf('Wrong data in %s. %s', self::KEY_LANGUAGE_ID, $ex->getMessage()));
        }
    }
}