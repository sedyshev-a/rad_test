<?php

namespace app\models;

/**
 * This is the model class for table "book_issue_log".
 *
 * @property int $id
 * @property string $date
 * @property string $type
 * @property int $user_id
 * @property int $book_id
 *
 * @property Book $book
 * @property User $user
 */
class BookIssueLog extends \yii\db\ActiveRecord
{
    const TYPE_ISSUE = 'issue';
    const TYPE_RETURN = 'return';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book_issue_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['user_id', 'book_id'], 'integer'],
            [['type'], 'in', 'range' => [self::TYPE_ISSUE, self::TYPE_RETURN]],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'type' => 'Type',
            'user_id' => 'User ID',
            'book_id' => 'Book ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
