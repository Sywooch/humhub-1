<?php

namespace humhub\modules\marketplace\controllers;

use Yii;
use yii\web\Controller;
use app\modules\marketplace\models\Product;
use app\modules\marketplace\models\BoughtProduct;
use humhub\modules\user\models\User;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\modules\coin\models\Coin;
use app\modules\coin\models\Wallet;


/**
 * AdminController
 *
 */
class ProductsController extends Controller
{
  public function actionIndex(){
    $user = Yii::$app->user->getIdentity();
    $products = Product::find()->all();
    $coin_id = Coin::find()->where(['name' => 'EvoCoin'])->one()->id;
    $wallet = Wallet::find()->where(['owner_id' => $user->id, 'coin_id' => $coin_id])->one();

    return $this->render('index', ['products' => $products, 'wallet' => $wallet, 'user' => $user]);
  }

  public function actionBuy() {
    $product_id = Yii::$app->request->get('product_id');
    $product = Product::findOne(['id' => $product_id]);

    // can't buy a sold out product
    if ($product->quantity < 1) {
      return false;
    }

    $user = Yii::$app->user->getIdentity();
    $coin_id = Coin::find()->where(['name' => 'EvoCoin'])->one()->id;
    $wallet = Wallet::find()->where(['owner_id' => $user->id, 'coin_id' => $coin_id])->one();

    // can't buy if they don't have enough evocoin
    if ($wallet->amount < $product->price) {
      return json_encode(['success' => false, 'message' => Yii::t('MarketplaceModule.base', 'Sorry you do not have enough evocoin!')]);
    }

    $bought_product = new BoughtProduct(['product_id' => $product->id, 'user_id' => $user->id]);

    if ($product->buyOne()) {
      $wallet->amount -= $product->price;
      $wallet->save();
      $product->save();
      $bought_product->save();

      if ($product->quantity < 1) {
        $sold_out_message = Yii::t('MarketplaceModule.base', 'Sold Out');
      } else {
        $sold_out_message = '';
      }

      $response = json_encode(['success' => true, 'wallet_amount' => $wallet->amount, 'product_quantity' => $product->quantity, 'message' => Yii::t('MarketplaceModule.base', "You successfully bought {product}!", ['product' => $product->name]), 'sold_out_message' => $sold_out_message]);
    } else {
      $response = json_encode(['success' => false, 'message' => Yii::t('MarketplaceModule.base', 'Sorry, something went wrong with your purchase')]);
    }


    return $response;
  }
}

?>
