<?php
namespace Vacancy\UiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Vacancy\ApiBundle\Validator\VacancyTranslationValidator;
use Vacancy\UiBundle\Dto\VacancyFilterDto;
use Vacancy\UiBundle\Entity\Language;
use Vacancy\UiBundle\Entity\Vacancy;
use Vacancy\UiBundle\Entity\VacancyTranslation;
use Vacancy\UtilsBundle\UtilTrait\TransactionTrait;

class VacancyTranslationRepository extends EntityRepository
{
    use TransactionTrait;

    const TABLE_ALIAS = 'VacancyTranslation';

    /**
     * @param VacancyTranslation $vacancyTranslation
     */
    public function persist(VacancyTranslation $vacancyTranslation)
    {
        $this->getEntityManager()->persist($vacancyTranslation);
        $this->getEntityManager()->flush();
    }

    /**
     * @param VacancyTranslationValidator $translationValidator
     * @param Vacancy $vacancy
     * @param Language $language
     * @return VacancyTranslation
     */
    public function persistFromValidator(
        VacancyTranslationValidator $translationValidator,
        Vacancy $vacancy,
        Language $language
    ) {
        $translation = new VacancyTranslation($vacancy, $language, $translationValidator->getTitle());
        $translation->setDescription($translationValidator->getDescription());

        $this->getEntityManager()->persist($translation);
        $this->getEntityManager()->flush();

        return $translation;
    }

    /**
     * @param Vacancy $vacancy
     * @return VacancyTranslation[]
     */
    public function fetchVacancyTranslations(Vacancy $vacancy)
    {
        $qb = $this->createQueryBuilder(self::TABLE_ALIAS);
        $this->applyVacancyFilter($qb, [$vacancy->getId()]);

        return $qb->getQuery()->execute();
    }

    /**
     * @param array $vacancyIds
     * @param \int $languageId
     * @return VacancyTranslation[]
     */
    public function fetchVacanciesTranslation(array $vacancyIds, $languageId)
    {
        $qb = $this->createQueryBuilder(self::TABLE_ALIAS);

        $this->applyVacancyFilter($qb, $vacancyIds);
        $this->applyLanguageFilter($qb, $languageId);

        return $qb->getQuery()->execute();
    }

    /**
     * @param \int $vacancyId
     */
    public function removeByVacancyId($vacancyId)
    {
        $qb = $this->createQueryBuilder(self::TABLE_ALIAS);
        $qb->delete();
        $qb->where(self::TABLE_ALIAS.'.vacancy = :vacancy')->setParameter('vacancy', $vacancyId);
        $qb->getQuery()->execute();
    }

    /**
     * @param QueryBuilder $qb
     * @param \int $languageId
     */
    private function applyLanguageFilter(QueryBuilder $qb, $languageId)
    {
        $qb->andWhere(self::TABLE_ALIAS.'.language = :language');
        $qb->setParameter('language', $languageId);
    }

    /**
     * @param QueryBuilder $qb
     * @param \array $vacancyIds
     */
    private function applyVacancyFilter(QueryBuilder $qb, array $vacancyIds)
    {
        $qb->andWhere($qb->expr()->in(self::TABLE_ALIAS.'.vacancy', ':vacancyIds'));
        $qb->setParameter('vacancyIds', $vacancyIds);
    }
}
