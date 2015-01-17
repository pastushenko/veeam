<?php
namespace Vacancy\ApiBundle\Validator;

use Symfony\Component\HttpFoundation\Request;
use Vacancy\UiBundle\Entity\Department;
use Vacancy\UtilsBundle\Exception\WrongDataTypeException;
use Vacancy\UtilsBundle\UtilTrait\DataValidationTrait;

class VacancyValidator
{
    use DataValidationTrait;

    const VACANCY_DATA_KEY = 'vacancy';
    const VACANCY_TITLE_KEY = 'title';
    const VACANCY_DESCRIPTION_KEY = 'description';
    const VACANCY_DEPARTMENT_KEY = 'department';
    const VACANCY_TRANSLATIONS_KEY = 'translation';

    /** @var [] */
    private $vacancyData;
    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var int */
    private $departmentId;
    /** @var VacancyTranslationValidator[] */
    private $translations = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fetchVacancyData($request);
        $this->fetchTitle();
        $this->fetchDescription();
        $this->fetchDepartmentId();

        $this->fetchTranslations();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \int
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    /**
     * @return VacancyTranslationValidator[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    private function fetchVacancyData(Request $request)
    {
        $data = $request->get(self::VACANCY_DATA_KEY);
        if (!$data) {
            throw new \Exception(sprintf('Request must contain "%s" key.', self::VACANCY_DATA_KEY));
        }
        $this->checkIsArray($data);

        $this->vacancyData = $data;
    }

    /**
     * @return string
     * @throws \Exception
     * @throws WrongDataTypeException
     */
    private function fetchTitle()
    {
        $vacancyData = $this->getVacancyData();
        if (!isset($vacancyData[self::VACANCY_TITLE_KEY])) {
            throw new \Exception(sprintf('Vacancy data must contain "%s" key.', self::VACANCY_TITLE_KEY));
        }

        $title = $vacancyData[self::VACANCY_TITLE_KEY];
        $this->checkIsString($title);

        $this->title = $title;
    }

    /**
     * @return string
     * @throws \Exception
     * @throws WrongDataTypeException
     */
    private function fetchDescription()
    {
        $vacancyData = $this->getVacancyData();
        if (!isset($vacancyData[self::VACANCY_DESCRIPTION_KEY])) {
            return null;
        }

        $description = $vacancyData[self::VACANCY_DESCRIPTION_KEY];
        $this->checkIsString($description);

        $this->description = $description;
    }

    /**
     * @return Department
     * @throws WrongDataTypeException
     * @throws \Exception
     */
    private function fetchDepartmentId()
    {
        $vacancyData = $this->getVacancyData();
        if (!isset($vacancyData[self::VACANCY_DEPARTMENT_KEY])) {
            throw new \Exception(sprintf('Vacancy data must contain "%s" key.', self::VACANCY_DEPARTMENT_KEY));
        }

        $departmentId = $vacancyData[self::VACANCY_DEPARTMENT_KEY];
        $this->checkIsInteger($departmentId);

        $this->departmentId = $departmentId;
    }

    /**
     * @return []
     * @throws \Exception
     */
    private function getVacancyData()
    {
        if (!$this->vacancyData) {
            throw new \Exception('You must call first fetchVacancyData() method, before calling other fetchers.');
        }

        return $this->vacancyData;
    }

    /**
     * @throws WrongDataTypeException
     * @throws \Exception
     */
    private function fetchTranslations()
    {
        $vacancyData = $this->getVacancyData();
        if (!isset($vacancyData[self::VACANCY_TRANSLATIONS_KEY])) {
            return;
        }

        $vacancyTranslations = $vacancyData[self::VACANCY_TRANSLATIONS_KEY];
        $this->checkIsArray($vacancyTranslations);

        foreach ($vacancyTranslations as $languageId => $translation) {
            $translationDto = new VacancyTranslationValidator($languageId, $translation);
            if (!$translationDto->getTitle()) {
                continue;
            }

            $this->translations[] = $translationDto;
        }
    }
}