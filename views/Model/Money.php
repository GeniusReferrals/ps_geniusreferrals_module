<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/24/2015
 * Time: 10:28 PM.
 */

//namespace GR\APIBundle\Model;

class Money
{
    /**
     * @var string
     */
    private $moneyType;
    /**
     * @var int
     */
    private $value;

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $referralsValue
     */
    public function setValue($referralsValue)
    {
        $this->value = $referralsValue;
    }

    /**
     * Cuando es Percentege este campo no se usa.
     *
     * @return string
     */
    public function getMoneyType()
    {
        return $this->moneyType;
    }

    /**
     * Cuando es Percentege este campo no se usa.
     *
     * @param string $moneyType
     */
    public function setMoneyType($moneyType)
    {
        $this->moneyType = $moneyType;
    }
}
