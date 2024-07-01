<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240628175918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE record (record_id INT AUTO_INCREMENT NOT NULL, record_user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, track_number VARCHAR(255) NOT NULL, track_title VARCHAR(255) NOT NULL, track_time VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, release_date DATE NOT NULL, genre VARCHAR(255) NOT NULL, collection_name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, booklet_front LONGBLOB NOT NULL, booklet_back LONGBLOB NOT NULL, listen_link VARCHAR(255) NOT NULL, `condition` VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9B349F9110B1D3E8 (record_user_id), PRIMARY KEY(record_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, user_email VARCHAR(255) NOT NULL, user_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F9110B1D3E8 FOREIGN KEY (record_user_id) REFERENCES user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F9110B1D3E8');
        $this->addSql('DROP TABLE record');
        $this->addSql('DROP TABLE user');
    }
}
