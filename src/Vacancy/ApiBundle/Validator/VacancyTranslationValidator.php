<?php
namespace Vacancy\ApiBundle\Validator;

use Symfony\Component\HttpFoundation\Request;
use Vacancy\UiBundle\Entity\Department;
use Vacancy\UtilsBundle\Exception\WrongDataStructureException;
use Vacancy\UtilsBundle\Exception\WrongDataTypeException;
use Vacancy\UtilsBundle\UtilTrait\DataValidationTrait;

class VacancyTranslationValidator
{
    use DataValidationTrait;

    const TITLE_KEY = 'title';
    const DESCRIPTION_KEY = 'description';

    /** @var \int */
    private $languageId;
    /** @var string */
    private $title;
    /** @var string */
    private $description;


    /**
     * @param \int $languageId
     * @param [] $translationData
     */
    public function __construct($languageId, $translationData)
    {
        $this->checkIsInteger($languageId);
        $this->languageId = $languageId;

        $this->checkIsArray($translationData);

        $this->fetchTitle($translationData);
        $this->fetchDescription($translationData);
    }

    /**
     * @return int
     */
    public function getLanguageId()
    {
        return $this->languageId;
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
     * @param [] $translationData
     * @throws WrongDataTypeException
     * @throws \Exception
     */
    private function fetchTitle(array $translationData)
    {
        if (!isset($translationData[self::TITLE_KEY])) {
            throw new WrongDataStructureException(sprintf('Translation data must contain "%s" key.', self::TITLE_KEY));
        }

        $title = $translationData[self::TITLE_KEY];
        $this->checkIsString($title);

        $this->title = $title;
    }

    /**
     * @param [] $translationData
     * @throws WrongDataTypeException
     * @throws \Exception
     */
    private function fetchDescription(array $translationData)
    {
        if (!isset($translationData[self::DESCRIPTION_KEY])) {
            return;
        }

        $description = $translationData[self::DESCRIPTION_KEY];
        $this->checkIsString($description);

        $this->description = $description;
    }
}