<?php
declare(strict_types=1);
date_default_timezone_set('UTC');

use Migrations\AbstractMigration;

class InsertSampleData extends AbstractMigration
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
        // INSERT INTO users (email, password, created, modified)
        // VALUES
        // ('cakephp@example.com', 'secret', NOW(), NOW());
        $usersTable = $this->table('users');
        $userData = [
            'email' => 'cakephp@example.com',
            'password' => 'secret',
            'created' => date("Y-m-d h:m:s"),
            'modified' => date("Y-m-d h:m:s"),
        ];
        $usersTable->insert($userData)->saveData();

        $userRow = $this->fetchRow('SELECT id FROM users');

        echo $userRow;

        // INSERT INTO articles (user_id, title, slug, body, published, created, modified)
        // VALUES
        // (1, 'First Post', 'first-post', 'This is the first post.', TRUE, NOW(), NOW());
        $articlesTable = $this->table('articles');
        $articleData = [
            'user_id' => $userRow[0],
            'title' => 'First Post',
            'slug' => 'first-post',
            'body' => 'This is the first post.',
            'published' => true,
            'created' => date("Y-m-d h:m:s"),
            'modified' => date("Y-m-d h:m:s"),
        ];
        $articlesTable->insert($articleData)->update();
    }
}
