<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/22/2015
 * Time: 10:31 PM.
 */

//namespace GR\APIBundle\Model\Restriction;

class MinBonusRestriction  implements IRestriction
{
    /**
     * @var int
     */
    private $bonus;

    /**
     * Cantidad minima de bonos para q sea valida la restriccion.
     *
     * @param int $referrals
     */
    public function __construct($bonus)
    {
        $this->bonus = $bonus;
    }
    public function getName()
    {
        return 'MinReferralsRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        // TODO: Implement valid() method.
        if ($this->bonus <= $data->countBonusForCurrentTier()) {
            return true;
        }
        $errores[] = 'No existe la cantidad minima de bonos requeridos';

        return false;
    }
}
