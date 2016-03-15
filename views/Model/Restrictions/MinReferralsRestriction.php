<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/22/2015
 * Time: 10:31 PM.
 */

//namespace GR\APIBundle\Model\Restriction;

class MinReferralsRestriction  implements IRestriction
{
    /**
     * @var int
     */
    private $referrals;

    /**
     * Cantidad minima de referidos para q sea valida la restriccion.
     *
     * @param int $referrals
     */
    public function __construct($referrals)
    {
        $this->referrals = $referrals;
    }
    public function getName()
    {
        return 'MinReferralsRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        // TODO: Implement valid() method.
        if ($this->referrals <= $data->countReferralsForCurrentTier()) {
            return true;
        }
        $errores[] = 'No existe la cantidad minima de referidos requeridos';

        return false;
    }
}
