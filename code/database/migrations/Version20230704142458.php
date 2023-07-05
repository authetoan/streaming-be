<?php

declare(strict_types = 1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704142458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create roles and permissions tables';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(TableName::TABLE_PERMISSION);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string');
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name']);

        $table = $schema->createTable(TableName::TABLE_ROLE);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string');
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['name']);

        $table = $schema->createTable(TableName::TABLE_USER_HAS_PERMISSIONS);
        $table->addColumn('permission_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->setPrimaryKey(['permission_id','user_id']);
        $table->addForeignKeyConstraint(TableName::TABLE_PERMISSION, ['permission_id'], ['id']);
        $table->addForeignKeyConstraint(TableName::TABLE_USER, ['user_id'], ['id']);

        $table = $schema->createTable(TableName::TABLE_USER_HAS_ROLES);
        $table->addColumn('role_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->setPrimaryKey(['role_id','user_id']);
        $table->addForeignKeyConstraint(TableName::TABLE_USER, ['user_id'], ['id']);
        $table->addForeignKeyConstraint(TableName::TABLE_ROLE, ['role_id'], ['id']);

        $table = $schema->createTable(TableName::TABLE_ROLE_HAS_PERMISSIONS);
        $table->addColumn('role_id', 'integer');
        $table->addColumn('permission_id', 'integer');
        $table->setPrimaryKey(['role_id','permission_id']);
        $table->addForeignKeyConstraint(TableName::TABLE_PERMISSION, ['permission_id'], ['id']);
        $table->addForeignKeyConstraint(TableName::TABLE_ROLE, ['role_id'], ['id']);

        $table = $schema->createTable(TableName::TABLE_CLIENT_HAS_PERMISSIONS);
        $table->addColumn('client_id', 'integer');
        $table->addColumn('permission_id', 'integer');
        $table->setPrimaryKey(['client_id','permission_id']);
        $table->addForeignKeyConstraint(TableName::TABLE_PERMISSION, ['permission_id'], ['id']);
        $table->addForeignKeyConstraint(TableName::TABLE_OAUTH_CLIENTS, ['client_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(TableName::TABLE_ROLE_HAS_PERMISSIONS);
        $schema->dropTable(TableName::TABLE_USER_HAS_ROLES);
        $schema->dropTable(TableName::TABLE_USER_HAS_PERMISSIONS);
        $schema->dropTable(TableName::TABLE_ROLE);
        $schema->dropTable(TableName::TABLE_PERMISSION);
    }
}
