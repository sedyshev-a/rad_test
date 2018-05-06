<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $author_name
 * @property string $name
 * @property int $taken_by
 * @property string $issued_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $takenBy
 * @property BookIssueLog[] $bookIssueLogs
 */
class Book extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => date('Y-m-d H:i:s'),
            ]
        ];
    }

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
            [['author_name', 'name'], 'required'],
            [['taken_by'], 'integer'],
            [['issued_at', 'created_at', 'updated_at'], 'safe'],
            [['name', 'author_name'], 'string', 'max' => 255],
            [['name', 'author_name'], 'unique', 'targetAttribute' => ['name', 'author_name']],
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
            'author_name' => 'Author name',
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
