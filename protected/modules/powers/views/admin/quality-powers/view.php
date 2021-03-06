<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\powers\models\QualityPowers */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('PowersModule.base', 'Quality Powers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quality-powers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('PowersModule.base', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('PowersModule.base', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('PowersModule.base', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'quality_id',
            'power_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
