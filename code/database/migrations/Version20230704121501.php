<?php

declare(strict_types = 1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704121501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create User table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable(TableName::TABLE_USER);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('uuid', 'string', ['length' => 255]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('email', 'string', ['length' => 255]);
        $table->addColumn('password', 'string', ['length' => 255]);
        $table->addColumn('created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid']);


        $table = $schema->createTable(TableName::TABLE_OAUTH_CLIENTS);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('identifier', 'string');
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('client_id', 'string', ['length' => 255]);
        $table->addColumn('client_secret', 'string', ['length' => 255]);
        $table->addColumn('redirect_uri', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('provider', 'string', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable(TableName::TABLE_OAUTH_CLIENTS);
        $schema->dropTable(TableName::TABLE_USER);
    }
}
