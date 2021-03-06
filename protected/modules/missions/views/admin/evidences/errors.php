<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

$this->title = Yii::t('MissionsModule.base', 'Evidences');
$this->params['breadcrumbs'][] = $this->title;

echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3><?php echo $this->title; ?></h3>
    </div>

    <div class="panel-body">
        
        <?php if (count($evidences) != 0): ?>
        
            <table class="table">
                <tr>
                    <th><?php echo Yii::t('MissionsModule.base', 'Author'); ?></th>
                    <th><?php echo Yii::t('MissionsModule.base', 'Title'); ?></th>
                    <th><?php echo Yii::t('MissionsModule.base', 'Text'); ?></th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($evidences as $evidence): ?>
                    <tr>
                        <td><?php echo $evidence->getAuthor()->username; ?></td>
                        <td><?php echo $evidence->title; ?></td>
                        <td><?php echo $evidence->text; ?></td>
                        <td>
                            <?php echo Html::a(
                                Yii::t('MissionsModule.base', 'Delete'),
                                ['delete-evidences', 'id' => $evidence->id], array(
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('MissionsModule.base', 'Are you sure you want to delete this evidence?'),
                                    'method' => 'post',
                                ],
                                )); ?> 
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

        <?php else: ?>

            <p><?php echo Yii::t('MissionsModule.base', 'No evidences created yet!'); ?></p>


        <?php endif; ?>

    </div>
</div>


