<?php
/**
 * Created by PhpStorm.
 * User: Huijiewei
 * Date: 2014/11/27
 * Time: 15:53
 */

namespace huijiewei\phonenumber;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class IntlTelInputWidget extends InputWidget
{
    public $options = [];

    public $clientOptions = [];

    public function init()
    {
        parent::init();

        $this->options = ArrayHelper::merge([
            'class' => 'form-control',
        ], $this->options);
    }

    public function run()
    {
        echo IntlTelInputJsWidget::widget(['querySelector' => '#' . $this->options['id'], 'clientOptions' => $this->clientOptions]);

        if ($this->hasModel()) {
            return Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            return Html::textInput($this->name, $this->value, $this->options);
        }
    }
}
