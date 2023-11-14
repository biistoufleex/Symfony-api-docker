<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114141008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot_mr005 ADD depot_mr005_validation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE depot_mr005 ADD CONSTRAINT FK_ADD9681159C62465 FOREIGN KEY (depot_mr005_validation_id) REFERENCES depot_mr005_validation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ADD9681159C62465 ON depot_mr005 (depot_mr005_validation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot_mr005 DROP FOREIGN KEY FK_ADD9681159C62465');
        $this->addSql('DROP INDEX UNIQ_ADD9681159C62465 ON depot_mr005');
        $this->addSql('ALTER TABLE depot_mr005 DROP depot_mr005_validation_id');
    }
}
