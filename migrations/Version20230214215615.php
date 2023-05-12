<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214215615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844C54C8C93 FOREIGN KEY (type_id) REFERENCES typeappoinment (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844C54C8C93 ON appointment (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844C54C8C93');
        $this->addSql('DROP INDEX IDX_FE38F844C54C8C93 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP type_id');
    }
}
