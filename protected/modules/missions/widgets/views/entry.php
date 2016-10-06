<?php

use yii\helpers\Html;
use humhub\libs\Helpers;
use humhub\models\Setting;
use yii\helpers\Url;
use yii\web\JsExpression;

echo Html::beginForm();
  $activity = $evidence->getActivities();
  $mentor_average_votes = $evidence->getAverageRating('Mentors');
  $user_average_votes = $evidence->getAverageRating('Users')
?>

<h5><?php print humhub\widgets\RichText::widget(['text' => $evidence->title]); ?></h5>
<?php if (Yii::$app->user->getIdentity()->group->name == "Mentors"): ?>
  <h6><?php echo Yii::t('MissionsModule.base', 'By'); ?> <?php echo $name ?></h4>
<?php endif; ?>
<p><?php print humhub\widgets\RichText::widget(['text' => $evidence->text]);?></p>

<hr>

<!-- SHOW FILES -->

<?php $files = \humhub\modules\file\models\File::getFilesOfObject($evidence); ?>

<?php if(!empty($files)): ?>
<ul class="files" style="list-style: none; margin: 0;" id="files-<?php echo $evidence->getPrimaryKey(); ?>">
    <?php foreach ($files as $file) : ?>
        <?php
        if ($file->getMimeBaseType() == "image" && Setting::Get('hideImageFileInfo', 'file'))
            continue;
        ?>
        <li class="mime <?php echo \humhub\libs\MimeHelper::getMimeIconClassByExtension($file->getExtension()); ?>"><a
                href="<?php echo $file->getUrl(); ?>" target="_blank"><span
                    class="filename"><?php echo Html::encode(Helpers::trimText($file->file_name, 40)); ?></span></a>
            <span class="time" style="padding-right: 20px;"> - <?php echo Yii::$app->formatter->asSize($file->size); ?></span>

            <?php if ($file->getExtension() == "mp3") : ?>
                <!-- Integrate jPlayer -->
                <?php
                echo xj\jplayer\AudioWidget::widget(array(
                    'id' => $file->id,
                    'mediaOptions' => [
                        'mp3' => $file->getUrl(),
                    ],
                    'jsOptions' => [
                        'smoothPlayBar' => true,
                    ]
                ));
                ?>
            <?php endif; ?>

        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<hr>

<div class = "evidence-mission-box">
  <h6><?= Yii::t('MissionsModule.base', 'Mission {mission}, Activity {activity}:', array('mission' => $activity->mission->position, 'activity' => $activity->position)); ?></h6>
  <h5><?php echo Html::a(
          (isset($activity->activityTranslations[0]) ? $activity->activityTranslations[0]->title : $activity->title),
          ['show', 'activityId' => $activity->id, 'sguid' => $contentContainer->guid], array('class' => '')); ?></h5>
  <div class="votes-container row">
    <div class="mentor-votes col-xs-9">
      <div class="col-xs-12 no-padding-left">
        <em><?php echo Yii::t('MissionsModule.base', 'Mentor Reviews'); ?></em>
      </div>
      <div class="rating col-xs-5 no-padding-left">
        <p>
          <?php echo Yii::t('MissionsModule.base', 'Average Rating: {votes}', array('votes' => $mentor_average_votes? number_format((float)$mentor_average_votes, 1, '.', '') : "-")); ?>
        </p>
        <p>
          <?php echo Yii::t('MissionsModule.base', 'Mentor Reviews: {votes}', array('votes' => $evidence->getVoteCount('Mentors')? $evidence->getVoteCount('Mentors') : "0")) ?>
        </p>
      </div>




      <div class="stars col-xs-6">
        <?php for ($i = 0; $i < 5; $i++): ?>
          <?php if ($mentor_average_votes > $i): ?>
            <?php if (($mentor_average_votes - $i) < 1): ?>
              <i class="fa fa-star-half-o" aria-hidden="true"></i>
            <?php else: ?>
              <i class="fa fa-star" aria-hidden="true"></i>
            <?php endif; ?>
          <?php else: ?>
            <i class="fa fa-star-o" aria-hidden="true"></i>
          <?php endif; ?>
        <?php endfor; ?>
        <p>
          <?php echo Yii::t('MissionsModule.base', 'Avg Mentor Rating'); ?>
        </p>
      </div>
    </div>
    <div class="agent-votes col-xs-3">
      <em><?php echo Yii::t('MissionsModule.base', 'Agent Reviews'); ?></em>
      <div class="rating">
        <p>
          <?php echo Yii::t('MissionsModule.base', 'Average Rating: {votes}', array('votes' => $user_average_votes? number_format((float)$user_average_votes, 1, '.', '') : "-")); ?>
        </p>
        <p>
          <?php echo Yii::t('MissionsModule.base', 'Agent Reviews: {votes}', array('votes' => $evidence->getVoteCount('Users')? $evidence->getVoteCount('Users') : "0")) ?>
        </p>
      </div>
    </div>
  </div>
