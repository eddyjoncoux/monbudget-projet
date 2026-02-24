<?php

namespace App\Repository;

use App\Entity\Withdrawal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Withdrawal>
 */
class WithdrawalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Withdrawal::class);
    }

    /**
     * Find all active withdrawals for a user
     */
    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :user')
            ->andWhere('w.isActive = true')
            ->andWhere('w.endDate IS NULL OR w.endDate > CURRENT_TIMESTAMP()')
            ->setParameter('user', $user)
            ->orderBy('w.nextWithdrawalDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all withdrawals for a user (active and inactive)
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :user')
            ->setParameter('user', $user)
            ->orderBy('w.nextWithdrawalDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find overdue withdrawals that need to be processed
     */
    public function findOverdueWithdrawals(): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.isActive = true')
            ->andWhere('w.nextWithdrawalDate <= CURRENT_TIMESTAMP()')
            ->andWhere('w.endDate IS NULL OR w.endDate > CURRENT_TIMESTAMP()')
            ->orderBy('w.nextWithdrawalDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
