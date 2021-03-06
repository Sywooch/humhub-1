<?php

use yii\helpers\Html;
use \yii\helpers\Url;
use app\modules\missions\models\Portfolio;
use humhub\modules\space\models\Setting;

echo Html::beginForm(); 

$user = Yii::$app->user->getIdentity();

$evokation_investment = Portfolio::find()
    ->where(['user_id' => Yii::$app->user->getIdentity()->id,'evokation_id' => $evokation->id])
    ->one();

$youtube_code = $evokation->youtube_url ? $evokation->getYouTubeCode($evokation->youtube_url) : null;

?>

<div class="user-content-box">
    <h4><?php print humhub\widgets\RichText::widget(['text' => $evokation->getTitle()]); ?></h4>
    <p><?php print humhub\widgets\RichText::widget(['text' => $evokation->description]);?></p>

    <br />

    <!-- YOUTUBE LINK -->
    <a target="_blank" href="<?=$evokation->youtube_url?>" style="font-size:12pt"><?= Yii::t('MissionsModule.base', 'Youtube video') ?></a>

    <!-- GDRIVE LINK -->
    <?php if($user->group->name === "Mentors"): ?>
        <br><br>
        <a target="_blank" href="<?=Setting::get($contentContainer->id, "gdrive_url")?>" style="font-size:12pt"><?= Yii::t('MissionsModule.base', 'Google Drive') ?></a>
    <?php endif; ?>  
    
    <div class="fuchsia-border"></div>
    
    <div class="evidence-mission-box" style="text-align:center">
        <?php if($deadline && $deadline->hasStarted()): ?>
            <!-- INVESTMENT -->
            <div class= "">
                <div class="">
                    <h5><?= Yii::t('MissionsModule.base', 'Investment') ?></h5>
                </div>
                    <div class="">
                    <p>
                        <b><?= Yii::t('MissionsModule.base', 'Total Investment:') ?></b> <?= $total_investment ?> <?= $total_investment > 1 ? 'evocoins' : 'evocoin' ?>
                    </p>
                    <p>
                        <b><?= Yii::t('MissionsModule.base', 'Median Investment:') ?></b>  <?= $median_investment ?> <?= $median_investment > 1 ? 'evocoins' : 'evocoin' ?>
                    </p>
                    
                </div>
            </div>
        <?php endif; ?>
    </div>

    <br /><br />

    <?php if($evokation->content->user_id): ?>
    <div>
        
        <!-- DISABLED
        <div style = "float:left">
            <a class = "btn btn-cta2" href='<?= Url::to(['/missions/evokations/view', 'id' => $evokation->id, 'sguid' => $contentContainer->guid]); ?>'>
                <?= Yii::t('MissionsModule.base', 'Read More') ?>
            </a>
        </div>
        -->

        <?php if ($deadline && $deadline->isOccurring() ): ?>
        <div style = "float:right">
            <?php if(!$evokation_investment): ?>
            <a id="evokation_vote_<?= $evokation->id ?>" class = "btn btn-cta1" onClick="addEvokationToPortfolio<?= $evokation->id ?>();">
                <?= Yii::t('MissionsModule.base', 'Add to Portfolio') ?>
            </a>
            <?php else: ?>
                <a id="evokation_vote_<?= $evokation->id ?>" class = "btn btn-cta1" onClick="deleteEvokation(<?= $evokation->id ?>);">
                    <?= Yii::t('MissionsModule.base', 'Remove from Portfolio') ?>
                </a>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <div style = "float:right">
                <a class = "btn btn-default" href='#'>
                    <?= Yii::t('MissionsModule.base', "Voting's Closed") ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <br><br>
    <?php endif; ?>

    <div class="clearFloats"></div>

    <?php echo Html::endForm(); ?>

</div>  

<script type="text/javascript">

    function addEvokationToPortfolio<?= $evokation->id ?>(){

        do{
            var investment = parseInt(window.prompt("<?= Yii::t('MissionsModule.base', 'How many evocoins do you want to invest?') ?>",1), 10);
        }while( (!isNaN(investment) && (isNaN(parseInt(investment)) || investment < 1)));

        if(!isNaN(investment)){

            if(investment > availableAmount){
                showMessage("<?= Yii::t('MissionsModule.base', 'Error') ?>", "<?= Yii::t('MissionsModule.base', 'No enough Evocoins!') ?>");
                return;
            }

            $.ajax({
                url: '<?= Url::to(['/missions/portfolio/add']); ?>&evokation_id='+<?= $evokation->id ?>+"&investment="+investment,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    if(data.status == 'success'){
                        addEvokation(
                            <?= $evokation->id ?>, 
                            <?= json_encode($evokation->getTitle()) ?>, 
                            '<?= Url::to(['/missions/evokations/view', 'id' => $evokation->id, 'sguid' => $contentContainer->guid]); ?>', 
                            investment);
                        $('#portfolio_status').hide();
                        $('#evokation_vote_<?= $evokation->id ?>').html("<?= Yii::t('MissionsModule.base', 'Remove from Portfolio') ?>");
                        $('#evokation_vote_<?= $evokation->id ?>').attr("onclick", "deleteEvokation(<?= $evokation->id ?>);");
                        showMessage("<?= Yii::t('MissionsModule.base', 'Updated') ?>", "<?= Yii::t('MissionsModule.base', 'Evokation added!') ?>");
                    }else if(data.status == 'error'){
                        $('#portfolio_status').hide();
                        showMessage("<?= Yii::t('MissionsModule.base', 'Error') ?>", "<?= Yii::t('MissionsModule.base', 'Something went wrong') ?>");
                    }else if(data.status == 'error_limit'){
                        $('#portfolio_status').hide();
                        showMessage("<?= Yii::t('MissionsModule.base', 'Error') ?>", "<?= Yii::t('MissionsModule.base', 'You can not invest more than {investment_limit} evocoins total.', ['investment_limit' => intval(humhub\models\Setting::Get('investment_limit'))]) ?>");
                    }
                    loadPopUps();
                }
            });      
        }
    }
</script>
