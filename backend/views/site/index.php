<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Apple;

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
                },
                'filter' => Apple::getColorList(),
            ],
            [
                'attribute' => 'status',
                'value' => function (Apple $model) {
                    return $model->getStatus();
                },
                'filter' => Apple::getStatusList(),
            ],
            [
                'attribute' => 'eaten',
                'value' => function (Apple $model) {
                    return $model->eaten . '%';
                },
                'filter' => \app\models\EatForm::getEatList(),
            ],

            'dateAppearance',
            'dateFail',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fail} {eat} {add-hour}',
                'buttons' => [
                    'fail' => function ($url) {
                        return Html::a('Упасть', $url, ['class' => 'btn btn-primary']);
                    },
                    'eat' => function ($url) {
                        return Html::a('Съесть', $url, ['class' => 'btn btn-warning']);
                    },
                    'add-hour' => function ($url) {
                        return Html::a('+1 час', $url, ['class' => 'btn btn-info']);
                    },
                ],
                'visibleButtons' => [
                    'fail' => function (Apple $model) {
                        return $model->status == Apple::STATUS_ON_TREE;
                    },
                    'eat' => function (Apple $model) {
                        return $model->status == Apple::STATUS_ON_GROUND;
                    },
                    'add-hour' => function (Apple $model) {
                        return $model->status == Apple::STATUS_ON_GROUND;
                    },
                ]
            ],
        ],
    ]); ?>

</div>
