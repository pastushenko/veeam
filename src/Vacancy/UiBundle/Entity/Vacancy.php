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
     */
    public function __construct(Department $department)
    {
        $this->department = $department;
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
}
