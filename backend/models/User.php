<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $fio
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Book[] $books
 * @property BookIssueLog[] $bookIssueLogs
 */
class User extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['fio', 'phone'], 'string', 'max' => 255],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['taken_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookIssueLogs()
    {
        return $this->hasMany(BookIssueLog::className(), ['user_id' => 'id']);
    }
}
