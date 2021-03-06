<?php

// use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default portfolio">
    <div class="panel-heading">
        <strong>
            <?= Yii::t('MissionsModule.base', 'Create new team') ?>
        </strong>
    </div>
    <div class="panel-body">
        <div class="col-xs-12 text-center">
            <!-- content -->
            <!--English-->
            <img src="<?php echo Url::to('@web/themes/Evoke/img/alchemy.png') ?>" width = "120px" style = "border-radius: 50%; border: 3px solid #254054; margin-bottom:10px">
            <h6 class="uppercase" style = "font-weight:700"><?= Yii::t('MissionsModule.base', 'Team Captains') ?></h6>
            <p><?= Yii::t('MissionsModule.base', 'Your first mission is to register your team on the platform.') ?></p>
            
            <a href="<?= Url::to(['/teams/create/create_team']) ?>" data-target = "#globalModal" class="btn btn-primary">
                <?= Yii::t('MissionsModule.base', 'Create Team') ?>
            </a><br /><br />

            <h6 class="uppercase" style = "font-weight:700"><?= Yii::t('MissionsModule.base', 'Other Agents') ?></h6>
            <p>
                <?= Yii::t('MissionsModule.base', 'Await the invitation from your captain to join your team.') ?>
            </p>
        </div>
    </div>

</div>