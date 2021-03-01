<?php

namespace app\models;

use app\exceptions\AppleException;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use DateTime;
use DateInterval;

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

    const MAX_CREATED_APPLE = 5;
    const MAX_TIME_APPEARANCE = 5 * 24 * 60 * 60;
    const TIME_ROTTEN = 5 * 60 * 60;

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
            'eaten' => 'Осталось',
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
        for ($i = 0; $i < $count; $i++) {
            $model = new Apple([
                'color' => mt_rand(0, 3),
                'status' => Apple::STATUS_ON_TREE,
                'eaten' => 100,
                'dateAppearance' => new Expression('FROM_UNIXTIME(UNIX_TIMESTAMP()-FLOOR(RAND()*' . Apple::MAX_TIME_APPEARANCE . '))'),
                'dateFail' => null,
            ]);
            $model->save();
        }

        return $count;
    }

    /**
     * @return bool
     */
    public function fail()
    {
        if ($this->status != Apple::STATUS_ON_TREE) {
            throw new AppleException('Упасть может только яблоко висящее на дереве');
        }
        $this->status = Apple::STATUS_ON_GROUND;
        $this->dateFail = new Expression('NOW()');
        return $this->save();
    }

    /**
     * @return bool
     */
    public function eat($eaten)
    {
        if ($this->status == Apple::STATUS_ON_TREE) {
            throw new AppleException('Нельзя съесть яблоко висящее на дереве');
        }
        if ($this->status == Apple::STATUS_ROTTEN) {
            throw new AppleException('Нельзя съесть гнилое яблоко');
        }
        if ($this->eaten - $eaten >= 0) {
            $this->eaten -= $eaten;
            return $this->save();
        } else {
            throw new AppleException('Оставшегося яблока недостаточно, чтобы столько съесть');
        }
    }

    /**
     * Для удобства отладки и проверки
     */
    public function addHourFail()
    {
        if ($this->status != Apple::STATUS_ON_GROUND) {
            throw new AppleException('Увеличить время можно только для несгнившего яблока лежащего на земле');
        }
        $this->dateFail = DateTime::createFromFormat('Y-m-d H:i:s', $this->dateFail)
            ->sub(new DateInterval('PT1H'))
            ->format('Y-m-d H:i:s');
        $this->save();
    }

    /**
     * Перевод яблок лежащих на земле в гнилые
     */
    public static function updateStatusApplies()
    {
        Apple::updateAll(['status' => Apple::STATUS_ROTTEN], [
            'AND',
            ['status' => Apple::STATUS_ON_GROUND],
            ['>', new Expression('UNIX_TIMESTAMP()-UNIX_TIMESTAMP(dateFail)'), Apple::TIME_ROTTEN],
        ]);
    }
}
