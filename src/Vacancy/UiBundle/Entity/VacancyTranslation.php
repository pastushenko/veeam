<?php
namespace Vacancy\UiBundle\Entity;

class VacancyTranslation
{
    /** @var int */
    private $id;
    /** @var Vacancy */
    private $vacancy;
    /** @var Language */
    private $language;
    /** @var string */
    private $title;
    /** @var string */
    private $description;

    /**
     * @param Vacancy $vacancy
     * @param Language $language
     * @param string $title
     * @param string $description
     */
    public function __construct(Vacancy $vacancy, Language $language, $title, $description)
    {
        $this->vacancy = $vacancy;
        $this->language = $language;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return Vacancy
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
