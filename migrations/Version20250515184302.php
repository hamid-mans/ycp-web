<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515184302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE webdev (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, serial_number VARCHAR(255) DEFAULT NULL, activation_key VARCHAR(255) DEFAULT NULL, echeance_date DATE DEFAULT NULL, INDEX IDX_892A73789395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE webdev ADD CONSTRAINT FK_892A73789395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE webdev DROP FOREIGN KEY FK_892A73789395C3F3');
        $this->addSql('DROP TABLE webdev');
    }
}
