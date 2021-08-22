<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626153725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('app_habit');

        $table->addColumn('uuid', 'string');
        $table->addColumn('title', 'string');
        $table->addColumn('from', 'date');
        $table->addColumn('to', 'date');
        $table->addColumn('frequency', 'string');
        $table->addColumn('user', 'string');
        $table->addColumn('moves', 'json');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('app_habit');
    }
}
