<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319155701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, nom_article VARCHAR(255) NOT NULL, prix NUMERIC(7, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_type_article (article_id INT NOT NULL, type_article_id INT NOT NULL, INDEX IDX_F95716027294869C (article_id), INDEX IDX_F95716026F9750B9 (type_article_id), PRIMARY KEY(article_id, type_article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_prevu (id INT AUTO_INCREMENT NOT NULL, liste_course_id INT DEFAULT NULL, article_id INT NOT NULL, quantite INT NOT NULL, statut_achat TINYINT(1) NOT NULL, INDEX IDX_9E675FDC4680FCB (liste_course_id), INDEX IDX_9E675FDC7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_course (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom_liste VARCHAR(255) NOT NULL, INDEX IDX_27EF1A82A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_article (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_PSEUDO (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_type_article ADD CONSTRAINT FK_F95716027294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_type_article ADD CONSTRAINT FK_F95716026F9750B9 FOREIGN KEY (type_article_id) REFERENCES type_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_prevu ADD CONSTRAINT FK_9E675FDC4680FCB FOREIGN KEY (liste_course_id) REFERENCES liste_course (id)');
        $this->addSql('ALTER TABLE article_prevu ADD CONSTRAINT FK_9E675FDC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE liste_course ADD CONSTRAINT FK_27EF1A82A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql("INSERT INTO `user` (`pseudo`, `roles`, `password`) VALUES ('Minh', '[\"ROLE_ADMIN\"]', '$2y$13$7Ln4a44DMOBEgi8JP/2a..dcDX9/z3AZYSc1wViWG7kVK9TLX6EMG');");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_type_article DROP FOREIGN KEY FK_F95716027294869C');
        $this->addSql('ALTER TABLE article_type_article DROP FOREIGN KEY FK_F95716026F9750B9');
        $this->addSql('ALTER TABLE article_prevu DROP FOREIGN KEY FK_9E675FDC4680FCB');
        $this->addSql('ALTER TABLE article_prevu DROP FOREIGN KEY FK_9E675FDC7294869C');
        $this->addSql('ALTER TABLE liste_course DROP FOREIGN KEY FK_27EF1A82A76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_type_article');
        $this->addSql('DROP TABLE article_prevu');
        $this->addSql('DROP TABLE liste_course');
        $this->addSql('DROP TABLE type_article');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
