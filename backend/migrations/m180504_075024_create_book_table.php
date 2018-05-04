<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m180504_075024_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book', [
            'id'         => $this->primaryKey(),
            'author_id'  => $this->integer()->notNull(),
            'name'       => $this->string()->notNull(),
            'taken_by'   => $this->integer()->defaultValue(null),
            'issued_at'  => $this->timestamp()->defaultValue(null),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $this->createIndex('idx_name_author_id', 'book', ['name', 'author_id'], true);
        $this->addForeignKey(
            'fk_author_id',
            'book',
            'author_id',
            'author',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk_taken_by',
            'book',
            'taken_by',
            'user',
            'id',
            'SET DEFAULT',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_author_id', 'book');
        $this->dropIndex('idx_name_author_id', 'book');
        $this->dropTable('book');
    }
}
