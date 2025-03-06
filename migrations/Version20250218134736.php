<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218134736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, medicament VARCHAR(255) NOT NULL, posologie VARCHAR(255) NOT NULL, date_prescription DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement (id INT AUTO_INCREMENT NOT NULL, id_ordonnance_id INT DEFAULT NULL, ordonnance_id INT DEFAULT NULL, date_creation DATE NOT NULL, historique_traitement VARCHAR(255) NOT NULL, INDEX IDX_2A356D2795DAEAEA (id_ordonnance_id), INDEX IDX_2A356D272BF23B8F (ordonnance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D2795DAEAEA FOREIGN KEY (id_ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D272BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D2795DAEAEA');
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D272BF23B8F');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE traitement');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
