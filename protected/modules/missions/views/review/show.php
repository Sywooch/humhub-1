<?php

use yii\helpers\Html;
$activity = null;

if($evidence){
    $activity = $evidence->getActivities();
}

$this->pageTitle = Yii::t('MissionsModule.event', 'Review Evidence');

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 style="margin-top:10px"><?php echo Yii::t('MissionsModule.base', 'Review Evidence'); ?></h4>
        <?php if($activity): ?>
        <?php endif; ?>
    </div>
<?php if($activity): ?>
    <div class="panel-body">

        <div class="formatted" style = "margin-bottom:40px">

            <h5>
                <?= Yii::t('MissionsModule.base', 'Mission {mission}: Activity {activity}', array('mission' => $activity->mission->position, 'activity' => $activity->position)) ?>
            </h5>

            <p><?php echo nl2br(isset($activity->activityTranslations[0]) ? $activity->activityTranslations[0]->description : $activity->description) ?></p>
        </div>

        <div class="grey-box evidence_area">
            <h4><?php print humhub\widgets\RichText::widget(['text' => $evidence->title]); ?></h4>
            <br />
            <p><?php print humhub\widgets\RichText::widget(['text' => $evidence->text]); ?></p>


            <?php if(sizeof($files) > 0): ?>
            <div class="files_area">
                <?php foreach ($files as $file): ?>
                    <a data-toggle="lightbox" data-gallery="<?php
                    if (count($files) > 1) {
                        echo "gallery-" . $evidence->content->getUniqueId();
                    }
                    ?>" href="<?php echo $file->getUrl(); ?>#.jpeg"  data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.widgets_views_showFiles', 'Close'); ?></button>'>
                        <img src='<?php echo $file->getPreviewImageUrl(200, 200); ?>'>
                    </a>
                <?php endforeach;?>
            </div>
            <?php endif;?>

            <div class="statistics">
                <?php if(Yii::$app->user->getIdentity()->group->name != "Mentors"): ?>
                    <?php //echo \humhub\widgets\TimeAgo::widget(['timestamp' => $evidence->created_at]); ?>
                    <p><?php echo Yii::t('MissionsModule.base', 'By Anonymous, {time}', array('time' => \humhub\widgets\TimeAgo::widget(['timestamp' => $evidence->created_at]))); ?></p>
                <?php else: ?>
                    <p><?php echo Yii::t('MissionsModule.base', 'By'); ?></p>
                    <a href="<?= ($evidence->content->user->getUrl()) ?>">
                        <?= ($evidence->content->user->username) ?>
                    </a>,
                    <?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $evidence->created_at]); ?>
                    <br>
                    <?= Yii::t('MissionsModule.base', 'Votes: {votes}', array('votes' => $evidence->getVoteCount() ? $evidence->getVoteCount() : "0")) ?>
                    <br>
                    <?= Yii::t('MissionsModule.base', 'Average Rating: {rating}', array('rating' => $evidence->getAverageRating()? number_format((float)$evidence->getAverageRating(), 1, '.', '') : "-")) ?>
                <?php endif; ?>
            </div>

        </div>

        <!-- REVIEWS SECTION -->
        <?php if(Yii::$app->user->getIdentity()->group->name == "Mentors"): ?>

            <div class = "text-center"><div class = "blue-border"></div></div>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h6 class="panel-title">
                            <a data-toggle="collapse" href="#collapseEvidenceReviews<?= $evidence->id ?>">
                                <?= Yii::t('MissionsModule.base', 'Reviews'); ?>
                            </a>
                        </h6>
                    </div>

                    <div class="panel-body">
                        <div id="collapseEvidenceReviews<?= $evidence->id ?>" class="panel-collapse collapse in">
                            <div class="">
                                <?php
                                $votes = $evidence->getVotes();
                                ?>

                                <?php if(!$votes || sizeof($votes) <= 0): ?>
                                    <p>
                                        <?php echo Yii::t('MissionsModule.base', 'There are no reviews yet.'); ?>
                                    </p>
                                <?php endif; ?>

                                <?php foreach($votes as $vote): ?>
                                    <div class="submitted-review" style = "padding: 10px 10px 3px; margin-bottom: 20px; border: 3px solid #9013FE;">
                                        <p><?php echo Yii::t('MissionsModule.base', 'Comment: {comment}', array('comment' => $vote->comment)); ?></p>
                                        <p><?php echo Yii::t('MissionsModule.base', 'Rating: {rating}', array('rating' => $vote->value)); ?></p>

                                        <p><?php echo Yii::t('MissionsModule.base', 'By'); ?>
                                        <a href="<?= ($vote->user->getUrl()) ?>">
                                            <?= ($vote->user->username) ?>
                                        </a>,
                                        <?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $vote->created_at]); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        <?php endif; ?>

        <div class = "text-center"><div class = "blue-border"></div></div>

        <div class="review evidence_area">
        <?php if($evidence->content->user_id != Yii::$app->user->getIdentity()->id): ?>
            <div>
                <?php
                  $collapse = "";
                  $yes = "";
                  $no = "";
                  $grade = 0;
                  $vote = $evidence->getUserVote();
                  $comment = "";
                  if($vote){
                    $yes = $vote->flag ? "checked" : "";
                    $collapse = $yes ? "in" : "";
                    $no = !$vote->flag ? "checked" : "";
                    $grade = $vote->value;
                    $comment = $vote->comment;
                  }
                ?>
                <div>
                  <?php
                    $power = $activity->getPrimaryPowers()[0]->getPower();
                    $primaryPowerTitle = isset($power->powerTranslations[0]) ? $power->powerTranslations[0]->title : $power->title; ?>
                    <h4><?= Yii::t('MissionsModule.base', 'Distribute points for {title}', array('title' => $primaryPowerTitle)) ?></h4>
                    <p style = "margin:20px 0"><?= Yii::t('MissionsModule.base', '<strong>Activity Difficulty Level:</strong> {level}', array('level' => $activity->difficultyLevel->title)) ?></p>
                    <p style = "margin-bottom:25px"><?= Yii::t('MissionsModule.base', '<strong>Activity Rubric:</strong> {rubric}', array('rubric' => isset($activity->activityTranslations[0]) ? $activity->activityTranslations[0]->rubric : $activity->rubric)) ?></p>

                <form id = "review" class="review">

                    <input type="hidden" id="evidence_id" value="<?= $evidence->id ?>">
                    <?php for ($x=1; $x <= 5; $x++): ?>
                    <label class="radio-inline">
                      <input type="radio" name="grade" value="<?= $x?>" <?= $x == $grade ? 'checked' : '' ?> >
                      <?php echo $x; ?>
                    </label>
                    <?php endfor; ?>

                    </br>
                    </br>
                        <?php if(Yii::$app->user->getIdentity()->group->name == "Mentors"): ?>
                            <p style="float:right"><?php echo Yii::t('MissionsModule.base', '{user} awarded + {value} {title}', array('user' => '', 'title' => $primaryPowerTitle, 'value' => $activity->getPrimaryPowers()[0]->value)); ?></p>
                        <?php endif; ?>

                        <?php echo Html::textArea("text", $comment , array('id' => 'review_comment', 'class' => 'text-margin form-control count-chars ', 'rows' => '5', "tabindex" => "1", 'placeholder' => Yii::t('MissionsModule.base', "140 characters required"))); ?>
                        <br>
                </div>
        <?php endif; ?>
            <button type="submit" id="post_submit_review" class="btn btn-cta2" style = "padding: 8px 16px 6px;">
                <?= Yii::t('MissionsModule.base', 'Submit Review') ?>
            </button>
        </div>
    </div>

    <?php else: ?>
        <div class="panel-body">
            <?php echo Yii::t('MissionsModule.base', 'There are no more evidences left to review.'); ?>
        </div>
    <?php endif; ?>

    </div>
