<?php
namespace Vacancy\UiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vacancy\UiBundle\Entity\Vacancy;
use Vacancy\UtilsBundle\Entity\RatesCollection;
use Vacancy\UiBundle\Entity\Rate;
use Vacancy\UtilsBundle\UtilTrait\TransactionTrait;

class VacancyRepository extends EntityRepository
{
    use TransactionTrait;

    /**
     * @param Vacancy $vacancy
     */
    public function persist(Vacancy $vacancy)
    {
        $this->getEntityManager()->persist($vacancy);
        $this->getEntityManager()->flush();
    }
}
