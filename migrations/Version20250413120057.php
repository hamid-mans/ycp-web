<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413120057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6C3568B40');
        $this->addSql('DROP INDEX IDX_5A6DD5F6C3568B40 ON server');
        $this->addSql('ALTER TABLE server DROP customers_id, DROP hostname, DROP local_ip, DROP public_ip, DROP username, DROP password, DROP pdm, DROP pdm_username, DROP pdm_password, DROP comment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server ADD customers_id INT NOT NULL, ADD hostname VARCHAR(255) NOT NULL, ADD local_ip VARCHAR(255) DEFAULT NULL, ADD public_ip VARCHAR(255) DEFAULT NULL, ADD username VARCHAR(255) DEFAULT NULL, ADD password VARCHAR(255) DEFAULT NULL, ADD pdm VARCHAR(255) DEFAULT NULL, ADD pdm_username VARCHAR(255) DEFAULT NULL, ADD pdm_password VARCHAR(255) DEFAULT NULL, ADD comment LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6C3568B40 FOREIGN KEY (customers_id) REFERENCES customers (id)');
        $this->addSql('CREATE INDEX IDX_5A6DD5F6C3568B40 ON server (customers_id)');
    }
}
