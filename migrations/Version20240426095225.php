<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426095225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE event_participant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE score_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event_participant (id INT NOT NULL, event_id INT NOT NULL, participant_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C16B89171F7E88B ON event_participant (event_id)');
        $this->addSql('CREATE INDEX IDX_7C16B8919D1C3019 ON event_participant (participant_id)');
        $this->addSql('CREATE TABLE score_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B89171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B8919D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD date_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE sport ADD score_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE sport ADD CONSTRAINT FK_1A85EFD2A4C4A907 FOREIGN KEY (score_type_id) REFERENCES score_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1A85EFD2A4C4A907 ON sport (score_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sport DROP CONSTRAINT FK_1A85EFD2A4C4A907');
        $this->addSql('DROP SEQUENCE event_participant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE score_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE event_participant DROP CONSTRAINT FK_7C16B89171F7E88B');
        $this->addSql('ALTER TABLE event_participant DROP CONSTRAINT FK_7C16B8919D1C3019');
        $this->addSql('DROP TABLE event_participant');
        $this->addSql('DROP TABLE score_type');
        $this->addSql('ALTER TABLE event DROP date_end');
        $this->addSql('DROP INDEX IDX_1A85EFD2A4C4A907');
        $this->addSql('ALTER TABLE sport DROP score_type_id');
    }
}
