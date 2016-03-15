<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/24/2015
 * Time: 12:32 AM.
 */

//namespace GR\APIBundle\Model;

//esta clase es un decorador para la clase DataForRestriccion
// y su funcion es ser llamada una vez q se da el bono para comprobar
// en su constructor se le pasa el mismo bono dado y

class NewDataForRestriccion implements  IDataForRestriccion
{
    /**
     * @var IDataForRestriccion
     */
    private $data;

    /**
     * @var Tier
     */
    private $tier;

    public function __construct(IDataForRestriccion $data, Tier $tier)
    {
        $this->data = $data;
        $this->tier = $tier;
    }
    public function dateToProcess()
    {
        // TODO: Implement dateToProcess() method.
        $this->data->dateToProcess();
    }

    public function countReferralsForCurrentTier()
    {
        // TODO: Implement countReferralsForCurrentTier() method.
        $this->data->countReferralsForCurrentTier();
    }

    public function countBonusForCurrentTier()
    {
        // TODO: Implement countBonusForCurrentTier() method.
        return $this->data->countReferralsForCurrentTier() + 1;
    }

    /**
     * Se adiciona al total de dinero el dinero de la capa actual
     * para poder comprobar las restricciones globales.
     *
     * @return int
     */
    public function totalOfMoneyCurrentTier()
    {
        // TODO: Implement totalOfMoneyCurrentTier() method.
        $totalAfter = $this->data->totalOfMoneyCurrentTier();
        if ($this->tier->hasAwardValue($this->data->getMoneyType())) {
            $moneyType = $this->data->getMoneyType();
            $actual = $this->tier->getAwardValue($moneyType);
            $referralsValue = $actual->getReferralsValue();

            return $totalAfter + $referralsValue;
        }

        return $totalAfter;
    }

    public function amountOfPaymentCurrentTier()
    {
        //aki habria q ver si dar un bono es dar un pago tb
        $this->data->amountOfPaymentCurrentTier();
    }

    public function actualizarDatosCapa($tier)
    {
        $this->data->actualizarDatosCapa($tier);
    }

    public function getMoneyType()
    {
        $this->data->getMoneyType();
    }

    public function setMoneyType($moneyType)
    {
        $this->data->setMoneyType($moneyType);
    }
}
