<?php
namespace Vacancy\UiBundle\Controller;

use Vacancy\UiBundle\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class VacancyController extends Controller
{
    /** @var LanguageRepository */
    private $languageRepository;

    /**
     * @param LanguageRepository $languageRepository
     */
    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @return Response
     */
    public function browseAction()
    {
        return $this->render('VacancyUiBundle:Vacancy:index.html.twig', array());
    }
}