</div>

<?php echo Html::endForm(); ?>

</br>

<style media="screen">
  .panel .evidence-mission-box h6 {
    font-size: 10pt;
    text-transform: uppercase;
    text-align: center;
    margin: 10px 0 0 0;
  }

  .panel .evidence-mission-box h5 {
    text-transform: uppercase;
    text-align: center;
    margin: 0 0 0.5em 0;
    text-decoration: underline;
  }

  .panel .evidence-mission-box h5 a {
    color: #254054;
    font-weight: 100;
  }

  .panel .evidence-mission-box h5 a:hover {
    color:  #4B667A;
  }

  .panel .evidence-mission-box em {
    text-transform: uppercase;
    font-style: normal;
    font-size: 0.8em;
    color: #254054;
  }

  .stars {
    text-align: center;
    font-size: 2em;
    color: #ece046;
    margin-top: -14px;
  }

  .evidence-mission-box .stars p {
    text-transform: uppercase;
    font-size: 8pt;
    font-weight: bold;
  }

  .panel .evidence-mission-box p {
    margin: 0;
  }

  .panel .evidence-mission-box .agent-votes {
    text-align: right;
    float: right;
    border-left: 2px solid #254054;
  }

  .panel .evidence-mission-box .agent-votes p {
    font-size: 0.9em;
  }

  .no-padding-left {
    padding-left: 0 !important;
  }
</style>

<?php if($evidence->content->user_id != Yii::$app->user->getIdentity()->id && Yii::$app->user->getIdentity()->group->name == "Mentors"): ?>
<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h6 class="panel-title">
        <a data-toggle="collapse" href="#collapseEvidence<?= $evidence->id ?>"  style="color:#254054">
        	<?= Yii::t('MissionsModule.base', 'Review') ?>
        </a>
      </h6>
    </div>

    <div id="collapseEvidence<?= $evidence->id ?>" class="panel-collapse collapse in">
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
            $primaryPowerTitle = $activity->getPrimaryPowers()[0]->getPower()->title;

            if(Yii::$app->language == 'es' && isset($activity->getPrimaryPowers()[0]->getPower()->powerTranslations[0]))
                $primaryPowerTitle = $activity->getPrimaryPowers()[0]->getPower()->powerTranslations[0]->title;
          ?>
        	<h2><?= Yii::t('MissionsModule.base', 'Distribute points for {title}', array('title' => $primaryPowerTitle)) ?></h2>
        	<p>
        		<?php //$activity->rubric ?>
            <?= isset($activity->activityTranslations[0]) ? $activity->activityTranslations[0]->rubric : $activity->rubric ?>
        	</p>
        	<form id = "review<?= $evidence->id ?>" class="review">
        		<div class="radio">
      				<label>
      					<input type="radio" name="yes-no-opt<?= $evidence->id ?>" class="btn-show<?= $evidence->id ?>" value="yes" <?= $yes ?> >
      					Yes
      				</label>
      				<div id="yes-opt<?= $evidence->id ?>" class="radio regular-radio-container collapse <?= $collapse ?>">
      					<span class="rating">
                    <?php for ($x=1; $x <= 5; $x++): ?>
                    <label class="radio-inline">
                      <input type="radio" name="grade" value="<?= $x?>" <?= $x == $grade ? 'checked' : '' ?> >
                      <?php echo $x; ?>
                    </label>
                    <?php endfor; ?>
                </span>
      					<p>
                  <?= Yii::t('MissionsModule.base', 'How many points will you award this evidence?') ?>
      					</p>
      				</div>
    			  </div>
    			  <div class="radio">
    				  <label>
    					<input type="radio" name="yes-no-opt<?= $evidence->id ?>" class="btn-hide<?= $evidence->id ?>" value="no" <?= $no ?>>
    					 No
    				  </label>
    			  </div>
    			  <br>
            <?php echo Html::textArea("text", $comment , array('id' => 'review_comment_'.$evidence->id, 'class' => 'text-margin form-control count-chars ', 'rows' => '5', "tabindex" => "1", 'placeholder' => Yii::t('MissionsModule.base', "140 characters required"))); ?>
    			  <br>

    			  <br>
    			  <button type="submit" id="post_submit_review<?= $evidence->id ?>" class="btn btn-cta1 submit">
              <?= Yii::t('MissionsModule.base', 'Submit Review') ?>
    			  </button>
        	</form>
        </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if($evidence->content->user_id == Yii::$app->user->getIdentity()->id || Yii::$app->user->getIdentity()->group->name == "Mentors"): ?>

