<?php

use yii\helpers\Html;

$this->pageTitle = Yii::t('MissionsModule.page_titles', 'Space Members');

?>

<div class="panel panel-default members" id="space-members-panel">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.widgets_views_spaceMembers', '<strong>Space</strong> members'); ?>
    </div>

    <div class="panel-body fix">
        <?php foreach ($members as $membership) : ?>
            <?php $user = $membership->user; ?>
            <?php if($membership->status === \humhub\modules\space\models\Membership::STATUS_MEMBER) : ?>
                <a href="<?php echo $user->getUrl(); ?>">
                    <img src="<?php echo $user->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                         height="72" width="72" alt="72x72" data-src="holder.js/72x72"
                         style="width: 72px; height: 72px;" data-toggle="tooltip" data-placement="top" title=""
                         data-original-title="<?php echo Html::encode($user->displayName); ?>">
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>