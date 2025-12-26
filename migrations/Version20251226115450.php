<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226115450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(50) NOT NULL, modele VARCHAR(50) NOT NULL, immatriculation VARCHAR(20) NOT NULL, prix_journalier NUMERIC(10, 2) NOT NULL, statut VARCHAR(20) NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E9E2810FBE73422E (immatriculation), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE voiture');
    }
}
