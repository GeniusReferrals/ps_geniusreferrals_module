<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/21/2015
 * Time: 10:04 AM.
 */

//namespace GR\APIBundle\Model;

///aki iria una pregunta importante el resultado de la evaluacion de la capa puede ser
//una cantidad de dinero o un bono
interface IDataForRestriccion
{
    public function dateToProcess();

    /**
     * Fecha de inicio de la Campanna.
     *
     * @return DateTime
     */
    public function dateCampaingStart();
    public function countReferralsForCurrentTier();
    public function countBonusForCurrentTier();
    public function totalOfMoneyCurrentTier();
    public function amountOfPaymentCurrentTier();
    public function getMoneyType();
    public function setMoneyType($moneyType);
    public function actualizarDatosCapa($tier);
    public function getPaymentValue();
}
