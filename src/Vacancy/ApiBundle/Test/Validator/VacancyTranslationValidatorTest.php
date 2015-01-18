<?php
namespace Vacancy\ApiBundle\Test\Validator;

use Vacancy\ApiBundle\Validator\VacancyTranslationValidator;

class VacancyTranslationValidatorTest extends \PHPUnit_Framework_TestCase
{
    const LANGUAGE_GOOD_ID = '1';
    const LANGUAGE_WRONG_ID = 'text';
    const DEFAULT_TITLE = 'default title';
    const DEFAULT_DESCRIPTION = 'default description';
    const DEFAULT_LANGUAGE = self::LANGUAGE_GOOD_ID;

    /** @var [] */
    private $goodTranslationData = [
        VacancyTranslationValidator::TITLE_KEY => self::DEFAULT_TITLE,
        VacancyTranslationValidator::DESCRIPTION_KEY => self::DEFAULT_DESCRIPTION
    ];
    /** @var [] */
    private $badTranslationData = 'text';
    /** @var VacancyTranslationValidator */
    private $vacancyTranslationValidator;

    protected function setUp()
    {
        $this->vacancyTranslationValidator = new VacancyTranslationValidator(self::LANGUAGE_GOOD_ID, $this->goodTranslationData);
    }

    public function testTitleGetting()
    {
        $this->assertEquals(self::DEFAULT_TITLE, $this->vacancyTranslationValidator->getTitle());
    }

    public function testDescriptionGetting()
    {
        $this->assertEquals(self::DEFAULT_DESCRIPTION, $this->vacancyTranslationValidator->getDescription());
    }

    public function testLanguageGetting()
    {
        $this->assertEquals(self::DEFAULT_LANGUAGE, $this->vacancyTranslationValidator->getLanguageId());
    }

    /**
     * @param $languageId
     * @param $translationData
     * @dataProvider wrongDataProvider
     */
    public function testWrongInputDataTypeValidation($languageId, $translationData)
    {
        $this->setExpectedExceptionRegExp('Vacancy\UtilsBundle\Exception\WrongDataTypeException');
        new VacancyTranslationValidator($languageId, $translationData);
    }

    public function wrongDataProvider()
    {
        return array(
            array(self::LANGUAGE_WRONG_ID, $this->goodTranslationData),
            array(self::LANGUAGE_GOOD_ID, $this->badTranslationData)
        );
    }

    public function testWrongInputDataStructureValidation()
    {
        $badTranslationStructure = [
            'wrongKey' => 'title'
        ];
        $this->setExpectedExceptionRegExp('Vacancy\UtilsBundle\Exception\WrongDataStructureException');
        new VacancyTranslationValidator(self::LANGUAGE_GOOD_ID, $badTranslationStructure);
    }
}


