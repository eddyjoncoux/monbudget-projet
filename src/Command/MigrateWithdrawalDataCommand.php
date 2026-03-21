<?php

namespace App\Command;

use App\Entity\Withdrawal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:migrate-withdrawal-data',
    description: 'Migrate existing withdrawal data to use dayOfMonth instead of startDate',
)]
class MigrateWithdrawalDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $withdrawals = $this->entityManager->getRepository(Withdrawal::class)->findAll();

        if (empty($withdrawals)) {
            $io->success('No withdrawals to migrate.');
            return Command::SUCCESS;
        }

        $io->info('Migrating ' . count($withdrawals) . ' withdrawal(s)...');

        foreach ($withdrawals as $withdrawal) {
            // Si dayOfMonth n'est pas défini, l'extraire de nextWithdrawalDate
            if ($withdrawal->getDayOfMonth() === null && $withdrawal->getNextWithdrawalDate()) {
                $dayOfMonth = (int) $withdrawal->getNextWithdrawalDate()->format('d');
                $withdrawal->setDayOfMonth($dayOfMonth);
                $io->text("Migrated withdrawal ID {$withdrawal->getId()}: dayOfMonth = {$dayOfMonth}");
            }
        }

        $this->entityManager->flush();

        $io->success('Migration completed successfully!');

        return Command::SUCCESS;
    }
}