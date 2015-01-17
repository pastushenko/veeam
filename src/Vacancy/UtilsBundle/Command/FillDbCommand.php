<?php
namespace Vacancy\UtilsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vacancy\UiBundle\Entity\Department;
use Vacancy\UiBundle\Entity\Language;
use Vacancy\UiBundle\Entity\Vacancy;
use Vacancy\UiBundle\Entity\VacancyTranslation;
use Vacancy\UiBundle\Repository\DepartmentRepository;
use Vacancy\UiBundle\Repository\LanguageRepository;
use Vacancy\UiBundle\Repository\VacancyRepository;
use Vacancy\UiBundle\Repository\VacancyTranslationRepository;
use Vacancy\UtilsBundle\Exception\EntityNotFoundException;
use Vacancy\UtilsBundle\Exception\RepositoryNotEmptyException;
use Vacancy\UtilsBundle\Repository\DefaultDataRepository;

class FillDbCommand extends Command
{
    /** @var VacancyRepository */
    private $vacancyRepository;
    /** @var VacancyTranslationRepository */
    private $vacancyTranslationRepository;
    /** @var DepartmentRepository */
    private $departmentRepository;
    /** @var LanguageRepository */
    private $languageRepository;
    /** @var DefaultDataRepository */
    private $defaultDataRepository;

    /**
     * @param DefaultDataRepository $defaultDataRepository
     */
    public function setDefaultDataRepository(DefaultDataRepository $defaultDataRepository)
    {
        $this->defaultDataRepository = $defaultDataRepository;
    }

    /**
     * @param VacancyRepository $vacancyRepository
     */
    public function setVacancyRepository(VacancyRepository $vacancyRepository)
    {
        $this->vacancyRepository = $vacancyRepository;
    }

    /**
     * @param VacancyTranslationRepository $vacancyTranslationRepository
     */
    public function setVacancyTranslationRepository(VacancyTranslationRepository $vacancyTranslationRepository)
    {
        $this->vacancyTranslationRepository = $vacancyTranslationRepository;
    }

