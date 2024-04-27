<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426103941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT fk_d79f6b1171f7e88b');
        $this->addSql('DROP INDEX idx_d79f6b1171f7e88b');
        $this->addSql('ALTER TABLE participant DROP event_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE participant ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fk_d79f6b1171f7e88b FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d79f6b1171f7e88b ON participant (event_id)');
    }
}
