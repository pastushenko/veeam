<?php
namespace Vacancy\UiBundle\Entity;

class VacancyTranslation
{
    /** @var int */
    private $id;
    /** @var int */
    private $vacancyId;
    /** @var Language */
    private $language;
    /** @var string */
    private $title;
    /** @var string */
    private $description;

    /**
     * @param int $vacancyId
     * @param Language $language
     * @param string $title
     * @param string $description
     */
    public function __construct($vacancyId, Language $language, $title, $description)
    {
        $this->vacancyId = (int) $vacancyId;
        $this->language = $language;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getVacancyId()
    {
        return $this->vacancyId;
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
