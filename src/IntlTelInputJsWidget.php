<?php
/**
 * Created by PhpStorm.
 * User: Huijiewei
 * Date: 2014/11/27
 * Time: 15:53
 */

namespace huijiewei\phonenumber;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\View;

class IntlTelInputJsWidget extends Widget
{
    public $querySelector = [];

    public $onLoadComponent = false;

    public $clientOptions = [];

    /* @var $_assetBundle AssetBundle */
    private $_assetBundle;

    public function init()
    {
        parent::init();

        $this->clientOptions = ArrayHelper::merge([
            'initialCountry' => 'cn',
            'placeholderNumberType' => 'MOBILE',
            'separateDialCode' => false,
            'autoPlaceholder' => 'aggressive',
            'formatOnDisplay' => false,
            'preferredCountries' => ['us', 'gb', 'cn'],
        ], $this->clientOptions);

        $this->registerAssetBundle();

        $this->registerScript();
    }

    public function registerAssetBundle()
    {
        $this->_assetBundle = IntlTelInputAsset::register($this->getView());
    }

    public function registerScript()
    {
        $clientOptions = Json::encode($this->clientOptions);

        $js = <<<EOD
var telInput = document.querySelector('{$this->querySelector}');
if(telInput) {
    var intlTelInput = window.intlTelInput(telInput, {$clientOptions});
    
    $(telInput).on("keyup change", resetIntlTelInput);

    function resetIntlTelInput() {
      if (typeof intlTelInputUtils !== 'undefined') {
          var currentText = intlTelInput.getNumber(intlTelInputUtils.numberFormat.E164);
          
          if (typeof currentText === 'string') {
              $(this).val(currentText);
          }
      }
    }
}
EOD;

        if ($this->onLoadComponent) {
            $this->getView()->registerJs('$(document.body).on(\'loadComponent\', function (e) {' . $js . '});', View::POS_END);
        } else {
            $this->getView()->registerJs($js);
        }
    }

    public function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerAssetBundle();
        }

        return $this->_assetBundle;
    }
}
