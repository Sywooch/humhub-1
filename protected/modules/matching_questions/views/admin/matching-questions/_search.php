<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\matching_questions\models\MatchingQuestionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="matching-questions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'created') ?>

    <?= $form->field($model, 'modified') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('MatchingModule.base', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('MatchingModule.base', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
