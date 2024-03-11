<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308173628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente CHANGE nombre nombre VARCHAR(255) DEFAULT NULL, CHANGE apellidos apellidos VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE incidencia CHANGE titulo titulo VARCHAR(255) DEFAULT NULL, CHANGE estado estado VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE apellidos apellidos VARCHAR(255) DEFAULT NULL, CHANGE foto foto VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente CHANGE nombre nombre VARCHAR(255) DEFAULT \'NULL\', CHANGE apellidos apellidos VARCHAR(255) DEFAULT \'NULL\', CHANGE telefono telefono VARCHAR(255) DEFAULT \'NULL\', CHANGE direccion direccion VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE incidencia CHANGE titulo titulo VARCHAR(255) DEFAULT \'NULL\', CHANGE estado estado VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE apellidos apellidos VARCHAR(255) DEFAULT \'NULL\', CHANGE foto foto VARCHAR(255) DEFAULT \'NULL\'');
    }
}
