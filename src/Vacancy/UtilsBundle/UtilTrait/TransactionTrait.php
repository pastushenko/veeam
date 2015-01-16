<?php
namespace Vacancy\UtilsBundle\UtilTrait;

use Doctrine\ORM\EntityManager;

trait TransactionTrait
{
    /** @return EntityManager */
    abstract function getEntityManager();

    public function beginTransaction()
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function commit()
    {
        $this->getEntityManager()->commit();
    }

    public function rollback()
    {
        $this->getEntityManager()->rollback();
    }
}