    /**
     * @param DepartmentRepository $departmentRepository
     */
    public function setDepartmentRepository(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @param LanguageRepository $languageRepository
     */
    public function setLanguageRepository(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }


    protected function configure()
    {
        $this
            ->setName('vacancies:initial-fill-db')
            ->setDescription('Fills empty database with test data.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->checkRepositoriesAreEmpty();
        } catch (RepositoryNotEmptyException $ex) {
            throw new \Exception(sprintf('You can\'t fill db with initial data. %s You can run "./app/console doctrine:schema:drop --force" to drop the db and "./app/console doctrine:schema:create" to create empty db.', $ex->getMessage()));
        }

        $output->writeln('<info>Filling db with initial data.</info>');

        $this->beginTransaction();
        try {
            $this->fillLanguages();
            $this->fillDepartments();
            $this->fillVacancies();
            $this->commit();
        } catch (\Exception $ex) {
            $this->rollback();
            throw $ex;
        }

        $output->writeln('<info>Done!</info>');

        return 0;

    }

    private function checkRepositoriesAreEmpty()
    {
        $this->checkVacancyRepositoryIsEmpty();
        $this->checkLanguageRepositoryIsEmpty();
        $this->checkDepartmentRepositoryIsEmpty();
    }

    /**
     * @throws \Exception
     */
    private function checkVacancyRepositoryIsEmpty()
    {
        $vacancies = $this->getVacancyRepository()->findAll();
        if ($vacancies) {
            throw new RepositoryNotEmptyException('Vacancy table is not empty.');
        }
    }

    /**
     * @throws \Exception
     */
    private function checkLanguageRepositoryIsEmpty()
    {
        $languages = $this->getLanguageRepository()->findAll();
        if ($languages) {
            throw new RepositoryNotEmptyException('Language table is not empty.');
        }
    }

    /**
     * @throws \Exception
     */
    private function checkDepartmentRepositoryIsEmpty()
    {
        $departments = $this->getDepartmentRepository()->findAll();
        if ($departments) {
            throw new RepositoryNotEmptyException('Department table is not empty.');
        }
    }

    /**
     * @throws \Exception
     */
    private function getDefaultDataRepository()
    {
        if (is_null($this->defaultDataRepository)) {
            throw new \Exception('You must inject default data repository in fill db command (call setDefaultDataRepository()).');
        }

        return $this->defaultDataRepository;
    }

    /**
     * @throws \Exception
     */
    private function getVacancyRepository()
    {
        if (is_null($this->vacancyRepository)) {
            throw new \Exception('You must inject vacancy repository in fill db command (call setVacancyRepository()).');
        }

        return $this->vacancyRepository;
    }

    /**
     * @throws \Exception
     */
    private function getVacancyTranslationRepository()
    {
        if (is_null($this->vacancyTranslationRepository)) {
            throw new \Exception('You must inject vacancy translation repository in fill db command (call setVacancyTranslationRepository()).');
        }

        return $this->vacancyTranslationRepository;
    }

    /**
     * @throws \Exception
     */
    private function getDepartmentRepository()
    {
        if (is_null($this->departmentRepository)) {
            throw new \Exception('You must inject department repository in fill db command (call setDepartmentRepository()).');
        }

        return $this->departmentRepository;
    }

    /**
     * @throws \Exception
     */
    private function getLanguageRepository()
    {
        if (is_null($this->languageRepository)) {
            throw new \Exception('You must inject language repository in fill db command (call setLanguageRepository()).');
        }

        return $this->languageRepository;
    }

    private function fillLanguages()
    {
        foreach ($this->getDefaultLanguages() as $shortName => $title) {
            $language = new Language($shortName, $title);
            $this->languageRepository->persist($language);
        }
    }

    private function fillDepartments()
    {
        foreach ($this->getDefaultDepartments() as $departmentTitle) {
            $department = new Department($departmentTitle);
            $this->departmentRepository->persist($department);
        }
    }

    private function fillVacancies()
    {
        foreach ($this->getDefaultVacancies() as $vacancyArray) {

            $department = $this->getVacancyDepartment($vacancyArray);
            $vacancy = new Vacancy($department, $vacancyArray['title']);
            $vacancy->setDescription($vacancyArray['description']);

            foreach($vacancyArray['translations'] as $translationArray) {
                $language = $this->getTranslationLanguage($translationArray);
                $vacancyTranslation = new VacancyTranslation($vacancy, $language, $translationArray['title']);
                $vacancyTranslation->setDescription($translationArray['description']);
                $vacancy->addTranslation($vacancyTranslation);
            }

            $this->vacancyRepository->persist($vacancy);
            foreach ($vacancy->getTranslations() as $translation) {
                $this->getVacancyTranslationRepository()->persist($translation);
            }
        }
    }

    /**
     * @param array $vacancyArray
     * @return Department
     * @throws \Exception
     */
    private function getVacancyDepartment(array $vacancyArray)
    {
        try {
            $department = $this->getDepartmentByName($vacancyArray['department']);
        } catch (EntityNotFoundException $ex) {
            throw new \Exception(sprintf('Can\'t fill vacancy default data. %s Check attribute $defaultVacancies. Vacancy data: %s', $ex->getMessage(), print_r($vacancyArray, true)));
        }

        return $department;
    }

    /**
     * @param string $departmentName
     * @return Department
     * @throws EntityNotFoundException
     */
    private function getDepartmentByName($departmentName)
    {
        $department = $this->departmentRepository->findOneBy(['title' => $departmentName]);
        if (is_null($department)) {
            throw new EntityNotFoundException(sprintf('Department with title "%s" not found.', $departmentName));
        }

        return $department;
    }

    /**
     * @param array $translationArray
     * @return Language
     * @throws \Exception
     */
    private function getTranslationLanguage(array $translationArray)
    {
        try {
            $language = $this->getLanguageByShortName($translationArray['language']);
        } catch (EntityNotFoundException $ex) {
            throw new \Exception(sprintf('Can\'t fill vacancy default data. %s Check attribute $defaultVacancies. Translation data: %s', $ex->getMessage(), print_r($translationArray, true)));
        }

        return $language;
    }

    /**
     * @param string $languageShortName
     * @return Language
     * @throws EntityNotFoundException
     */
    private function getLanguageByShortName($languageShortName)
    {
        $language = $this->languageRepository->findOneBy(['shortName' => $languageShortName]);
        if (is_null($language)) {
            throw new EntityNotFoundException(sprintf('Translation with short name "%s" not found.', $languageShortName));
        }

        return $language;
    }

    private function beginTransaction()
    {
        $this->getVacancyRepository()->beginTransaction();
        $this->getVacancyTranslationRepository()->beginTransaction();
        $this->getDepartmentRepository()->beginTransaction();
        $this->getLanguageRepository()->beginTransaction();
    }

    private function commit()
    {
        $this->getVacancyRepository()->commit();
        $this->getVacancyTranslationRepository()->commit();
        $this->getDepartmentRepository()->commit();
        $this->getLanguageRepository()->commit();
    }

    private function rollback()
    {
        $this->getVacancyRepository()->rollback();
        $this->getVacancyTranslationRepository()->rollback();
        $this->getDepartmentRepository()->rollback();
        $this->getLanguageRepository()->rollback();
    }

    /**
     * @return array
     */
    private function getDefaultLanguages()
    {
        return $this->getDefaultDataRepository()->getDefaultLanguages();
    }

    /**
     * @return array
     */
    private function getDefaultDepartments()
    {
        return $this->getDefaultDataRepository()->getDefaultDepartments();
    }

    /**
     * @return array
     */
    private function getDefaultVacancies()
    {
        return $this->getDefaultDataRepository()->getDefaultVacancies();
    }
}