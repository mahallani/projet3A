<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219165523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe DROP FOREIGN KEY FK_244422CF6B899279');
        $this->addSql('DROP INDEX IDX_244422CF6B899279 ON suivi_bebe');
        $this->addSql('ALTER TABLE suivi_bebe DROP COLUMN patient_id');
        $this->addSql('ALTER TABLE suivi_grossesse ADD CONSTRAINT FK_746122E66B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_746122E66B899279 ON suivi_grossesse (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_bebe ADD patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE suivi_bebe ADD CONSTRAINT FK_244422CF6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_244422CF6B899279 ON suivi_bebe (patient_id)');
        $this->addSql('ALTER TABLE suivi_grossesse DROP FOREIGN KEY FK_746122E66B899279');
        $this->addSql('DROP INDEX IDX_746122E66B899279 ON suivi_grossesse');
    }
}
