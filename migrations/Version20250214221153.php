<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214221153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe ADD appetit_bebe VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE suivi_grossesse CHANGE poids poids DOUBLE PRECISION NOT NULL, CHANGE tension tension DOUBLE PRECISION NOT NULL, CHANGE symptomes symptomes VARCHAR(255) NOT NULL, CHANGE etat_grossesse etat_grossesse VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe DROP appetit_bebe');
        $this->addSql('ALTER TABLE suivi_grossesse CHANGE poids poids DOUBLE PRECISION DEFAULT NULL, CHANGE tension tension DOUBLE PRECISION DEFAULT NULL, CHANGE symptomes symptomes VARCHAR(255) DEFAULT NULL, CHANGE etat_grossesse etat_grossesse VARCHAR(255) DEFAULT NULL');
    }
}
