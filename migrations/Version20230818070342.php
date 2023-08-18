<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818070342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campus CHANGE nom nom VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D0968116C6E55B5 ON campus (nom)');
        $this->addSql('ALTER TABLE etat CHANGE libelle libelle VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55CAF762A4D60759 ON etat (libelle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9D0968116C6E55B5 ON campus');
        $this->addSql('ALTER TABLE campus CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_55CAF762A4D60759 ON etat');
        $this->addSql('ALTER TABLE etat CHANGE libelle libelle VARCHAR(50) NOT NULL');
    }
}