<BR>

<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">

                <a data-toggle="collapse" href="#collapseMentorEvidenceReviews<?= $evidence->id ?>" style="color:#254054" aria-expanded="false" class="collapsed">
                    <?= Yii::t('MissionsModule.base', 'Mentor Reviews') ?>
                </a>
            </h6>
        </div>

        <div class="panel-body">
            <div id="collapseMentorEvidenceReviews<?= $evidence->id ?>"  class="panel-collapse collapse" aria-expanded="false">
                <div class="">
                    <?php
                    $votes = $evidence->getVotes('Mentors');
                    ?>

                    <?php if(!$votes || sizeof($votes) <= 0): ?>
                        <p>
                            <?php echo Yii::t('MissionsModule.base', 'There are no reviews yet.'); ?>
                        </p>
                    <?php endif; ?>

                    <?php foreach($votes as $vote): ?>
                        <div style = "padding: 10px 10px 3px; margin-bottom: 20px; border: 3px solid #9013FE; word-wrap: break-word;">
                            <p><?php echo Yii::t('MissionsModule.base', 'Comment: {comment}', array('comment' => $vote->comment)); ?></p>
                            <p><?php echo Yii::t('MissionsModule.base', 'Rating: {rating}', array('rating' => $vote->value)); ?></p>

                            <?php if(Yii::$app->user->getIdentity()->group->name == "Mentors" || $vote->user->group->name == "Mentors"): ?>
                                <p><?php echo Yii::t('MissionsModule.base', 'By'); ?>
                                <a href="<?= ($vote->user->getUrl()) ?>">
                                    <?= ($vote->user->username) ?>
                                </a>,
                                <?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $vote->created_at]); ?></p>
                            <?php else: ?>
                                <p><?php echo Yii::t('MissionsModule.base', 'By Anonymous, {time}', array('time' => \humhub\widgets\TimeAgo::widget(['timestamp' => $vote->created_at]))); ?></p>
                            <?php endif; ?>

                            <?php echo \humhub\modules\comment\widgets\CommentLink::widget(['object' => $vote, 'mode' => \humhub\modules\comment\widgets\CommentLink::MODE_INLINE]); ?>
                            <?php echo \humhub\modules\comment\widgets\Comments::widget(array('object' => $vote)); ?>

                        </div>

                    
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">

                <a data-toggle="collapse" href="#collapseAgentEvidenceReviews<?= $evidence->id ?>" style="color:#254054" aria-expanded="false" class="collapsed">
                    <?= Yii::t('MissionsModule.base', 'Agent Reviews') ?>
                </a>
            </h6>
        </div>

        <div class="panel-body">
            <div id="collapseAgentEvidenceReviews<?= $evidence->id ?>"  class="panel-collapse collapse" aria-expanded="false">
                <div class="">
                    <?php
                    $votes = $evidence->getVotes('Users');
                    ?>

                    <?php if(!$votes || sizeof($votes) <= 0): ?>
                        <p>
                            <?php echo Yii::t('MissionsModule.base', 'There are no reviews yet.'); ?>
                        </p>
                    <?php endif; ?>

                    <?php foreach($votes as $vote): ?>
                        <div style = "padding: 10px 10px 3px; margin-bottom: 20px; border: 3px solid #9013FE; word-wrap: break-word;">
                            <p><?php echo Yii::t('MissionsModule.base', 'Comment: {comment}', array('comment' => $vote->comment)); ?></p>
                            <p><?php echo Yii::t('MissionsModule.base', 'Rating: {rating}', array('rating' => $vote->value)); ?></p>

                            <?php if(Yii::$app->user->getIdentity()->group->name == "Mentors" || $vote->user->group->name == "Mentors"): ?>
                                <p><?php echo Yii::t('MissionsModule.base', 'By'); ?>
                                <a href="<?= ($vote->user->getUrl()) ?>">
                                    <?= ($vote->user->username) ?>
                                </a>,
                                <?php echo \humhub\widgets\TimeAgo::widget(['timestamp' => $vote->created_at]); ?></p>
                            <?php else: ?>
                                <p><?php echo Yii::t('MissionsModule.base', 'By Anonymous, {time}', array('time' => \humhub\widgets\TimeAgo::widget(['timestamp' => $vote->created_at]))); ?></p>
                            <?php endif; ?>
                        </div>



                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <br>

    <?php if($evidence->content->visibility == 0): ?>

     <div>
        <a class="btn btn-success" href="<?= $contentContainer->createUrl('/missions/evidence/publish', ['id' => $evidence->id]) ?>">
            Publish 
        </a>

    <?php
    /*
    echo \humhub\widgets\AjaxButton::widget([
        'label' => Yii::t('ContentModule.widgets_views_editLink', 'Edit'),
        'tag' => 'a',
        'ajaxOptions' => [
            'type' => 'POST',
            'success' => new JsExpression('function(html){ $("#wall_content_' . $evidence->content->getUniqueId() . '").replaceWith(html); }'),
            'url' => $evidence->content->container->createUrl('/missions/evidence/edit', ['id' => $evidence->id]),
        ],
        'htmlOptions' => [
            'href' => '#',
            'class' => 'btn btn-info'
        ]
    ]);
    */
    ?>

    </div>
    <?php endif; ?>

