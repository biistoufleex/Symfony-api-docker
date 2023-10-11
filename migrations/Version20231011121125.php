<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011121125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_autorisation CHANGE perimetre perimetre VARCHAR(50) NOT NULL, CHANGE type_autorisation type_autorisation VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organisation_autorisation CHANGE perimetre perimetre LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE type_autorisation type_autorisation LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
