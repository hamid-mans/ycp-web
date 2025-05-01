<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501122712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_softwares_forbidden (user_id INT NOT NULL, software_id INT NOT NULL, INDEX IDX_E9299268A76ED395 (user_id), INDEX IDX_E9299268D7452741 (software_id), PRIMARY KEY(user_id, software_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_softwares_forbidden ADD CONSTRAINT FK_E9299268A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_softwares_forbidden ADD CONSTRAINT FK_E9299268D7452741 FOREIGN KEY (software_id) REFERENCES software (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_softwares_forbidden DROP FOREIGN KEY FK_E9299268A76ED395');
        $this->addSql('ALTER TABLE user_softwares_forbidden DROP FOREIGN KEY FK_E9299268D7452741');
        $this->addSql('DROP TABLE user_softwares_forbidden');
    }
}
