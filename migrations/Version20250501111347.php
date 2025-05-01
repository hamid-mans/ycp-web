<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501111347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_servers_forbidden (user_id INT NOT NULL, server_id INT NOT NULL, INDEX IDX_C882636CA76ED395 (user_id), INDEX IDX_C882636C1844E6B7 (server_id), PRIMARY KEY(user_id, server_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_servers_forbidden ADD CONSTRAINT FK_C882636CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_servers_forbidden ADD CONSTRAINT FK_C882636C1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_servers_forbidden DROP FOREIGN KEY FK_C882636CA76ED395');
        $this->addSql('ALTER TABLE user_servers_forbidden DROP FOREIGN KEY FK_C882636C1844E6B7');
        $this->addSql('DROP TABLE user_servers_forbidden');
    }
}
