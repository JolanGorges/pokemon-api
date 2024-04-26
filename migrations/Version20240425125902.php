<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425125902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ability (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, damage INT NOT NULL, pp INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, child_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A50FF7122C423CC4 (child_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species_type (species_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_846C25FB2A1D860 (species_id), INDEX IDX_846C25FC54C8C93 (type_id), PRIMARY KEY(species_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species_ability (species_id INT NOT NULL, ability_id INT NOT NULL, INDEX IDX_97519F73B2A1D860 (species_id), INDEX IDX_97519F738016D8B2 (ability_id), PRIMARY KEY(species_id, ability_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, abilities_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8CDE57291E1F6EAC (abilities_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF7122C423CC4 FOREIGN KEY (child_id_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE species_type ADD CONSTRAINT FK_846C25FB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE species_type ADD CONSTRAINT FK_846C25FC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE species_ability ADD CONSTRAINT FK_97519F73B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE species_ability ADD CONSTRAINT FK_97519F738016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE57291E1F6EAC FOREIGN KEY (abilities_id) REFERENCES ability (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE species DROP FOREIGN KEY FK_A50FF7122C423CC4');
        $this->addSql('ALTER TABLE species_type DROP FOREIGN KEY FK_846C25FB2A1D860');
        $this->addSql('ALTER TABLE species_type DROP FOREIGN KEY FK_846C25FC54C8C93');
        $this->addSql('ALTER TABLE species_ability DROP FOREIGN KEY FK_97519F73B2A1D860');
        $this->addSql('ALTER TABLE species_ability DROP FOREIGN KEY FK_97519F738016D8B2');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE57291E1F6EAC');
        $this->addSql('DROP TABLE ability');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE species_type');
        $this->addSql('DROP TABLE species_ability');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
