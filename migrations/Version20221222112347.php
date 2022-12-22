<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221222112347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_ingredients (recipe_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_9F925F2B59D8A214 (recipe_id), INDEX IDX_9F925F2B3EC4DCE (ingredients_id), PRIMARY KEY(recipe_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredients ADD CONSTRAINT FK_9F925F2B3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE recipe ADD time INT DEFAULT NULL, ADD nb_people INT DEFAULT NULL, ADD difficulty INT DEFAULT NULL, ADD is_favorite TINYINT(1) NOT NULL, ADD create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD ingredients VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(50) NOT NULL, CHANGE description description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B59D8A214');
        $this->addSql('ALTER TABLE recipe_ingredients DROP FOREIGN KEY FK_9F925F2B3EC4DCE');
        $this->addSql('DROP TABLE recipe_ingredients');
        $this->addSql('ALTER TABLE ingredients CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE recipe DROP time, DROP nb_people, DROP difficulty, DROP is_favorite, DROP create_at, DROP update_at, DROP ingredients, CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description TEXT NOT NULL');
    }
}
