<?php
$user = $this->context->getUser();
?>
<div class="container profile-layout-container">
    <div class="row">
        <div class="col-xs-12">
            <?php 

            if($user->group->name != "Mentors"){
                echo \humhub\modules\stats\widgets\CustomProfileHeader::widget(['user' => $user]); 
            }else{
                echo \humhub\modules\user\widgets\ProfileHeader::widget(['user' => $user]);
            }
             
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2 layout-nav-container">
            <?= \humhub\modules\user\widgets\ProfileMenu::widget(['user' => $this->context->user]); ?>
        </div>

        <?php if (isset($this->context->hideSidebar) && $this->context->hideSidebar) : ?>
            <div class="col-xs-10 layout-content-container">
                <?php echo $content; ?>
            </div>
        <?php else: ?>
            <div class="col-xs-7 layout-content-container">
                <?php echo $content; ?>
            </div>
            <div class="col-xs-3 layout-sidebar-container">
                <?php
                echo \humhub\modules\user\widgets\ProfileSidebar::widget([
                    'user' => $this->context->user,
                    'widgets' => [
                        [\humhub\modules\user\widgets\UserTags::className(), ['user' => $this->context->user], ['sortOrder' => 10]],
                        [\humhub\modules\user\widgets\UserSpaces::className(), ['user' => $this->context->user], ['sortOrder' => 20]],
                        [\humhub\modules\user\widgets\UserFollower::className(), ['user' => $this->context->user], ['sortOrder' => 30]],
                    ]
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
