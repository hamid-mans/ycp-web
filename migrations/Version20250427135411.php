<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427135411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE software (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, type_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_77D068CF9395C3F3 (customer_id), INDEX IDX_77D068CFC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE software ADD CONSTRAINT FK_77D068CF9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE software ADD CONSTRAINT FK_77D068CFC54C8C93 FOREIGN KEY (type_id) REFERENCES type_software (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE software DROP FOREIGN KEY FK_77D068CF9395C3F3');
        $this->addSql('ALTER TABLE software DROP FOREIGN KEY FK_77D068CFC54C8C93');
        $this->addSql('DROP TABLE software');
    }
}
