<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250923202200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates "ingredient" table"';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA content');
        $this->addSql('CREATE TABLE content.ingredient (code VARCHAR NOT NULL, name JSON NOT NULL, type VARCHAR(255) NOT NULL, cost DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX ingredient_code_unique_idx ON content.ingredient (code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE content.ingredient');
    }
}
