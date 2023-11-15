<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113164619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depot_mr005_formulaire (id INT AUTO_INCREMENT NOT NULL, ipe VARCHAR(9) NOT NULL, finess VARCHAR(9) NOT NULL, raison_sociale VARCHAR(100) NOT NULL, civilite VARCHAR(15) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, courriel VARCHAR(255) NOT NULL, numero_recepice VARCHAR(255) NOT NULL, date_attribution VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5143F1A6734D95EF (numero_recepice), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE depot_mr005_formulaire');
    }
}
