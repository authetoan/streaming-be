<?php

declare(strict_types = 1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705055558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Streams ';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable(TableName::TABLE_STREAMS);
        $table->addColumn('id', 'bigint')
            ->setAutoincrement(true)
            ->setUnsigned(true);
        $table->addColumn('uuid', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid']);
        $table->addColumn('title', 'string', ['length' => 255]);
        $table->addColumn('stream_key', 'string', ['length' => 255]);
        $table->addColumn('is_active', 'boolean', ['default' => 1]);
        $table->addColumn('user_id', 'integer');
        $table->addColumn('room_id', 'integer');
        $table->addColumn('record_url', 'text', ['length' => 255, 'notnull' => false]);
        $table->addForeignKeyConstraint(TableName::TABLE_USER, ['user_id'], ['id']);
        $table->addColumn('created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable(TableName::TABLE_STREAMS);
    }
}
