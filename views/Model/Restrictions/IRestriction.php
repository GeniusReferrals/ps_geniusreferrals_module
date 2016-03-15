<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/20/2015
 * Time: 10:02 PM.
 */

//namespace GR\APIBundle\Model\Restriction;

interface IRestriction
{
    public function getName();

    public function valid(IDataForRestriccion $data, $errores);
}
