<?php

/**
 * User: Huijiewei
 * Date: 2015-03-31
 * Time: 14:37.
 */
namespace huijiewei\phonenumber;

use libphonenumber\PhoneNumberFormat;
use Yii;
use yii\validators\Validator;

class PhoneNumberValidator extends Validator
{
    public $region;

    public $format;

    public $phoneNumber;

    private $_phoneUtil;

    /**
     * @return PhoneNumber
     */
    private function getUtil()
    {
        return $this->_phoneUtil;
    }

    public function init()
    {
        parent::init();

        if ($this->message === null) {
            $this->message = '{attribute} 格式无效';
        }

        $this->_phoneUtil = Yii::$app->get('phonenumber');
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if ($this->region !== null) {
            $this->getUtil()->region = $this->region;
        }

        if ($this->format !== null) {
            $this->getUtil()->format = $this->format;
        }

        if ($this->getUtil()->validate($value)) {
            $this->phoneNumber = $this->getUtil()->format($value, PhoneNumberFormat::E164);
            return null;
        } else {
            return [$this->message, []];
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        parent::validateAttribute($model, $attribute);

        if (!$model->hasErrors($attribute)) {
            $model->$attribute = $this->phoneNumber;
        }
    }
}
