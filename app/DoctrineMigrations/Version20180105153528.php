<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180105153528 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_50D0C606BCB134CE ON answers');
        $this->addSql('ALTER TABLE answers DROP questions_id');
        $this->addSql('ALTER TABLE users_quizzes CHANGE done_answers done_answers INT NOT NULL, CHANGE right_answers right_answers INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answers ADD questions_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_50D0C606BCB134CE ON answers (questions_id)');
        $this->addSql('ALTER TABLE users_quizzes CHANGE done_answers done_answers INT DEFAULT NULL, CHANGE right_answers right_answers INT DEFAULT NULL');
    }
}
