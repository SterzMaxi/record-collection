<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719140252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collection (collection_id INT AUTO_INCREMENT NOT NULL, collection_user_id INT DEFAULT NULL, collectionname VARCHAR(255) NOT NULL, style VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FC4D6532F342A144 (collection_user_id), PRIMARY KEY(collection_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE record (record_id INT AUTO_INCREMENT NOT NULL, record_collection_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, format VARCHAR(255) NOT NULL, track_count INT NOT NULL, label VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, genre VARCHAR(255) NOT NULL, collection_name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, booklet_front VARCHAR(255) NOT NULL, booklet_back VARCHAR(255) NOT NULL, grade VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9B349F9158D309B8 (record_collection_id), PRIMARY KEY(record_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE track (track_id INT AUTO_INCREMENT NOT NULL, track_record_id INT DEFAULT NULL, artist VARCHAR(255) NOT NULL, track_number VARCHAR(255) NOT NULL, track_title VARCHAR(255) NOT NULL, track_time VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, listen_link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D6E3F8A64C45DBF8 (track_record_id), PRIMARY KEY(track_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, user_email VARCHAR(255) NOT NULL, user_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collection ADD CONSTRAINT FK_FC4D6532F342A144 FOREIGN KEY (collection_user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F9158D309B8 FOREIGN KEY (record_collection_id) REFERENCES collection (collection_id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A64C45DBF8 FOREIGN KEY (track_record_id) REFERENCES record (record_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collection DROP FOREIGN KEY FK_FC4D6532F342A144');
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F9158D309B8');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A64C45DBF8');
        $this->addSql('DROP TABLE collection');
        $this->addSql('DROP TABLE record');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE user');
    }
}
