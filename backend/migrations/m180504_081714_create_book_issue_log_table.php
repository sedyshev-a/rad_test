<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book_issue_log`.
 */
class m180504_081714_create_book_issue_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book_issue_log', [
            'id' => $this->primaryKey(),
            'date' => $this->timestamp(),
            'type' => $this->string(),
            'user_id' => $this->integer(),
            'book_id' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk_book_id',
            'book_issue_log',
            'book_id',
            'book',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk_user_id',
            'book_issue_log',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('book_issue_log');
    }
}
