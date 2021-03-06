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
    $products = Product::find()->orderBy('quantity DESC')->all();
    $coin_id = Coin::find()->where(['name' => 'EvoCoin'])->one()->id;
    $wallet = Wallet::find()->where(['owner_id' => $user->id, 'coin_id' => $coin_id])->one();

    return $this->render('index', ['products' => $products, 'wallet' => $wallet, 'user' => $user]);
  }

  // action for buying products on the marketplace page
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

      // give evocoin to seller if the product has one
      if ($product->seller_id > 0) {
        $seller_wallet = Wallet::find()->where(['owner_id' => $product->seller_id, 'coin_id' => $coin_id])->one();
        $seller_wallet->amount += $product->price;
        $seller_wallet->save();
      }

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

  public function actionReturn() {
    $bought_product_id = Yii::$app->request->get('bought_product_id');
    $bought_product = BoughtProduct::findOne(['id' => $bought_product_id]);

    // can't return a product if it's been fulfilled
    if ($bought_product->fulfilled) {
      return false;
    }

    $user = Yii::$app->user->getIdentity();
    $coin_id = Coin::find()->where(['name' => 'EvoCoin'])->one()->id;
    $wallet = Wallet::find()->where(['owner_id' => $user->id, 'coin_id' => $coin_id])->one();

    $product_price = $bought_product->product->price;
    $product_seller_id = $bought_product->product->seller_id;
    $product_name = $bought_product->product->name;

    if ($bought_product->returnProduct()) {
      // refund user
      $wallet->amount += $product_price;
      $wallet->save();

      // remove evocoin from seller if the product has one
      if ($product_seller_id > 0) {
        $seller_wallet = Wallet::find()->where(['owner_id' => $product_seller_id, 'coin_id' => $coin_id])->one();
        $seller_wallet->amount -= $product_price;
        $seller_wallet->save();
      }

      $response = json_encode(['success' => true, 'wallet_amount' => $wallet->amount, 'message' => Yii::t('MarketplaceModule.base', "You successfully returned {product}!", ['product' => $product_name])]);
    } else {
      $response = json_encode(['success' => false, 'message' => Yii::t('MarketplaceModule.base', 'Sorry, something went wrong with your return')]);
    }

    return $response;
  }

  // action to create a product for mentoring on marketplace page
  public function actionMentoring() {
    $model = new Product();
    $user = Yii::$app->user->getIdentity();

    if ($model->load(Yii::$app->request->post())) {
      $model->name = Yii::$app->request->post()['time'] . ' - ' . $user->getName();
      $model->seller_id = $user->id;
      $model->image = $user->getProfileImage()->getUrl();

      $model->created_at = date("Y-m-d H:i:s");

      if($model->save())
          return $this->redirect(['index']);
    }

    return $this->render('mentoring', ['user' => $user, 'model' => $model]);
  }

  public function actionFulfillMentoring() {
    $bought_time_id = Yii::$app->request->get('bought_time_id');
    $bought_time = BoughtProduct::findOne(['id' => $bought_time_id]);

    $bought_time->fulfill();

    $response = json_encode(['success' => true, 'message' => Yii::t('MarketplaceModule.base', "You successfully fulfilled time for {username}!", ['username' => $bought_time->getUsername()])]);

    return $response;
  }
}

?>
