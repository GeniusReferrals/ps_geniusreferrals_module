<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 7/6/2015
 * Time: 11:02 PM.
 */

/**
 * Payment minimum value
 * The minimum payment value allowed to give a bonus.
 * A payment made by a client must be bigger that these values so that the bonus can be given.
 * Class MinPaymentRestriction.
 */
class MinPaymentRestriction implements IRestriction,IMoneyCollection
{
    /**
     * Arreglo de Money por cada una de las monedas permitidas.
     * Que representa el valor minimo permitido para q sea consedido el bono.
     *
     * @var array
     */
    private $minPaymentsArray;

    public function __construct()
    {
        $this->$minPaymentsArray = array();
    }

    /**
     * @param Money $money
     */
    public function addMoney($money)
    {
        $this->minPaymentsArray[$money->getMoneyType()] = $money;
    }

    /**
     * @param int $moneyType
     *
     * @return bool
     */
    public function hasMoney($moneyType)
    {
        return array_key_exists($moneyType, $this->minPaymentsArray);
    }

    /**
     * @param int $moneyType
     *
     * @return Money
     */
    public function getMoney($moneyType)
    {
        return $this->minPaymentsArray[$moneyType];
    }

    public function getName()
    {
        return 'MinPaymentRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        /*
         * Si el arreglo no tiene valores entonces la restriccion no se debe evaluar
         * porque no fue seteada y ent debera devolver true
         */
        if (count($this->minPaymentsArray) == 0) {
            return true;
        }
        if ($this->hasMoney($data->getMoneyType())) {
            if ($data->getPaymentValue() >= $this->getMoney($data->getMoneyType())->getValue()) {
                return true;
            } else {
                $errores[] = 'El Valor del pago realizado no es mayor q el minimo exigido.';

                return false;
            }
        } else {
            $errores[] = 'No se encuentra la moneda actual en esta restriccion';
        }
    }
}