</div>


<style type="text/css">



.statistics{
    font-size: 12px;
    text-align: right;
    margin-right: 2%;
    padding-top: 10px;
}

.activity_area{
    background: #e2e2e2;
    font-size: 12px;
    padding: 15px;
    font-weight: bold;
    border-radius: 4px
}

.files_area{
    padding: 15px;
    background: #e2e2e2;
    border-radius: 4px;
    text-align: center;
}

.submitted-review {
  word-wrap: break-word;
}

</style>

<script>

$(document).ready(function(){

    current = $('#current<?= $evidence->id ?>');

    if(current.text() >= 140){
        current.css('color', '#92CE92')
    }else{
        current.css('color', '#9B0000')
    }


    $(".btn-hide<?= $evidence->id ?>").click(function(){
        $("#yes-opt<?= $evidence->id ?>").collapse('hide');
    });
    $(".btn-show<?= $evidence->id ?>").click(function(){
        $("#yes-opt<?= $evidence->id ?>").collapse('show');
    });
});

$('#evidence_input_text_<?= $evidence->id ?>').keyup(function() {

    current = $('#current<?= $evidence->id ?>');
    minimun = $('#minimun<?= $evidence->id ?>');

    //change current
    current.text($('#evidence_input_text_<?= $evidence->id ?>').val().length);

    if(current.text() >= 140){
        current.css('color', '#92CE92')
    }else{
        current.css('color', '#9B0000')
    }

})

