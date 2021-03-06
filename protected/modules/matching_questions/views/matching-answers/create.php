<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\matching_questions\models\MatchingAnswers */

$this->title = Yii::t('MatchingModule.base', 'Create Matching Answers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('MatchingModule.base', 'Matching Answers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matching-answers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
