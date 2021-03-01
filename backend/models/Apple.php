<?php

namespace app\models;

use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%apple}}".
 *
 * @property int $id
 * @property int $color
 * @property int $status
 * @property float $eaten
 * @property string $dateAppearance
 * @property string|null $dateFail
 */
class Apple extends \yii\db\ActiveRecord
{
    const COLOR_GREEN = 0;
    const COLOR_RED = 1;
    const COLOR_YELLOW = 2;
    const COLOR_WHITE = 3;

    const STATUS_ON_TREE = 0;
    const STATUS_ON_GROUND = 1;
    const STATUS_ROTTEN = 2;

    const MAX_CREATED_APPLE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color', 'status', 'eaten'], 'integer'],
            [['dateAppearance', 'dateFail'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Цвет',
            'status' => 'Статус',
            'eaten' => 'Съедено',
            'dateAppearance' => 'Дата появления',
            'dateFail' => 'Дата падения',
        ];
    }

    /**
     * @return string[]
     */
    public static function getColorList()
    {
        return [
            self::COLOR_GREEN => 'Зелёное',
            self::COLOR_RED => 'Красное',
            self::COLOR_YELLOW => 'Желтое',
            self::COLOR_WHITE => "Белое",
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getColor()
    {
        return ArrayHelper::getValue(self::getColorList(), $this->color);
    }

    /**
     * @return string[]
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ON_TREE => 'На дереве',
            self::STATUS_ON_GROUND => 'На земле',
            self::STATUS_ROTTEN => 'Сгнило',
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    /**
     * Создание случайного числа яблок
     *
     * @return int
     */
    public static function createApplies()
    {
        $count = mt_rand(1, Apple::MAX_CREATED_APPLE);
        $model = new Apple([
            'color' => mt_rand(0, 4),
            'status' => Apple::STATUS_ON_TREE,
            'eaten' => 0,
            'dateAppearance' => new Expression('NOW()'),
            'dateFail' => null,
        ]);
        $model->save();

        return $count;
    }

    /**
     * @return bool
     */
    public function fail()
    {
        $this->status = Apple::STATUS_ON_GROUND;
        $this->dateFail = new Expression('NOW()');
        return $this->save();
    }
}
