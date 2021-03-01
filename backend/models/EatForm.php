<?php

namespace app\models;

use yii\base\Model;

/**
 * Class EatForm
 * @package app\models
 */
class EatForm extends Model
{
    public $eaten;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eaten'], 'required'],
            [['eaten'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return ['eaten' => 'Съесть'];
    }

    /**
     * @return string[]
     */
    public static function getEatList()
    {
        return [
            '0' => '0%',
            '10' => '10%',
            '20' => '20%',
            '30' => '30%',
            '40' => '40%',
            '50' => '50%',
            '60' => '60%',
            '70' => '70%',
            '80' => '80%',
            '90' => '90%',
            '100' => '100%',
        ];

    }
}
