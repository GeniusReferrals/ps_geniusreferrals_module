<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/22/2015
 * Time: 11:16 PM.
 */

//namespace GR\APIBundle\Model\Restriction;

class AmountOfPaymentRestriction
{
    /**
     * @var int
     */
    private $amountOfPayment;

    /**
     * Cantidad minima de bonos para q sea valida la restriccion.
     *
     * @param int $maxMoney
     */
    public function __construct($amountOfPayment)
    {
        $this->amountOfPayment = $amountOfPayment;
    }
    public function getName()
    {
        return 'AmountOfPaymentRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        // TODO: Implement valid() method.
        if ($this->amountOfPayment >= $data->amountOfPaymentCurrentTier()) {
            return true;
        }
        $errores[] = 'Ya se llego a la cantidad maxima de pagos permitidos';

        return false;
    }
}
