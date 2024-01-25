<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125192027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       
        $this->addSql('
            INSERT INTO `user` (`uuid`, `roles`, `password`)
            VALUES
                (\'190Z841908Z4109\', \'["ROLE_ADMIN"]\', \'$2y$13$Rydlhj9S/1ltnbvhTTfOT.xFYfuy06Rjrgt/mtR0kaKHkxvgH8GbW \');
            '
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
