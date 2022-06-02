<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220507143125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add item table and constraint to user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE item (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                owner_id INT UNSIGNED NOT NULL,
                name VARCHAR(255) DEFAULT NULL,
                model VARCHAR(255) DEFAULT NULL,
                price NUMERIC(10, 2) DEFAULT NULL,
                buy_date DATETIME DEFAULT NULL,
                end_date DATETIME DEFAULT NULL,
                plan_to_use_in_months INT UNSIGNED DEFAULT NULL,
                INDEX IDX_1F1B251E7E3C61F9 (owner_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE item
                ADD CONSTRAINT FK_1F1B251E7E3C61F9
                    FOREIGN KEY (owner_id)
                    REFERENCES `user` (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE item');
    }
}
