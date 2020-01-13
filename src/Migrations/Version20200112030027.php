<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200112030027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_answers ADD question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question_answers ADD CONSTRAINT FK_5E0C131B1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_5E0C131B1E27F6BF ON question_answers (question_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_answers DROP FOREIGN KEY FK_5E0C131B1E27F6BF');
        $this->addSql('DROP INDEX IDX_5E0C131B1E27F6BF ON question_answers');
        $this->addSql('ALTER TABLE question_answers DROP question_id');
    }
}
