<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107165537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, titltitle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_description (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_81A4CA094B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_link (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, link VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FFF9FEFA4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_description ADD CONSTRAINT FK_81A4CA094B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_link ADD CONSTRAINT FK_FFF9FEFA4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_description DROP FOREIGN KEY FK_81A4CA094B89032C');
        $this->addSql('ALTER TABLE post_link DROP FOREIGN KEY FK_FFF9FEFA4B89032C');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_description');
        $this->addSql('DROP TABLE post_link');
    }
}
