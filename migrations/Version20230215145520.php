<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215145520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, biography VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, avatar_key VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CAC89EACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE followers (id INT AUTO_INCREMENT NOT NULL, follower_id INT DEFAULT NULL, followed_user_id INT DEFAULT NULL, INDEX IDX_8408FDA7AC24F853 (follower_id), INDEX IDX_8408FDA7AF2612FD (followed_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, image_key VARCHAR(255) NOT NULL, INDEX IDX_49CA4E7D9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', message VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, image_key VARCHAR(255) DEFAULT NULL, INDEX IDX_1DD399509B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, password_hash VARCHAR(255) DEFAULT NULL, confirm_token VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, reset_token_token VARCHAR(255) DEFAULT NULL, reset_token_expires DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7AC24F853 FOREIGN KEY (follower_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA7AF2612FD FOREIGN KEY (followed_user_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399509B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EACA76ED395');
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7AC24F853');
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA7AF2612FD');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D9B6B5FBA');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399509B6B5FBA');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE followers');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
