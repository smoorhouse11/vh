<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111175534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'base';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_tags (question_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_315279C91E27F6BF (question_id), INDEX IDX_315279C9BAD26311 (tag_id), PRIMARY KEY(question_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_answers (id INT AUTO_INCREMENT NOT NULL, answer VARCHAR(255) NOT NULL, rank INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_tags ADD CONSTRAINT FK_315279C91E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE question_tags ADD CONSTRAINT FK_315279C9BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_tags DROP FOREIGN KEY FK_315279C91E27F6BF');
        $this->addSql('ALTER TABLE question_tags DROP FOREIGN KEY FK_315279C9BAD26311');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE question_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE question_answers');
    }
}
