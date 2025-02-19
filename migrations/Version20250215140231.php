<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215140231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe DROP FOREIGN KEY FK_244422CF56297EB8');
        $this->addSql('ALTER TABLE suivi_bebe CHANGE etat_sante etat_sante VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE suivi_bebe ADD CONSTRAINT FK_244422CF56297EB8 FOREIGN KEY (suivi_grossesse_id) REFERENCES suivi_grossesse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe DROP FOREIGN KEY FK_244422CF56297EB8');
        $this->addSql('ALTER TABLE suivi_bebe CHANGE etat_sante etat_sante VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE suivi_bebe ADD CONSTRAINT FK_244422CF56297EB8 FOREIGN KEY (suivi_grossesse_id) REFERENCES suivi_grossesse (id)');
    }
}
