<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\matching_questions\models\SuperheroIdentityTranslations */

$this->title = Yii::t('MatchingModule.base', 'Update {modelClass}: ', [
    'modelClass' => 'Superhero Identity Translations',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('MatchingModule.base', 'Superhero Identity Translations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('MatchingModule.base', 'Update');
?>
<div class="superhero-identity-translations-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
