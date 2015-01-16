<?php
namespace Vacancy\UiBundle\Entity;

class Vacancy
{
    /** @var int */
    private $id;
    /** @var Department */
    private $department;
    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var VacancyTranslation[] */
    private $translations = [];

    /**
     * @param Department $department
     * @param string $title
     */
    public function __construct(Department $department, $title)
    {
        $this->department = $department;
        $this->title = $title;
    }

    /**
     * @param VacancyTranslation $translation
     */
    public function addTranslation(VacancyTranslation $translation)
    {
        $languageShortName = $translation->getLanguage()->getShortName();
        $this->translations[$languageShortName] = $translation;
    }

    /**
     * @return VacancyTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }
}
