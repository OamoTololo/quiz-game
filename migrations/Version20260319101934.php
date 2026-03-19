<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319101934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, is_correct TINYINT NOT NULL, question_id INT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, quiz_id INT NOT NULL, INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_result ADD user_id INT NOT NULL, ADD quiz_id INT NOT NULL, DROP username, DROP finished_at, CHANGE total_questions total INT NOT NULL, CHANGE started_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE quiz_result ADD CONSTRAINT FK_FE2E314AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_result ADD CONSTRAINT FK_FE2E314A853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('CREATE INDEX IDX_FE2E314AA76ED395 ON quiz_result (user_id)');
        $this->addSql('CREATE INDEX IDX_FE2E314A853CD175 ON quiz_result (quiz_id)');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(180) NOT NULL, CHANGE surname surname VARCHAR(180) NOT NULL, CHANGE username username VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('ALTER TABLE quiz_result DROP FOREIGN KEY FK_FE2E314AA76ED395');
        $this->addSql('ALTER TABLE quiz_result DROP FOREIGN KEY FK_FE2E314A853CD175');
        $this->addSql('DROP INDEX IDX_FE2E314AA76ED395 ON quiz_result');
        $this->addSql('DROP INDEX IDX_FE2E314A853CD175 ON quiz_result');
        $this->addSql('ALTER TABLE quiz_result ADD total_questions INT NOT NULL, ADD username VARCHAR(255) NOT NULL, ADD finished_at DATETIME DEFAULT NULL, DROP total, DROP user_id, DROP quiz_id, CHANGE created_at started_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL');
    }
}
