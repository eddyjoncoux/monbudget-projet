<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260711150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user_id column to category table';
    }

    public function up(Schema $schema): void
    {
        // Add user_id column if it doesn't exist
        $this->addSql('ALTER TABLE `category` ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `category` ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1A76ED395 ON `category` (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `category` DROP FOREIGN KEY FK_64C19C1A76ED395');
        $this->addSql('DROP INDEX IDX_64C19C1A76ED395 ON `category`');
        $this->addSql('ALTER TABLE `category` DROP user_id');
    }
}
