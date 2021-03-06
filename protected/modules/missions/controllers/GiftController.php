<?php

namespace humhub\modules\missions\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use humhub\modules\missions\controllers\AlertController;
use app\modules\coin\models\Wallet;
use humhub\modules\user\models\User;
use app\modules\missions\models\EvokeLog;

class GiftController extends Controller
{
	public function actionEvocoins(){
		$user = Yii::$app->user->getIdentity();
        $wallet = Wallet::findOne(['owner_id' => $user->id]);
        $receiver_id = Yii::$app->request->get('user_id');
        $receiver = User::findOne($receiver_id);
        $receiver_wallet = Wallet::findOne(['owner_id' => $receiver_id]);
        $value = Yii::$app->request->post('Gift')['evocoins'];

        if(($wallet->amount >= $value) && ($value > 0 )){
			$wallet->amount -= $value;
			$wallet->save();
			$receiver_wallet->amount += $value;
			$receiver_wallet->save();

			// AlertController::createAlert(Yii::t('MissionsModule.base', 'Success!'), Yii::t('MissionsModule.base', 'You gave {number} evocoins to {name}.', ['number' => $value, 'name' => $receiver->username]));

			$log['id'] = 'gift_evocoin';
			$log['user_id'] = $user->id;
			$log['receiver_id'] = $receiver_id;
			$log['value'] = $value;

			EvokeLog::log($log);

			Yii::info(json_encode($log));
			AlertController::createAlert(Yii::t('MissionsModule.base', 'Success!'), Yii::t('MissionsModule.base', 'You gave {number} evocoins to {name}.', ['number' => $value, 'name' => $receiver->username]));

		}else{
			AlertController::createAlert("Error", Yii::t('MissionsModule.base', 'No enough Evocoins!'));
		}
	}

}