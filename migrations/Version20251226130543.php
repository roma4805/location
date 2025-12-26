<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226130543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, prix_total NUMERIC(10, 2) NOT NULL, statut VARCHAR(20) NOT NULL, client_id INT NOT NULL, voiture_id INT NOT NULL, INDEX IDX_6034999319EB6921 (client_id), INDEX IDX_60349993181A8BA (voiture_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_6034999319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_60349993181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_6034999319EB6921');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_60349993181A8BA');
        $this->addSql('DROP TABLE contrat');
    }
}
