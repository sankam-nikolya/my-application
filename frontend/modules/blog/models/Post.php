<?php


namespace frontend\modules\blog\models;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
        ];
    }
}