<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220420222554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dino_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fixer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_active_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_step_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, country VARCHAR(60) NOT NULL, region VARCHAR(100) NOT NULL, city VARCHAR(35) NOT NULL, postcode VARCHAR(10) NOT NULL, street VARCHAR(100) NOT NULL, additional VARCHAR(100) DEFAULT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, settings TEXT NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76E7927C74 ON admin (email)');
        $this->addSql('COMMENT ON COLUMN admin.settings IS \'(DC2Type:object)\'');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, address_id INT DEFAULT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, settings TEXT NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09E7927C74 ON customer (email)');
        $this->addSql('CREATE INDEX IDX_81398E09F5B7AF75 ON customer (address_id)');
        $this->addSql('COMMENT ON COLUMN customer.settings IS \'(DC2Type:object)\'');
        $this->addSql('CREATE TABLE dino (id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F4757507989D9B62 ON dino (slug)');
        $this->addSql('CREATE INDEX IDX_F475750712469DE2 ON dino (category_id)');
        $this->addSql('CREATE TABLE fixer (id INT NOT NULL, address_id INT DEFAULT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, alias VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, slug VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(50) NOT NULL, picture VARCHAR(255) DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, settings TEXT NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A1D7B63E16C6B94 ON fixer (alias)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A1D7B63E7927C74 ON fixer (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A1D7B63989D9B62 ON fixer (slug)');
        $this->addSql('CREATE INDEX IDX_6A1D7B63F5B7AF75 ON fixer (address_id)');
        $this->addSql('COMMENT ON COLUMN fixer.settings IS \'(DC2Type:object)\'');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, service_id INT DEFAULT NULL, customer_id INT NOT NULL, category_id INT DEFAULT NULL, dino_id INT DEFAULT NULL, reference VARCHAR(8) NOT NULL, subject VARCHAR(255) NOT NULL, description TEXT NOT NULL, slug VARCHAR(8) NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, expected_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B978F9FAEA34913 ON request (reference)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B978F9F989D9B62 ON request (slug)');
        $this->addSql('CREATE INDEX IDX_3B978F9FED5CA9E6 ON request (service_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F9395C3F3 ON request (customer_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F12469DE2 ON request (category_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9F1129DB41 ON request (dino_id)');
        $this->addSql('CREATE TABLE request_active (id INT NOT NULL, request_id INT DEFAULT NULL, fixer_id INT DEFAULT NULL, step_id INT DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_473FF9427EB8A5 ON request_active (request_id)');
        $this->addSql('CREATE INDEX IDX_473FF9831D7CC7 ON request_active (fixer_id)');
        $this->addSql('CREATE INDEX IDX_473FF973B21E9C ON request_active (step_id)');
        $this->addSql('CREATE TABLE request_log (id INT NOT NULL, request_id INT DEFAULT NULL, event VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42152989427EB8A5 ON request_log (request_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, customer_id INT NOT NULL, fixer_id INT DEFAULT NULL, service_id INT DEFAULT NULL, rate INT NOT NULL, message VARCHAR(255) NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C69395C3F3 ON review (customer_id)');
        $this->addSql('CREATE INDEX IDX_794381C6831D7CC7 ON review (fixer_id)');
        $this->addSql('CREATE INDEX IDX_794381C6ED5CA9E6 ON review (service_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, fixer_id INT NOT NULL, dino_id INT DEFAULT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, rating DOUBLE PRECISION NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E19D9AD2989D9B62 ON service (slug)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2831D7CC7 ON service (fixer_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD21129DB41 ON service (dino_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD212469DE2 ON service (category_id)');
        $this->addSql('CREATE TABLE service_step (id INT NOT NULL, service_id INT DEFAULT NULL, step INT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8DFA4AD0ED5CA9E6 ON service_step (service_id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dino ADD CONSTRAINT FK_F475750712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fixer ADD CONSTRAINT FK_6A1D7B63F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F1129DB41 FOREIGN KEY (dino_id) REFERENCES dino (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_active ADD CONSTRAINT FK_473FF9427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_active ADD CONSTRAINT FK_473FF9831D7CC7 FOREIGN KEY (fixer_id) REFERENCES fixer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_active ADD CONSTRAINT FK_473FF973B21E9C FOREIGN KEY (step_id) REFERENCES service_step (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_log ADD CONSTRAINT FK_42152989427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C69395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6831D7CC7 FOREIGN KEY (fixer_id) REFERENCES fixer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2831D7CC7 FOREIGN KEY (fixer_id) REFERENCES fixer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD21129DB41 FOREIGN KEY (dino_id) REFERENCES dino (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service_step ADD CONSTRAINT FK_8DFA4AD0ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E09F5B7AF75');
        $this->addSql('ALTER TABLE fixer DROP CONSTRAINT FK_6A1D7B63F5B7AF75');
        $this->addSql('ALTER TABLE dino DROP CONSTRAINT FK_F475750712469DE2');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F12469DE2');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD212469DE2');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F9395C3F3');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C69395C3F3');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F1129DB41');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD21129DB41');
        $this->addSql('ALTER TABLE request_active DROP CONSTRAINT FK_473FF9831D7CC7');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6831D7CC7');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2831D7CC7');
        $this->addSql('ALTER TABLE request_active DROP CONSTRAINT FK_473FF9427EB8A5');
        $this->addSql('ALTER TABLE request_log DROP CONSTRAINT FK_42152989427EB8A5');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FED5CA9E6');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6ED5CA9E6');
        $this->addSql('ALTER TABLE service_step DROP CONSTRAINT FK_8DFA4AD0ED5CA9E6');
        $this->addSql('ALTER TABLE request_active DROP CONSTRAINT FK_473FF973B21E9C');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admin_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dino_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fixer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_active_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_log_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_step_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE dino');
        $this->addSql('DROP TABLE fixer');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE request_active');
        $this->addSql('DROP TABLE request_log');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_step');
    }
}
