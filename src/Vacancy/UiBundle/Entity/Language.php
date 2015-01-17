<?php
namespace Vacancy\UiBundle\Entity;

class Language
{
    /** @var int */
    private $id;
    /** @var string */
    private $shortName;
    /** @var string */
    private $title;

    /**
     * @param string $shortName
     * @param string $title
     */
    public function __construct($shortName, $title)
    {
        $this->shortName = $shortName;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'shortName' => $this->getShortName(),
            'title' => $this->getTitle()
        ];
    }
}
