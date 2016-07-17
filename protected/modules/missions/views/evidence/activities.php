<?php

use yii\helpers\Html;
use humhub\modules\missions\models\Missions;
use yii\widgets\Breadcrumbs;

$this->title = $mission->title; //Yii::t('MissionsModule.base', 'Activities');
$this->params['breadcrumbs'][] = ['label' => Yii::t('MissionsModule.base', 'Missions'), 'url' => ['missions', 'sguid' => $contentContainer->guid]];
// $this->params['breadcrumbs'][] = $mission->title;
$this->params['breadcrumbs'][] = Yii::t('MissionsModule.base', 'Mission: {alias}', array('{alias}' => $this->title)); //Yii::t('MissionsModule.base', 'Mission:').' '.$this->title;

echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);

$mission_title = isset($mission->missionTranslations[0]) ? $mission->missionTranslations[0]->title : $mission->title;

$this->pageTitle = Yii::t('MissionsModule.base', 'Mission {mission}: Activities', array('mission' => $mission_title));

?>
<div class="panel panel-default">
    <div class="panel-heading">
        
        <h4 class = "bold"><?php echo Yii::t('MissionsModule.base', 'Mission:'); ?>&nbsp;<?= $mission_title ?></h4>
        
        <p class="description">
            <?= isset($mission->missionTranslations[0]) ? $mission->missionTranslations[0]->description : $mission->description ?>
        </p>
        
    </div>
    <div class="panel-body">

        <?php 
            $x = 0;
            if (count($mission->activities) != 0): ?>
            
            <?php foreach ($mission->activities as $activity): ?>
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= isset($activity->activityTranslations[0]) ? $activity->activityTranslations[0]->title : $activity->title ?>
                    </div>
                    <div class="panel-body">
                        
                        <p class="description">
                            <?= isset($activity->activityTranslations[0]) ? $activity->activityTranslations[0]->description : $activity->description ?>
                        </p>
                        
                        <br>
                        <?php echo Html::a(
                                Yii::t('MissionsModule.base', 'Enter Activity'), 
                                ['show', 'activityId' => $activity->id, 'sguid' => $contentContainer->guid], array('class' => 'btn btn-cta2')); ?>
                    </div>
                </div>
            
            <?php endforeach; ?>
            
        <?php else: ?>
            <p><?php echo Yii::t('MissionsModule.base', 'No activities created yet!'); ?></p>
        <?php endif; ?>
    </div>
</div>

<style type="text/css">

.description{
    text-align: justify;
}

</style>
