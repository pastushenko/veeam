<?php
namespace Vacancy\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vacancy\ApiBundle\Validator\VacancyValidator;
use Vacancy\UiBundle\Dto\VacancyFilterDto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vacancy\UiBundle\Entity\Vacancy;
use Vacancy\UiBundle\Entity\VacancyTranslation;
use Vacancy\UiBundle\Repository\DepartmentRepository;
use Vacancy\UiBundle\Repository\LanguageRepository;
use Vacancy\UiBundle\Repository\VacancyRepository;
use Vacancy\UiBundle\Repository\VacancyTranslationRepository;

class VacancyApiController extends Controller
{
    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    const MESSAGE_OK = 'ok';
    const MESSAGE_ERR = 'Error: %s';

    /** @var VacancyRepository */
    private $vacancyRepository;
    /** @var VacancyTranslationRepository */
    private $vacancyTranslationRepository;
    /** @var DepartmentRepository */
    private $departmentRepository;
    /** @var LanguageRepository */
    private $languageRepository;

    /**
     * @param VacancyRepository $vacancyRepository
     * @param VacancyTranslationRepository $vacancyTranslationRepository
     * @param DepartmentRepository $departmentRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        VacancyRepository $vacancyRepository,
        VacancyTranslationRepository $vacancyTranslationRepository,
        DepartmentRepository $departmentRepository,
        LanguageRepository $languageRepository
    ) {
        $this->vacancyRepository = $vacancyRepository;
        $this->vacancyTranslationRepository = $vacancyTranslationRepository;
        $this->departmentRepository = $departmentRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $vacanciesArray = [];
        try {
            $filter = new VacancyFilterDto($request);
            $vacancies = $this->vacancyRepository->fetchByFiler($filter);
            $this->fetchVacanciesTranslations($vacancies, $filter);
            $vacanciesArray = $this->prepareVacancies($vacancies);

            $status = self::STATUS_OK;
            $message = self::MESSAGE_OK;
        } catch (\Exception $ex) {
            $status = self::STATUS_ERROR;
            $message = sprintf(self::MESSAGE_ERR, $ex->getMessage());
        }

        return $this->render('VacancyApiBundle:VacancyApi:json.html.twig', array(
            'response' => [
                'status' => $status,
                'message' => $message,
                'vacancies' => $vacanciesArray
            ]
        ));
    }

    /**
     * @param int $vacancyId
     * @return Response
     */
    public function getAction($vacancyId)
    {
        $vacancyData = [];

        try {
            /** @var Vacancy $vacancy */
            $vacancy = $this->vacancyRepository->find($vacancyId);
            if (!$vacancy) {
                throw new \Exception(sprintf('Vacancy with id "%s" not exists.', $vacancyId));
            }

            $translations = $this->vacancyTranslationRepository->fetchVacancyTranslations($vacancy);
            foreach ($translations as $translation) {
                $vacancy->addTranslation($translation);
            }
            $vacancyData = $vacancy->toArray();

            $status = self::STATUS_OK;
            $message = self::MESSAGE_OK;
        } catch (\Exception $ex) {
            $status = self::STATUS_ERROR;
            $message = sprintf(self::MESSAGE_ERR, $ex->getMessage());
        }

        return $this->render('VacancyApiBundle:VacancyApi:json.html.twig', array(
            'response' => [
                'status' => $status,
                'message' => $message,
                'vacancy' => $vacancyData
            ]
        ));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->vacancyRepository->beginTransaction();
        $this->vacancyTranslationRepository->beginTransaction();

        try {
            $vacancyValidator = new VacancyValidator($request);
            $department = $this->departmentRepository->fetchDepartment($vacancyValidator->getDepartmentId());
            $vacancy = $this->vacancyRepository->persistFromValidator($vacancyValidator, $department);

            foreach($vacancyValidator->getTranslations() as $translationValidator) {
                $language = $this->languageRepository->fetchLanguage($translationValidator->getLanguageId());
                $this->vacancyTranslationRepository->persistFromValidator($translationValidator, $vacancy, $language);
            }

            $this->vacancyRepository->commit();
            $this->vacancyTranslationRepository->commit();
            $status = self::STATUS_OK;
            $message = self::MESSAGE_OK;

        } catch (\Exception $ex) {
            $this->vacancyRepository->rollback();
            $this->vacancyTranslationRepository->rollback();
            $status = self::STATUS_ERROR;
            $message = sprintf(self::MESSAGE_ERR, $ex->getMessage());
        }

        return $this->render('VacancyApiBundle:VacancyApi:json.html.twig', array(
            'response' => [
                'status' => $status,
                'message' => $message
            ]
        ));
    }

    /**
     * @param \int $vacancyId
     * @return Response
     */
    public function removeAction($vacancyId)
    {
        $this->vacancyRepository->beginTransaction();
        $this->vacancyTranslationRepository->beginTransaction();

        try {
            $this->vacancyTranslationRepository->removeByVacancyId($vacancyId);
            $this->vacancyRepository->removeById($vacancyId);

            $this->vacancyRepository->commit();
            $this->vacancyTranslationRepository->commit();
            $status = self::STATUS_OK;
            $message = self::MESSAGE_OK;

        } catch (\Exception $ex) {
            $this->vacancyRepository->rollback();
            $this->vacancyTranslationRepository->rollback();
            $status = self::STATUS_ERROR;
            $message = sprintf(self::MESSAGE_ERR, $ex->getMessage());
        }

        return $this->render('VacancyApiBundle:VacancyApi:json.html.twig', array(
            'response' => [
                'status' => $status,
                'message' => $message
            ]
        ));
    }

    /**
     * @param Vacancy[] $vacancies
     * @param VacancyFilterDto $filter
     */
    private function fetchVacanciesTranslations($vacancies, VacancyFilterDto $filter)
    {
        $languageId = $filter->getLanguageId();
        if (!$languageId) {
            return;
        }

        $vacancyIds = [];
        foreach ($vacancies as $vacancy) {
            $vacancyIds[] = $vacancy->getId();
        }

        $translations = $this->vacancyTranslationRepository->fetchVacanciesTranslation($vacancyIds, $filter->getLanguageId());
        $this->appendTranslations($vacancies, $translations);
    }

    /**
     * @param Vacancy[] $vacancies
     * @return array
     */
    private function prepareVacancies($vacancies)
    {
        $vacanciesArray = [];
        foreach ($vacancies as $vacancy) {
            $vacanciesArray[] = $vacancy->toArray();
        }

        return $vacanciesArray;
    }

    /**
     * @param Vacancy[] $vacancies
     * @param VacancyTranslation[] $translations
     */
    private function appendTranslations($vacancies, $translations)
    {
        $vacancyTranslations = [];
        foreach ($translations as $translation) {
            $vacancyTranslations[$translation->getVacancy()->getId()][] = $translation;
        }

        foreach ($vacancies as $vacancy) {
            $vacancyId = $vacancy->getId();
            if (isset($vacancyTranslations[$vacancyId])) {
                foreach ($vacancyTranslations[$vacancyId] as $translation) {
                    $vacancy->addTranslation($translation);
                }
            }
        }
    }
}
