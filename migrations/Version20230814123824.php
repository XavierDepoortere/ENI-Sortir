<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814123824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie ADD etats_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2CA7E0C2E FOREIGN KEY (etats_id) REFERENCES etat (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2CA7E0C2E ON sortie (etats_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2CA7E0C2E');
        $this->addSql('DROP INDEX IDX_3C3FD3F2CA7E0C2E ON sortie');
        $this->addSql('ALTER TABLE sortie DROP etats_id');
    }
}
