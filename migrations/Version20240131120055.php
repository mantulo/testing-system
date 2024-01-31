<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131120055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, question_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, test_id UUID DEFAULT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494E1E5D0459 ON question (test_id)');
        $this->addSql('COMMENT ON COLUMN question.test_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE test (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN test.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN test.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_answer (id INT NOT NULL, test_id UUID DEFAULT NULL, question_id INT DEFAULT NULL, answer_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF8F51181E5D0459 ON user_answer (test_id)');
        $this->addSql('CREATE INDEX IDX_BF8F51181E27F6BF ON user_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_BF8F5118AA334807 ON user_answer (answer_id)');
        $this->addSql('CREATE UNIQUE INDEX test_question_answer ON user_answer (test_id, question_id, answer_id)');
        $this->addSql('COMMENT ON COLUMN user_answer.test_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_test (id UUID NOT NULL, test_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, finished BOOLEAN NOT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, user_first_name VARCHAR(25) NOT NULL, user_last_name VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A2FE32C51E5D0459 ON user_test (test_id)');
        $this->addSql('COMMENT ON COLUMN user_test.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_test.test_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_test.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_test.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E1E5D0459 FOREIGN KEY (test_id) REFERENCES test (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F51181E5D0459 FOREIGN KEY (test_id) REFERENCES user_test (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F51181E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F5118AA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_test ADD CONSTRAINT FK_A2FE32C51E5D0459 FOREIGN KEY (test_id) REFERENCES test (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_answer_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E1E5D0459');
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT FK_BF8F51181E5D0459');
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT FK_BF8F51181E27F6BF');
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT FK_BF8F5118AA334807');
        $this->addSql('ALTER TABLE user_test DROP CONSTRAINT FK_A2FE32C51E5D0459');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE user_answer');
        $this->addSql('DROP TABLE user_test');
    }
}
