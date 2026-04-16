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

        $this->addSql(<<<SQL
            CREATE TABLE content.ingredient (
                id UUID NOT NULL,
                code VARCHAR(50) NOT NULL,
                name JSONB NOT NULL,
                type TEXT NOT NULL,
                cost DOUBLE PRECISION NOT NULL,
                created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL,
                PRIMARY KEY (id)
            )
            SQL
        );

        $this->addSql('CREATE UNIQUE INDEX ingredient_code_unique_idx ON content.ingredient (code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE content.ingredient');
    }
}
