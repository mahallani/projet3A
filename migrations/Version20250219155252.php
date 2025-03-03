<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219155252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE suivi_bebe (id INT AUTO_INCREMENT NOT NULL, suivi_grossesse_id INT NOT NULL, date_suivi DATETIME NOT NULL, poids_bebe DOUBLE PRECISION NOT NULL, taille_bebe DOUBLE PRECISION NOT NULL, etat_sante VARCHAR(255) DEFAULT NULL, battement_coeur DOUBLE PRECISION NOT NULL, appetitBebe VARCHAR(50) NOT NULL, INDEX IDX_244422CF56297EB8 (suivi_grossesse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_grossesse (id INT AUTO_INCREMENT NOT NULL, date_suivi DATETIME DEFAULT NULL, poids DOUBLE PRECISION NOT NULL, tension DOUBLE PRECISION NOT NULL, symptomes VARCHAR(255) NOT NULL, etat_grossesse VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suivi_bebe ADD CONSTRAINT FK_244422CF56297EB8 FOREIGN KEY (suivi_grossesse_id) REFERENCES suivi_grossesse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE is_banned is_banned TINYINT(1) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe DROP FOREIGN KEY FK_244422CF56297EB8');
        $this->addSql('DROP TABLE suivi_bebe');
        $this->addSql('DROP TABLE suivi_grossesse');
        $this->addSql('ALTER TABLE user CHANGE is_banned is_banned TINYINT(1) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL');
    }
}