</div>
<?php endif; ?>


<style type="text/css">

.statistics{
  font-size: 12px;
  text-align: right;
  margin-right: 2%;
}

.activity_area{
	font-size: 12px;
}

</style>

<script>
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


$(document).ready(function(){
    $(".btn-hide<?= $evidence->id ?>").click(function(){
        $("#yes-opt<?= $evidence->id ?>").collapse('hide');
    });
    $(".btn-show<?= $evidence->id ?>").click(function(){
        $("#yes-opt<?= $evidence->id ?>").collapse('show');
    });
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

  .panel .evidence-mission-box h6 {
    font-size: 10pt;
    text-transform: uppercase;
    text-align: center;
    margin: 10px 0 0 0;
  }

  .panel .evidence-mission-box h5 {
    text-transform: uppercase;
    text-align: center;
    margin: 0;
    text-decoration: underline;
  }

  .panel .evidence-mission-box h5 a {
    color: #254054;
    font-weight: 100;
  }

  .panel .evidence-mission-box h5 a:hover {
    color:  #4B667A;
  }

  .panel .evidence-mission-box em {
    text-transform: uppercase;
    font-style: normal;
    font-size: 0.8em;
    color: #254054;
  }

  .stars {
    text-align: center;
    font-size: 2em;
    color: #ece046;
    margin-top: -14px;
  }

  .evidence-mission-box .stars p {
    text-transform: uppercase;
    font-size: 8pt;
    font-weight: bold;
  }

  .panel .evidence-mission-box p {
    margin: 0;
  }

  .panel .evidence-mission-box .agent-votes {
    text-align: right;
    float: right;
    border-left: 2px solid #254054;
  }

  .panel .evidence-mission-box .agent-votes p {
    font-size: 0.9em;
  }

  .no-padding-left {
    padding-left: 0 !important;
  }
</style>
