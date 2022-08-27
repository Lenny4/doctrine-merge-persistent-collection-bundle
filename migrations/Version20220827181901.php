<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220827181901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE father_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE son_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE father (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE son (id INT NOT NULL, father_id INT NOT NULL, name VARCHAR(255) NOT NULL, age INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E199342C2055B9A2 ON son (father_id)');
        $this->addSql('ALTER TABLE son ADD CONSTRAINT FK_E199342C2055B9A2 FOREIGN KEY (father_id) REFERENCES father (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE father_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE son_id_seq CASCADE');
        $this->addSql('ALTER TABLE son DROP CONSTRAINT FK_E199342C2055B9A2');
        $this->addSql('DROP TABLE father');
        $this->addSql('DROP TABLE son');
    }
}
