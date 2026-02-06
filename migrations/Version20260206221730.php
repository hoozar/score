<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206221730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(31) NOT NULL, timestamp INTEGER NOT NULL, data_type VARCHAR(31) NOT NULL, data_player VARCHAR(255) DEFAULT NULL, data_team_id VARCHAR(127) NOT NULL, data_match_id VARCHAR(127) NOT NULL, data_minute INTEGER NOT NULL, data_second INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE statistic (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, match_id VARCHAR(127) NOT NULL, team_id VARCHAR(127) NOT NULL, type VARCHAR(31) NOT NULL, count INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE statistic');
    }
}