function review(id, comment, opt, grade){
    grade = grade? grade : 0;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            if(xhttp.responseText){
              if(xhttp.responseText == "success"){
                updateReview(id, opt, grade);
              }
            }
        }
    };
    xhttp.open("GET", "<?= $contentContainer->createUrl('/missions/evidence/review'); ?>&opt="+opt+"&grade="+grade+"&evidenceId="+id+"&comment="+comment , true);
    xhttp.send();

    return false;
}

function validateReview(id){

  var opt = $('#review' + id).find('input[name="yes-no-opt'+id+'"]:checked'),
      grade = $('input[name="grade"]:checked'),
      comment = $("#review_comment_"+id).val();

  opt = opt? opt.val() : null;
  grade = grade? grade.val() : null;

  if(opt == "yes"){

    if(grade >= 1){
      return review(id, comment, opt, grade);
    }

    // showMessage("Error", "Choose how many points you will award this evidence.");
    showMessage("Error", "<?= Yii::t('MissionsModule.base', 'Choose how many points you will award this evidence.') ?>");

  } else if(opt == "no"){
    return review(id, comment, opt);
  } else{
    // showMessage("Error", "Please, Answer yes or no.");
    showMessage("Error", "<?= Yii::t('MissionsModule.base', 'Please, Answer yes or no.') ?>");
  }

  return false;
}

jQuery(document).on('ajaxComplete', function () {
  var $forms    = $('form.review'),
      formCount = $forms.length,
      i         = 0;

  for (i; i < formCount; i++) {
    var id            = $forms[i].id.replace('review', ''),
        $form         = $('#review' + id),
        $submitButton = $('#post_submit_review' + id);

    $submitButton.off();
    $submitButton.on('click', function(e){
      var id  = e.target.id.replace('post_submit_review', ''),
          opt = $('#review' + id).find('input[name="yes-no-opt'+id+'"]:checked').val();

      if (opt == 'no') {
        if (confirm("<?php echo Yii::t('MissionsModule.base', 'Are you sure you want to submit this review?'); ?>")){
          $('#review' + id).submit(
              function(){
                  return validateReview(id);
              }
          );
        } else {
          e.preventDefault();
          return false;
        }
      } else {
        $('#review' + id).submit(function(e){
            e.preventDefault();
            return validateReview(id);
          }
        );
      }

    });
  }
});

</script>




<style>

/*
Reference:
https://www.everythingfrontend.com/posts/star-rating-input-pure-css.html
*/

.rating {
    overflow: hidden;
    display: inline-block;
    font-size: 0;
    position: relative;
}
.rating-input {
    float: right;
    width: 16px;
    height: 16px;
    padding: 0;
    margin: 0 0 0 -16px;
    opacity: 0;
}
.rating:hover .rating-star:hover,
.rating:hover .rating-star:hover ~ .rating-star,
.rating-input:checked ~ .rating-star {
    background-position: 0 0;
}
.rating-star,
.rating:hover .rating-star {
    position: relative;
    float: right;
    display: block;
    width: 40px;
    height: 40px;
    background: url('http://kubyshkin.ru/samples/star-rating/star.png') 0 -40px;
    background-size: cover;
}

</style>
