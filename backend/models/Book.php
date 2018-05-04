<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property int $author_id
 * @property string $name
 * @property int $taken_by
 * @property string $issued_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Author $author
 * @property User $takenBy
 * @property BookIssueLog[] $bookIssueLogs
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'name'], 'required'],
            [['author_id', 'taken_by'], 'integer'],
            [['issued_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name', 'author_id'], 'unique', 'targetAttribute' => ['name', 'author_id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['taken_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['taken_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'name' => 'Name',
            'taken_by' => 'Taken By',
            'issued_at' => 'Issued At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTakenBy()
    {
        return $this->hasOne(User::className(), ['id' => 'taken_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookIssueLogs()
    {
        return $this->hasMany(BookIssueLog::className(), ['book_id' => 'id']);
    }
}
