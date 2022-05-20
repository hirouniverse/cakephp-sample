<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class DBInitialization extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        /**
         * CREATE TABLE users (
         *   id INT AUTO_INCREMENT PRIMARY KEY,
         *   email VARCHAR(255) NOT NULL,
         *   password VARCHAR(255) NOT NULL,
         *   created DATETIME,
         *   modified DATETIME
         * );
         */
        $table = $this->table('users', [ 'id' => 'id' ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();


        /**
         * CREATE TABLE articles (
         *   id INT AUTO_INCREMENT PRIMARY KEY,
         *   user_id INT NOT NULL,
         *   title VARCHAR(255) NOT NULL,
         *   slug VARCHAR(191) NOT NULL,
         *   body TEXT,
         *   published BOOLEAN DEFAULT FALSE,
         *   created DATETIME,
         *   modified DATETIME,
         *   UNIQUE KEY (slug),
         *   FOREIGN KEY user_key (user_id) REFERENCES users(id)
         * ) CHARSET=utf8mb4;
         */
        $articlesTable = $this->table('articles', [ 'id' => 'id' ]);
        $articlesTable->addColumn('user_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ]);
        $articlesTable->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $articlesTable->addColumn('slug', 'string', [
            'default' => null,
            'limit' => 191,
            'null' => false,
        ]);
        $articlesTable->addColumn('body', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $articlesTable->addColumn('published', 'boolean', [
            'default' => false,
        ]);
        $articlesTable->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $articlesTable->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $articlesTable->addIndex([
            'slug',
            ], [
            'name' => 'UNIQUE_SLUG',
            'unique' => true,
        ]);
        $articlesTable->addForeignKey('user_id', 'users', 'id', [
            'update'=> 'NO_ACTION'
        ]);
        $articlesTable->create();


        /**
         * CREATE TABLE tags (
         *   id INT AUTO_INCREMENT PRIMARY KEY,
         *   title VARCHAR(191),
         *   created DATETIME,
         *   modified DATETIME,
         *   UNIQUE KEY (title)
         * ) CHARSET=utf8mb4;
         */
        $tagsTable = $this->table('tags', [ 'id' => 'id' ]);
        $tagsTable->addColumn('title', 'string', [
            'default' => null,
            'limit' => 191,
        ]);
        $tagsTable->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $tagsTable->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $tagsTable->addIndex([
            'title',
            ], [
            'name' => 'UNIQUE_TITLE',
            'unique' => true,
        ]);
        $tagsTable->create();
    }
}
