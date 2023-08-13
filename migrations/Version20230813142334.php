<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813142334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD platt_id INT DEFAULT NULL, ADD etat VARCHAR(255) DEFAULT NULL, ADD nombre INT DEFAULT NULL, DROP nom_commande, DROP nombre_plats, CHANGE prix_total prix_total INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D37C3D971 FOREIGN KEY (platt_id) REFERENCES plat (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D37C3D971 ON commande (platt_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D37C3D971');
        $this->addSql('DROP INDEX IDX_6EEAA67D37C3D971 ON commande');
        $this->addSql('ALTER TABLE commande ADD nom_commande VARCHAR(50) NOT NULL, ADD nombre_plats INT NOT NULL, DROP platt_id, DROP etat, DROP nombre, CHANGE prix_total prix_total DOUBLE PRECISION NOT NULL');
    }
}
