<?php
namespace Vacancy\UiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vacancy\UiBundle\Entity\Language;
use Vacancy\UtilsBundle\UtilTrait\TransactionTrait;

class LanguageRepository extends EntityRepository
{
    use TransactionTrait;

    /**
     * @param Language $language
     */
    public function persist(Language $language)
    {
        $this->getEntityManager()->persist($language);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $languageId
     * @return Language
     * @throws \Exception
     */
    public function getLanguage($languageId)
    {
        $language = $this->find($languageId);

        if (is_null($language)) {
            throw new \Exception(sprintf('Language with id "%s" not found.', $languageId));
        }

        return $language;
    }
}

