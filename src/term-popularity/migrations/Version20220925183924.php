<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220925183924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE providers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE providers_terms (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, term_id INT NOT NULL, score DOUBLE PRECISION NOT NULL, INDEX IDX_5DB66395A53A8AA (provider_id), INDEX IDX_5DB66395E2C35FC (term_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terms (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE providers_terms ADD CONSTRAINT FK_5DB66395A53A8AA FOREIGN KEY (provider_id) REFERENCES providers (id)');
        $this->addSql('ALTER TABLE providers_terms ADD CONSTRAINT FK_5DB66395E2C35FC FOREIGN KEY (term_id) REFERENCES terms (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE providers_terms DROP FOREIGN KEY FK_5DB66395A53A8AA');
        $this->addSql('ALTER TABLE providers_terms DROP FOREIGN KEY FK_5DB66395E2C35FC');
        $this->addSql('DROP TABLE providers');
        $this->addSql('DROP TABLE providers_terms');
        $this->addSql('DROP TABLE terms');
    }
}
