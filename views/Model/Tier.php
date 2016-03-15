<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/20/2015
 * Time: 10:05 PM.
 */

//namespace GR\APIBundle\Model;

class Tier
{
    const REFERRALS = 'REFERRALS';
    const BONUS = 'BONUS';
    const ALL = 'ALL'; // este puede ser util en la reacion del sql

    private $restrictionList;
    /**
     * @var AwardValues
     */
    private $awardValues;
    /**
     * Esto es para saber si el resultado de la capa esta enfocado en los referidos, en los bonos o en ambos
     * esto se usara a la hora transformarlo al sql q le corresponde.
     *
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->restrictionList = array();
    }
    public function addRestriction($restriction)
    {
        $this->restrictionList[] = $restriction;
    }

    public function hasAward($moneyType)
    {
        return $this->awardValues->hasAward($moneyType);
    }

    /**
     * Shotcut  to get the Award.
     *
     * @param string $moneyType
     *
     * @return Money
     */
    public function getAward($moneyType)
    {
        return $this->awardValues->getAward($moneyType);
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        //Comprobar q el tipo de moneda existe entre los posibles valores q se devuelven
        if ($this->hasAward($data->getMoneyType())) {
            /**
             * @var \GR\APIBundle\Model\IRestriction
             */
            foreach ($this->restrictionList as $restriction) {
                if (!$restriction->valid($data, $errores)) {
                    return false;
                }
            }

            return true;
        } else {
            $errores[] = 'This Tier not have that money type';
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getAwardValues()
    {
        return $this->awardValues;
    }

    /**
     * @param mixed $awardValues
     */
    public function setAwardValues($awardValues)
    {
        $this->awardValues = $awardValues;
    }

    /**
     * Shotcut to $awardValues->getRefererValue
     * La cantidad de dinero dada a un usuario referido es fija siemrpe pues no hay  de dodne sacar porcientos.
     *
     * @return int
     */
    public function getRefererValue()
    {
        return $this->awardValues->getRefererAwardsArray();
    }

    /**
     * Shortcut addRefererAward.
     *
     * @param Money $award
     */
    public function addRefererAward(Money $award)
    {
        $this->awardValues->addRefererAward($award);
    }

    /**
     * Shortcut hasRefererAward.
     *
     * @param $moneyType
     *
     * @return bool
     */
    public function hasRefererAward($moneyType)
    {
        return $this->awardValues->hasRefererAward($moneyType);
    }
    /**
     * Shortcut getRefererAward.
     *
     * @param $moneyType
     *
     * @return Money
     */
    public function getRefererAward($moneyType)
    {
        return $this->awardValues->getRefererAward($moneyType);
    }
}
