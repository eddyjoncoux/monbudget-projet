<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260711160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ensure foreign key constraint on user_id exists (if column was added by previous migration)';
    }

    public function up(Schema $schema): void
    {
        // This migration is a no-op now since Version20260711150000 already handles the column creation
        // It exists only to prevent issues if migrations are replayed
    }

    public function down(Schema $schema): void
    {
        // Nothing to do on rollback
    }
}

