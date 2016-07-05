<?php

namespace humhub\modules\missions\widgets;

use \yii\base\Widget;

class HomePageStats extends \yii\base\Widget
{

	public $powers;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('home_page_stats', ['userPowers' => $this->powers]);
    }

}

?>