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
     */
    public function __construct(Vacancy $vacancy, Language $language, $title)
    {
        $this->vacancy = $vacancy;
        $this->language = $language;
        $this->title = $title;
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'vacancyId' => $this->getVacancy()->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'language' => $this->getLanguage()->toArray()
        ];
    }
}
