<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use humhub\compat\CActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\missions\models\EvokationCategories */

$this->title = Yii::t('MissionsModule.base', 'Update {modelClass}: ', [
    'modelClass' => 'Evokation Categories',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('MissionsModule.base', 'Evokation Categories'), 'url' => ['index-categories']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('MissionsModule.base', 'Update');
?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><?php echo $this->title; ?></strong></div>
    <div class="panel-body">
        
        <div class="evokation-categories-update">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>

    </div>
</div>