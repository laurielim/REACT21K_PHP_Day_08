<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510122700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, ingredients CLOB NOT NULL --(DC2Type:array)
        , instructions CLOB NOT NULL --(DC2Type:array)
        )');
        $this->addSql('ALTER TABLE ingredients ADD COLUMN ingredient_id INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recipe_list');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredients AS SELECT id, name FROM ingredients');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('CREATE TABLE ingredients (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO ingredients (id, name) SELECT id, name FROM __temp__ingredients');
        $this->addSql('DROP TABLE __temp__ingredients');
    }
}
