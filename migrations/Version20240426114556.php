<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426114556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album_entity (id INT AUTO_INCREMENT NOT NULL, artist_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, year INT NOT NULL, UNIQUE INDEX UNIQ_DC5379DFB7970CF8 (artist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE track_entity (id INT AUTO_INCREMENT NOT NULL, album_entity_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, duration INT NOT NULL, INDEX IDX_46EA8581EC0B7510 (album_entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album_entity ADD CONSTRAINT FK_DC5379DFB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist_entity (id)');
        $this->addSql('ALTER TABLE track_entity ADD CONSTRAINT FK_46EA8581EC0B7510 FOREIGN KEY (album_entity_id) REFERENCES album_entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_entity DROP FOREIGN KEY FK_DC5379DFB7970CF8');
        $this->addSql('ALTER TABLE track_entity DROP FOREIGN KEY FK_46EA8581EC0B7510');
        $this->addSql('DROP TABLE album_entity');
        $this->addSql('DROP TABLE artist_entity');
        $this->addSql('DROP TABLE track_entity');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
