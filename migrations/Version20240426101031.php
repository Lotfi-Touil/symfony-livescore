<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426101031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE score_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE score (id INT NOT NULL, value NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE event_participant ADD score_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B89112EB0A51 FOREIGN KEY (score_id) REFERENCES score (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7C16B89112EB0A51 ON event_participant (score_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event_participant DROP CONSTRAINT FK_7C16B89112EB0A51');
        $this->addSql('DROP SEQUENCE score_id_seq CASCADE');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP INDEX IDX_7C16B89112EB0A51');
        $this->addSql('ALTER TABLE event_participant DROP score_id');
    }
}
