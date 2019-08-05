<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "twitter".
 *
 * @property int $id
 * @property  string secret
 * @property string $user

 */
class Twitter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'twitter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user'], 'string', 'max' => 255],
            [['secret'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'secret' => 'Secret',
        ];
    }
}
