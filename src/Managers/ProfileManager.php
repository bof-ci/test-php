<?php
namespace BOF\Managers;

use Doctrine\ORM\EntityManager;

class ProfileManager
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return array
     */
    public function getProfiles()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
            ->from('BOF\Entities\ProfileEntity', 'p')
            ->orderBy('p.name', 'ASC')
        ;
        $query = $qb->getQuery();

        return $query->getArrayResult();
    }

    /**
     * @param $year
     * @return array
     */
    public function getSumViews($year)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('p.id as profile_id, MONTH(v.date) as month, SUM(v.views) as views')
            ->from('BOF\Entities\ViewEntity', 'v')
            ->innerJoin('v.profile', 'p')
            ->where('YEAR(v.date) = :year')
            ->groupBy('profile_id, month')
            ->setParameter('year', $year)
        ;
        $query = $qb->getQuery();

        return $query->getArrayResult();
    }

    /**
     * @return string|null String of year
     */
    public function getMaxYear()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('MAX(YEAR(v.date)) as year')
            ->from('BOF\Entities\ViewEntity', 'v')
        ;
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        return $result ? $result[0]["year"] : null;
    }
}