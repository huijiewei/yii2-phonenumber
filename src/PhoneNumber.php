<?php
/**
 * Created by PhpStorm.
 * User: huijiewei
 * Date: 2018/8/19
 * Time: 14:54
 */

namespace huijiewei\phonenumber;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\helpers\StringHelper;

class PhoneNumber extends Component
{
    public $region;
    public $format;

    public $nationalRegion = 'CN';
    public $nationalFormat = false;

    private $_formatName = [
        'INTERNATIONAL' => PhoneNumberFormat::INTERNATIONAL,
        'NATIONAL' => PhoneNumberFormat::NATIONAL,
        'RFC3966' => PhoneNumberFormat::RFC3966,
        'E164' => PhoneNumberFormat::E164,
    ];

    private $_phoneNumber;

    public function init()
    {
        parent::init();

        if (!strlen($this->region)) {
            throw new InvalidArgumentException('默认区域不能为空');
        }

        if (!strlen($this->format) || !in_array($this->format, $this->_formatName)) {
            throw new InvalidArgumentException('默认格式不能为空或者设置错误');
        }

        $this->format = $this->_formatName[$this->format];
    }

    /**
     * @param $phone
     *
     * @return bool
     */
    public function validate($phone)
    {
        if (!$this->parse($phone)) {
            return false;
        }

        return static::phoneUtil()->isValidNumberForRegion(
            $this->getPhoneNumber(),
            static::phoneUtil()->getRegionCodeForCountryCode($this->getPhoneNumber()->getCountryCode())
        );
    }

    /**
     * @param $phone
     *
     * @return boolean
     */
    public function parse($phone)
    {
        $region = StringHelper::startsWith($phone, '+') ? null : $this->region;

        try {
            $this->_phoneNumber = self::phoneUtil()->parse($phone, $region);

            return true;
        } catch (NumberParseException $ex) {
            return false;
        }
    }

    /**
     * @return PhoneNumberUtil
     */
    private static function phoneUtil()
    {
        return PhoneNumberUtil::getInstance();
    }

    /**
     * @return \libphonenumber\PhoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->_phoneNumber;
    }

    /**
     * @param $phone
     * @param null $format
     *
     * @return string
     */
    public function format($phone, $format = null)
    {
        if ($format === null) {
            $format = $this->format;
        }

        if (!strlen($phone)) {
            return '';
        }

        if (!$this->parse($phone)) {
            return '';
        }

        if ($this->nationalFormat && $format == PhoneNumberFormat::INTERNATIONAL) {
            if (static::phoneUtil()->getRegionCodeForCountryCode($this->getPhoneNumber()->getCountryCode()) == $this->nationalRegion) {
                $format = PhoneNumberFormat::NATIONAL;
            }
        }

        return static::phoneUtil()->format($this->getPhoneNumber(), $format);
    }
}