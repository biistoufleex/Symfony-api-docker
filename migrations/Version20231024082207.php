<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024082207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_application_entity ADD habilitation_organisation_perimetre VARCHAR(255) NOT NULL, ADD habilitation_domaine_perimetre VARCHAR(255) NOT NULL, DROP perimetre, DROP role_domaine_scansante');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_application_entity ADD perimetre VARCHAR(255) NOT NULL, ADD role_domaine_scansante VARCHAR(255) NOT NULL, DROP habilitation_organisation_perimetre, DROP habilitation_domaine_perimetre');
    }
}
