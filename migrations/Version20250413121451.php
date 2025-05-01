<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413121451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server ADD remote_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6AEC87838 FOREIGN KEY (remote_control_id) REFERENCES remote_control_tool (id)');
        $this->addSql('CREATE INDEX IDX_5A6DD5F6AEC87838 ON server (remote_control_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6AEC87838');
        $this->addSql('DROP INDEX IDX_5A6DD5F6AEC87838 ON server');
        $this->addSql('ALTER TABLE server DROP remote_control_id');
    }
}
