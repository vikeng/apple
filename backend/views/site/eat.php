<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\EatForm;

/* @var $this yii\web\View */
/* @var $model \app\models\EatForm */

$this->title = 'Cъесть яблоко';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'eaten')->dropDownList(EatForm::getEatList()) ?>

    <div class="form-group">
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Съесть', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
