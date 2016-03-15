<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/21/2015
 * Time: 10:09 AM.
 */

//namespace GR\APIBundle\Model;

class DataForRestriccion implements  IDataForRestriccion
{
    private $tier;
    private $moneyType;

    public function dateToProcess()
    {
        return new DateTime('now');
    }

    public function countReferralsByCurrentTier()
    {
        // TODO: Implement referralsRestriction() method.
    }

    public function actualizarDatosCapa($tier)
    {
        // TODO: Implement actualizarDatosCapa() method.
        $this->tier = $tier;
    }

    public function countReferralsForCurrentTier()
    {
        // TODO: Implement countReferralsForCurrentTier() method.
    }

    public function countBonusForCurrentTier()
    {
        // TODO: Implement countBonusForCurrentTier() method.
    }

    public function totalOfMoneyCurrentTier()
    {
        // TODO: Implement totalOfMoneyCurrentTier() method.
    }

    public function amountOfPaymentCurrentTier()
    {
        // TODO: Implement amountOfPaymentCurrentTier() method.
    }

    /**
     * @return mixed
     */
    public function getTier()
    {
        return $this->tier;
    }

    /**
     * @param mixed $tier
     */
    public function setTier($tier)
    {
        $this->tier = $tier;
    }

    /**
     * @return string
     */
    public function getMoneyType()
    {
        return $this->moneyType;
    }

    /**
     * @param string $moneyType
     */
    public function setMoneyType($moneyType)
    {
        $this->moneyType = $moneyType;
    }

    /**
     * Fecha de inicio de la Campanna.
     *
     * @return DateTime
     */
    public function dateCampaingStart()
    {
        // TODO: Implement dateCampaingStart() method.
    }

    public function getPaymentValue()
    {
        // TODO: Implement getPaymentValue() method.
    }
}
