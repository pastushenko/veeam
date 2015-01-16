<?php
namespace Vacancy\UiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vacancy\UiBundle\Entity\Department;
use Vacancy\UtilsBundle\Entity\RatesCollection;
use Vacancy\UiBundle\Entity\Rate;
use Vacancy\UtilsBundle\UtilTrait\TransactionTrait;

class DepartmentRepository extends EntityRepository
{
    use TransactionTrait;
    /**
     * @param Department $department
     */
    public function persist(Department $department)
    {
        $this->getEntityManager()->persist($department);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $departmentId
     * @return Department
     * @throws \Exception
     */
    public function getDepartment($departmentId)
    {
        $department = $this->find($departmentId);

        if (is_null($department)) {
            throw new \Exception(sprintf('Department with id "%s" not found.', $departmentId));
        }

        return $department;
    }
}
