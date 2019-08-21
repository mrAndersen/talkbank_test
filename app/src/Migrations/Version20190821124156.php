<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190821124156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE promo_code_usage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE promo_code_usage (id INT NOT NULL, promocode_id VARCHAR(6) DEFAULT NULL, "user" VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6025E75FC76C06D9 ON promo_code_usage (promocode_id)');
        $this->addSql('CREATE TABLE promo_code (id VARCHAR(6) NOT NULL, discount DOUBLE PRECISION NOT NULL, max_usages INT NOT NULL, due_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE promo_code_usage ADD CONSTRAINT FK_6025E75FC76C06D9 FOREIGN KEY (promocode_id) REFERENCES promo_code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE promo_code_usage DROP CONSTRAINT FK_6025E75FC76C06D9');
        $this->addSql('DROP SEQUENCE promo_code_usage_id_seq CASCADE');
        $this->addSql('DROP TABLE promo_code_usage');
        $this->addSql('DROP TABLE promo_code');
    }
}
