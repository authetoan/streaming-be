<?php

declare(strict_types = 1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704144200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable(TableName::TABLE_PASSWORD_RESETS);
        $table->addColumn('id', 'bigint')
            ->setAutoincrement(true)
            ->setUnsigned(true);
        $table->addColumn('email', 'string');
        $table->addColumn('token', 'string');
        $table->addColumn('created_at', 'datetime')->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn('updated_at', 'datetime')->setDefault('CURRENT_TIMESTAMP');
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable(TableName::TABLE_PASSWORD_RESETS);
    }
}
