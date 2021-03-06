<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->registerJsFile('@web/resources/space/colorpicker/js/bootstrap-colorpicker-modified.js', ['position' => \humhub\components\View::POS_BEGIN]);
$this->registerCssFile('@web/resources/space/colorpicker/css/bootstrap-colorpicker.min.css');
?>
<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel">
                <?php if($model->is_team == 1): ?>
                    <?php echo Yii::t('TeamsModule.views_create_create', '<strong>Create</strong> new team'); ?>
                <?php else: ?>
                    <?php echo Yii::t('SpaceModule.views_create_create', '<strong>Create</strong> new space'); ?>
                <?php endif; ?>
            </h4>
        </div>
        <div class="modal-body">

            <hr>
            <br>
            <div class="row">
                <div class="col-xs-8"> 
                    <?php if($model->is_team == 1): ?>
                        <?php echo $form->field($model, 'name')->textInput(['id' => 'space-name', 'placeholder' => Yii::t('TeamsModule.views_create_create', 'team name'), 'maxlength' => 45]); ?>
                    <?php else: ?>
                        <?php echo $form->field($model, 'name')->textInput(['id' => 'space-name', 'placeholder' => Yii::t('SpaceModule.views_create_create', 'space name'), 'maxlength' => 45]); ?>
                    <?php endif; ?>
                </div>
                <div class="col-xs-4">            
                    <strong><?php echo Yii::t('SpaceModule.views_create_create', 'Color'); ?>
                </strong>

                    <div class="input-group space-color-chooser" style="margin-top: 5px;">

                        <?= Html::activeTextInput($model, 'color', ['class' => 'form-control', 'id' => 'space-color-picker', 'value' => '#000000']); ?>
                        <span class="input-group-addon"><i></i></span>
                    </div>
                    <br></div>
            </div>

            <?php if($model->is_team == 1): ?>
                <?php echo $form->field($model, 'description')->textarea(['placeholder' => Yii::t('TeamsModule.views_create_create', 'team description'), 'rows' => '3']); ?>
            <?php else: ?>
                <?php echo $form->field($model, 'description')->textarea(['placeholder' => Yii::t('SpaceModule.views_create_create', 'space description'), 'rows' => '3']); ?>
            <?php endif; ?>

            <?php if($createTeamsPermission): ?>
                <?php if($model->is_team == 1 || $model->is_team == 0): ?>
                    <?php echo Html::activeHiddenInput($model, 'is_team'); ?>
                <?php else: ?>
                    <?php //echo $form->field($model, 'is_team')->checkBox(); ?>
                    <?php 
                        $model->is_team = 0;
                        echo Html::activeHiddenInput($model, 'is_team'); 
                    ?>
                <?php endif; ?>
            <?php else: ?>
                
                <?php //echo $form->field($model, 'is_team')->checkBox(['disabled' => true]); ?>
                <?php //echo Html::label(Yii::t('TeamsModule.views_create_create', "You're already a member of a Team.<BR>Leave it to create a new one.")); ?>
                
            <?php endif; ?>

            <a data-toggle="collapse" id="access-settings-link" href="#collapse-access-settings"
               style="font-size: 11px;"><i
                    class="fa fa-caret-right"></i> <?php echo Yii::t('SpaceModule.views_create_create', 'Advanced access settings'); ?>
            </a>

            <div id="collapse-access-settings" class="panel-collapse collapse">
                <br/>
                <div class="row">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'join_policy')->radioList($joinPolicyOptions); ?>
                    </div>
                    <div class="col-xs-6">
                        <?= $form->field($model, 'visibility')->radioList($visibilityOptions); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <hr>
            <br>
            <?php
                if($model->is_team == 1){
                    $urlAction = 'create_team';
                }else{
                    $urlAction = 'create';
                }

            echo \humhub\widgets\AjaxButton::widget([
                'label' => Yii::t('SpaceModule.views_create_create', 'Next'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    'success' => new yii\web\JsExpression('function(html){ $("#globalModal").html(html); }'),
                    'url' => Url::to(['/teams/create/'.$urlAction]),
                ],
                'htmlOptions' => [
                    'class' => 'btn btn-primary',
                    'id' => 'space-create-submit-button',
                ]
            ]);
            ?>

            <?php echo \humhub\widgets\LoaderWidget::widget(['id' => 'create-loader', 'cssClass' => 'loader-modal hidden']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>


<script type="text/javascript">

    // Replace the standard checkbox and radio buttons
    $('.modal-dialog').find(':checkbox, :radio').flatelements();

    // show Tooltips on elements inside the views, which have the class 'tt'
    $('.tt').tooltip({html: false});

    // Shake modal after wrong validation
<?php if ($model->hasErrors()) { ?>
        $('.modal-dialog').removeClass('fadeIn');
        $('.modal-dialog').addClass('shake');
<?php } ?>

    $('#collapse-access-settings').on('show.bs.collapse', function () {
        // change link arrow
        $('#access-settings-link i').removeClass('fa-caret-right');
        $('#access-settings-link i').addClass('fa-caret-down');
    })

    $('#collapse-access-settings').on('hide.bs.collapse', function () {
        // change link arrow
        $('#access-settings-link i').removeClass('fa-caret-down');
        $('#access-settings-link i').addClass('fa-caret-right');
    })

    // prevent enter key and simulate ajax button submit click
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                $('#space-create-submit-button').click();
                //return false;
            }
        });

        $('#space-name').focus();

        $('.space-color-chooser').colorpicker({
            format: 'hex',
            color: '#6fdbe8',
            horizontal: false,
            component: '.input-group-addon',
            input: '#space-color-picker',
        });
    });

</script>