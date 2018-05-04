<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m180504_075024_create_book_table extends Migration
{
    private $tableName = 'book';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema($this->tableName)) {
            $this->createTable($this->tableName, [
                'id'         => $this->primaryKey(),
                'author_id'  => $this->integer()->notNull(),
                'name'       => $this->string()->notNull(),
                'taken_by'   => $this->integer()->defaultValue(null),
                'issued_at'  => $this->timestamp()->defaultValue(null),
                'created_at' => $this->timestamp()->defaultValue(null),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ]);
        }


        $this->createIndex('idx_name_author_id', $this->tableName, ['name', 'author_id'], true);
        $this->addForeignKey(
            'fk_author_id',
            $this->tableName,
            'author_id',
            'author',
            'id',
            'CASCADE',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk_taken_by',
            $this->tableName,
            'taken_by',
            'user',
            'id',
            'SET NULL',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_author_id', $this->tableName);
        $this->dropIndex('idx_name_author_id', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
