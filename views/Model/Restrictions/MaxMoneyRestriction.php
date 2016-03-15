<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/22/2015
 * Time: 10:31 PM.
 */

//namespace GR\APIBundle\Model\Restriction;

//Esto se debe poner al final de las comprobaciones para q se pueda
// estar seguro q no se sobrepaso
class MaxMoneyRestriction  implements IRestriction,IMoneyCollection
{
    /**
     * @var array
     */
    private $maxMoneyArray;

    /**
     * Cantidad minima de bonos para q sea valida la restriccion.
     *
     * @param int $maxMoney
     */
    public function __construct()
    {
        $this->maxMoneyArray = array();
    }

    /**
     * @param Money $money
     */
    public function addMoney($money)
    {
        $this->maxMoneyArray[$money->getMoneyType()] = $money;
    }

    /**
     * @param string $moneyType
     *
     * @return Money
     */
    public function getMoney($moneyType)
    {
        return  $this->maxMoneyArray[$moneyType];
    }

    /**
     * @param string $moneyType
     */
    public function hasMoney($moneyType)
    {
        return  array_key_exists($moneyType, $this->maxMoneyArray);
    }

    public function getName()
    {
        return 'MaxMoneyRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        /*
         * Si el arreglo no tiene valores entonces la restriccion no se debe evaluar
         * porque no fue seteada y ent debera devolver true
         */
        if (count($this->maxMoneyArray) == 0) {
            return true;
        }
        // TODO: Implement valid() method.
        if ($this->hasMoney($data->getMoneyType())) {
            $maxMoney = $this->getMoney($data->getMoneyType());

            if ($maxMoney->getValue() > $data->totalOfMoneyCurrentTier()) {
                return true;
            }
            $errores[] = 'Ya se llego a la cantidad maxima de dinero posible';

            return false;
        } else {
            $errores[] = 'No se cuenta con esa moneda.';
        }
    }
}
