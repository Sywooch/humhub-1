<?php

namespace app\modules\powers\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "user_powers".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $power_id
 * @property integer $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Powers $power
 * @property User $user
 */
class UserPowers extends \yii\db\ActiveRecord
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
        return 'user_powers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'power_id', 'value'], 'required'],
            [['user_id', 'power_id', 'value'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['power_id'], 'exist', 'skipOnError' => true, 'targetClass' => Powers::className(), 'targetAttribute' => ['power_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('PowersModule.base', 'ID'),
            'user_id' => Yii::t('PowersModule.base', 'User ID'),
            'power_id' => Yii::t('PowersModule.base', 'Power ID'),
            'value' => Yii::t('PowersModule.base', 'Value'),
            'created_at' => Yii::t('PowersModule.base', 'Created At'),
            'updated_at' => Yii::t('PowersModule.base', 'Modified At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPower()
    {
        $power = Powers::findOne($this->power_id);
        return $power;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQualityPowers()
    {
        return $this->hasMany(QualityPowers::className(), ['power_id' => 'power_id']);
    }


    public function addPowerPoint($power, $user, $value){
        $userPower = UserPowers::findOne(['power_id' => $power->id, 'user_id' => $user->id]);

        if(isset($userPower)){
            if(!isset($userPower->value)){
                $userPower->value = 0;
            }
            $userPower->value += $value;
            $userPower->save();
        }else{
            $userPower = new UserPowers();
            $userPower->user_id = $user->id;
            $userPower->power_id = $power->id;
            $userPower->value = $value;
            $userPower->save();
        }  

    }

}