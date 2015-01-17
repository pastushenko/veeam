<?php
namespace Vacancy\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vacancy\UiBundle\Dto\VacancyFilterDto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vacancy\UiBundle\Repository\VacancyRepository;

class VacancyApiController extends Controller
{
    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    const MESSAGE_OK = 'ok';
    const MESSAGE_ERR = 'error: %s';

    /** @var VacancyRepository */
    private $vacancyRepository;

    /**
     * @param VacancyRepository $vacancyRepository
     */
    public function __construct(VacancyRepository $vacancyRepository)
    {
        $this->vacancyRepository = $vacancyRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getAction(Request $request)
    {
        $vacancies = [];
        try {
            $filter = new VacancyFilterDto($request);
            $vacancies = $this->vacancyRepository->getVacanciesAsArray($filter);

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
                'vacancies' => $vacancies
            ]
        ));
    }
}
