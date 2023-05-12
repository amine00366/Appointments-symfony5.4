<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214052615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD user_id INT DEFAULT NULL, ADD doctor_id INT DEFAULT NULL, ADD appointment_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84487F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844A76ED395 ON appointment (user_id)');
        $this->addSql('CREATE INDEX IDX_FE38F84487F4FB17 ON appointment (doctor_id)');
        $this->addSql('ALTER TABLE doctor CHANGE info username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP lastname');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A76ED395');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84487F4FB17');
        $this->addSql('DROP INDEX IDX_FE38F844A76ED395 ON appointment');
        $this->addSql('DROP INDEX IDX_FE38F84487F4FB17 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP user_id, DROP doctor_id, DROP appointment_date');
        $this->addSql('ALTER TABLE doctor CHANGE username info VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD lastname VARCHAR(255) NOT NULL');
    }
}
