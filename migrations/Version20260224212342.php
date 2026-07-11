<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224212342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE withdrawal (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, next_withdrawal_date DATETIME NOT NULL, last_withdrawal_date DATETIME DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, frequency VARCHAR(255) NOT NULL, is_active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, category_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_6D2D3B4512469DE2 (category_id), INDEX IDX_6D2D3B45A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE withdrawal ADD CONSTRAINT FK_6D2D3B4512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE withdrawal ADD CONSTRAINT FK_6D2D3B45A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE withdrawal DROP FOREIGN KEY FK_6D2D3B4512469DE2');
        $this->addSql('ALTER TABLE withdrawal DROP FOREIGN KEY FK_6D2D3B45A76ED395');
        $this->addSql('DROP TABLE withdrawal');
        $this->addSql('ALTER TABLE transaction CHANGE user_id user_id INT NOT NULL');
    }
}
