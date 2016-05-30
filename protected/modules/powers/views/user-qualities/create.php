<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\powers\models\UserQualities */

$this->title = Yii::t('app', 'Create User Qualities');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Qualities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-qualities-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
