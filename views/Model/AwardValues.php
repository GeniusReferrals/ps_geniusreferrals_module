<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/24/2015
 * Time: 10:28 PM.
 */

//namespace GR\APIBundle\Model;

class AwardValues
{
    /**
     * posibles valores de $MeasureType.
     */
    const PERCENTAGE = 'PERCENTAGE';
    const FIXED = 'FIXED';

    /**
     * @var array
     */
    private $refererAwardsArray;
    /**
     * forma de calculo porcentage o valor fijo PERCENTAGE or FIXED.
     *
     * @var string
     */
    private $MeasureType;

    /**
     * @var array
     */
    private $awardArray;

    public function __construct()
    {
        $this->awardArray = array();
        /*
         * This award is always fix
         */
        $this->refererAwardsArray = array();
    }

    /**
     * @param Money $award
     */
    public function addRefererAward(Money $award)
    {
        $this->refererAwardsArray[$award->getMoneyType()] = $award;
    }

    /**
     * @param $moneyType
     *
     * @return bool
     */
    public function hasRefererAward($moneyType)
    {
        return array_key_exists($moneyType, $this->awardArray);
    }
    /**
     * @param $moneyType
     *
     * @return Money
     */
    public function getRefererAward($moneyType)
    {
        return $this->awardArray[$moneyType];
    }

    /**
     * @param Money $award
     */
    public function addAward(Money $award)
    {
        $this->awardArray[$award->getMoneyType()] = $award;
    }

    /**
     * @param $moneyType
     *
     * @return bool
     */
    public function hasAward($moneyType)
    {
        return array_key_exists($moneyType, $this->awardArray);
    }
    /**
     * @param $moneyType
     *
     * @return Money
     */
    public function getAward($moneyType)
    {
        return $this->awardArray[$moneyType];
    }
    /**
     * @return string
     */
    public function getMeasureType()
    {
        return $this->MeasureType;
    }

    /**
     * @param string $MeasureType
     */
    public function setMeasureType($MeasureType)
    {
        $this->MeasureType = $MeasureType;
    }

    /**
     * La cantidad de dinero dada a un usuario referido es fija siemrpe pues no hay  de dodne sacar porcientos.
     *
     * @return int
     */
    public function getRefererAwardsArray()
    {
        return $this->refererAwardsArray;
    }

    public function isPercentage()
    {
        return $this->getMeasureType() == self::PERCENTAGE;
    }
    public function isFixed()
    {
        return $this->getMeasureType() == self::FIXED;
    }
}
