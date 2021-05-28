<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528085724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('todo');

        $table->addColumn('uuid', 'string');
        $table->addColumn('title', 'string');
        $table->addColumn('scheduled_date', 'date');
        $table->addColumn('status', 'string');
        $table->addColumn('user', 'int');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('todo');
    }
}
