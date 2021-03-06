<?php

namespace app\modules\missions\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use app\modules\powers\models\Powers;
use app\modules\languages\models\Languages;

/**
 * This is the model class for table "activity_powers".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $power_id
 * @property integer $flag
 * @property integer $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Powers $power
 * @property Activities $activity
 */
class ActivityPowers extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_powers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'power_id', 'flag', 'value'], 'required'],
            [['activity_id', 'power_id', 'flag', 'value'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['power_id'], 'exist', 'skipOnError' => true, 'targetClass' => Powers::className(), 'targetAttribute' => ['power_id' => 'id']],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activities::className(), 'targetAttribute' => ['activity_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('MissionsModule.model', 'ID'),
            'activity_id' => Yii::t('MissionsModule.model', 'Activity ID'),
            'power_id' => Yii::t('MissionsModule.model', 'Power ID'),
            'flag' => Yii::t('MissionsModule.model', 'Flag'),
            'value' => Yii::t('MissionsModule.model', 'Value'),
            'created_at' => Yii::t('MissionsModule.model', 'Created At'),
            'updated_at' => Yii::t('MissionsModule.model', 'Modified At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPower()
    {        
        //return Powers::findOne($this->power_id);
        
        $power = Powers::find()
        ->where(['=', 'id', $this->power_id])
        ->with([
            'powerTranslations' => function ($query) {
                // $lang = Languages::findOne(['code' => Yii::$app->language]);
                // $query->andWhere(['language_id' => $lang->id]);
                
                $lang = Languages::findOne(['code' => Yii::$app->language]);
                
                if(isset($lang))
                    $query->andWhere(['language_id' => $lang->id]);
                else{
                    $lang = Languages::findOne(['code' => 'en-US']);
                    $query->andWhere(['language_id' => $lang->id]);
                }
            },
        ])->one();
        
        return $power;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activities::className(), ['id' => 'activity_id']);
    }
}
