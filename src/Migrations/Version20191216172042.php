<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216172042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE livre CHANGE prix prix DOUBLE PRECISION DEFAULT NULL, CHANGE annee annee INT DEFAULT NULL, CHANGE langue langue VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adherent ADD roles TINYTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE code_commune code_commune VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pret CHANGE date_retour_reelle date_retour_reelle DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adherent DROP roles, CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code_commune code_commune VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE livre CHANGE prix prix DOUBLE PRECISION DEFAULT \'NULL\', CHANGE annee annee INT DEFAULT NULL, CHANGE langue langue VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pret CHANGE date_retour_reelle date_retour_reelle DATETIME DEFAULT \'NULL\'');
    }
}
