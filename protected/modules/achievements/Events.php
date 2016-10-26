<?php

namespace humhub\modules\achievements;

use Yii;
use yii\helpers\Url;
use app\modules\missions\models\Evidence;
// use humhub\modules\matching_questions\models\MatchingQuestions;

/**
 * Description of Events
 *
 */
class Events extends \yii\base\Object
{

    public static function onEvidenceAfterSave($event){

        //get user id (evidence's author)

        $user_id = $event->sender->created_by;

        $evidences_count = Evidence::find()
        ->join('INNER JOIN', 'content as c', '`c`.`object_model`=\''.str_replace("\\", "\\\\", Evidence::classname()).'\' AND `evidence`.`id` = `c`.`object_id`')
        ->where(['evidence.created_by' => $this->user->id])
        ->andWhere(['visibility' => 1])
        ->count();
    }

    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('AchievementsModule.base', 'Achievements'),
            'url' => Url::to(['/achievements/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-th"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'achievements' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 300,
        ));
    }
    
    public static function onSpaceAdminMenuInit($event)
    {
        $space = $event->sender->space;
        if ($space->isModuleEnabled('achievements') && $space->isAdmin() && $space->isMember()) {
            $event->sender->addItem(array(
                'label' => Yii::t('AchievementsModule.base', 'Achievements'),
                'group' => 'admin',
                'url' => $space->createUrl('/achievements/admin'),
                'icon' => '<i class="fa fa-th"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'achievements' && Yii::$app->controller->id == 'container' && Yii::$app->controller->action->id != 'view'),
            ));
        }
    }

}
