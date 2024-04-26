<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426115158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_entity ADD artist_entity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE album_entity ADD CONSTRAINT FK_DC5379DF9DF116D5 FOREIGN KEY (artist_entity_id) REFERENCES artist_entity (id)');
        $this->addSql('CREATE INDEX IDX_DC5379DF9DF116D5 ON album_entity (artist_entity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_entity DROP FOREIGN KEY FK_DC5379DF9DF116D5');
        $this->addSql('DROP INDEX IDX_DC5379DF9DF116D5 ON album_entity');
        $this->addSql('ALTER TABLE album_entity DROP artist_entity_id');
    }
}
