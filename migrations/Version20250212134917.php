<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212134917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // Ajouter uniquement les colonnes qui n'existent pas déjà
    $this->addSql('ALTER TABLE suivi_grossesse 
                    ADD COLUMN IF NOT EXISTS date_suivi DATETIME NOT NULL, 
                    ADD COLUMN IF NOT EXISTS poids DOUBLE PRECISION NOT NULL, 
                    ADD COLUMN IF NOT EXISTS tension DOUBLE PRECISION NOT NULL, 
                    ADD COLUMN IF NOT EXISTS symptomes VARCHAR(255) NOT NULL, 
                    ADD COLUMN IF NOT EXISTS etat_grossesse VARCHAR(255) NOT NULL');
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE suivi_grossesse DROP date_suivi, DROP poids, DROP tension, DROP symptomes, DROP etat_grossesse');
    }
}
