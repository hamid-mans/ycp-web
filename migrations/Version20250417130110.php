<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417130110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prestataires_customers (prestataires_id INT NOT NULL, customers_id INT NOT NULL, INDEX IDX_75569910B2CAA6B8 (prestataires_id), INDEX IDX_75569910C3568B40 (customers_id), PRIMARY KEY(prestataires_id, customers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestataires_customers ADD CONSTRAINT FK_75569910B2CAA6B8 FOREIGN KEY (prestataires_id) REFERENCES prestataires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestataires_customers ADD CONSTRAINT FK_75569910C3568B40 FOREIGN KEY (customers_id) REFERENCES customers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestataires_customers DROP FOREIGN KEY FK_75569910B2CAA6B8');
        $this->addSql('ALTER TABLE prestataires_customers DROP FOREIGN KEY FK_75569910C3568B40');
        $this->addSql('DROP TABLE prestataires_customers');
    }
}
