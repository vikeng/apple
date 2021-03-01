<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Apple;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AppleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жизнь яблок';
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать яблоки', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php $form = ActiveForm::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'color',
                'value' => function (Apple $model) {
                    return $model->getColor();
                }
            ],
            [
                'attribute' => 'status',
                'value' => function (Apple $model) {
                    return $model->getStatus();
                }
            ],
            [
                'attribute' => 'eaten',
                'value' => function (Apple $model) {
                    return $model->eaten . '%';
                }
            ],

            'dateAppearance',
            'dateFail',

            [
                'value' => function (Apple $model) {
                    if ($model->status == Apple::STATUS_ON_TREE) {
                        return Html::a('Упасть', ['fail', 'id' => $model->id], ['class' => 'btn btn-success']);
                    }
                    if ($model->status == Apple::STATUS_ON_GROUND) {
                        return '';
                    }
                    return '';
                },
                'format' => 'html',
            ]
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>
</div>
