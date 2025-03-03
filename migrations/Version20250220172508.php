<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220172508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, id_medecin_id INT DEFAULT NULL, jour DATE NOT NULL, heures_disp JSON NOT NULL COMMENT \'(DC2Type:json)\', statut_disp VARCHAR(255) NOT NULL, INDEX IDX_2CBACE2FA1799A53 (id_medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, heure_r_id INT DEFAULT NULL, id_medecin_id INT DEFAULT NULL, motif VARCHAR(255) NOT NULL, symptomes VARCHAR(255) NOT NULL, traitement_en_cours VARCHAR(255) DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL, statut_rendez_vous VARCHAR(255) DEFAULT NULL, creation DATE DEFAULT NULL, heure_string VARCHAR(255) DEFAULT NULL, jour DATE NOT NULL, INDEX IDX_65E8AA0A12450429 (heure_r_id), INDEX IDX_65E8AA0AA1799A53 (id_medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FA1799A53 FOREIGN KEY (id_medecin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A12450429 FOREIGN KEY (heure_r_id) REFERENCES disponibilite (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AA1799A53 FOREIGN KEY (id_medecin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suivi_grossesse ADD CONSTRAINT FK_746122E66B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_746122E66B899279 ON suivi_grossesse (patient_id)');
        $this->addSql('ALTER TABLE user CHANGE is_banned is_banned TINYINT(1) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FA1799A53');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A12450429');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AA1799A53');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('ALTER TABLE suivi_grossesse DROP FOREIGN KEY FK_746122E66B899279');
        $this->addSql('DROP INDEX IDX_746122E66B899279 ON suivi_grossesse');
        $this->addSql('ALTER TABLE user CHANGE is_banned is_banned TINYINT(1) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL');
    }
}
