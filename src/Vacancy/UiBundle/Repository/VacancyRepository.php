<?php
namespace Vacancy\UiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Vacancy\UiBundle\Dto\VacancyFilterDto;
use Vacancy\UiBundle\Entity\Vacancy;
use Vacancy\UtilsBundle\UtilTrait\TransactionTrait;

class VacancyRepository extends EntityRepository
{
    use TransactionTrait;

    const TABLE_ALIAS = 'vacancy';

    /**
     * @param Vacancy $vacancy
     */
    public function persist(Vacancy $vacancy)
    {
        $this->getEntityManager()->persist($vacancy);
        $this->getEntityManager()->flush();
    }

    /**
     * @param VacancyFilterDto $filter
     * @return Vacancy[]
     */
    public function fetchByFiler(VacancyFilterDto $filter)
    {
        $qb = $this->createQueryBuilder(self::TABLE_ALIAS);
        $this->applyFilters($qb, $filter);
        return $qb->getQuery()->execute();
    }

    /**
     * @param QueryBuilder $qb
     * @param VacancyFilterDto $filter
     */
    private function applyFilters(QueryBuilder $qb, VacancyFilterDto $filter)
    {
        $this->applyDepartmentFilter($qb, $filter);
    }

    /**
     * @param QueryBuilder $qb
     * @param VacancyFilterDto $filter
     */
    private function applyDepartmentFilter(QueryBuilder $qb, VacancyFilterDto $filter)
    {
        $departmentId = $filter->getDepartmentId();
        if (!$departmentId) {
            return;
        }

        $qb->andWhere(self::TABLE_ALIAS.'.department = :department')->setParameter('department', $departmentId);
    }
}
