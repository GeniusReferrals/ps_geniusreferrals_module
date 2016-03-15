<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/20/2015
 * Time: 10:38 PM.
 */

//namespace GR\APIBundle\Model;

class Campaing
{
    private $tierList;
    /**
     * @var \GR\APIBundle\Model\Tier
     */
    private $tier;
    public function __construct()
    {
        $this->tierList = array();
        $this->tier = new Tier();

        $this->restrictionList = array();
    }

    public function setTierForAllCampaing($tier)
    {
        $this->tier = $tier;
    }

    /**
     * Restricciones de toda la campanna
     * ShortCut to tier->addRestriction.
     *
     * @param $restriction
     */
    public function addRestriction($restriction)
    {
        $tier = new Tier();
        $tier->addRestriction($restriction);
        $this->restrictionList[] = $tier;
        //$this->tier->addRestriction($restriction);
    }

    /**
     * Aki se debe de organizar para q las capaz sean procesadas en el orden q se requiere
     * o organizarlos para q en el valid se organize.
     *
     * @param $tier
     */
    public function addTier($tier)
    {
        $this->tierList[] = $tier;
    }

    /**
     * Aki lo mas probable es q se deba devolber un AwardValur en vez de un tier.
     *
     * @param IDataForRestriccion $data
     * @param $errores
     *
     * @return \GR\APIBundle\Model\Tier
     */
    public function valid(IDataForRestriccion $data, $errores)
    {
        $data->actualizarDatosCapa($this->tier);
//        if($this->tier->valid($data,$errores)){
          if ($this->validRestrictions($data, $errores)) {
              /**
 * @var \GR\APIBundle\Model\Tier
 */
            ///como comprueba que si se da un bono para el referino no exceda las condiciones de la capa
            //una persona es referido una sola vez por lo q lo de arriba no tiene ssentido no tiene sentido
            ///este problema existe porque el valor solo puede contener usuario, tipo de moneda,cantidad
            //totalOfMoneyCurrentTier() no puede devolver un entero debe devolver un objeto de valor con los campos arriba sitados
            ///aki el usuario es irrelevante lo q se debe saber si es referente o referido.

            //el otro tema es como hacer una camparacion con el bono q se pretende otorgar y las restricciones globales para
            //asegurar q estas se cumplan con el bono dado

//            ****** Las moneda si hay q tenerlas en cuenta --***** pensaar es eso
            //el tipo de dinero seguira siendo un problema porqu todavia no se ha definido como llega esto a la clase
            //IDataForRestriccion
            foreach ($this->tierList as $tier) {
                $data->actualizarDatosCapa($tier);
                if ($tier->valid($data, $errores)) {
                    $tempData = new NewDataForRestriccion($data, $tier);
                    //aki se vuelve a validar a campanna pero ya con los datos como si se hubiera dado el bono a ver si aun
                    //se cumple la restriccion de la campanna.
                    if ($this->validRestrictions($tempData, $errores)) {
                        return $tier;
                    }
                }
            }
          }

        return;
    }

    public function validRestrictions(IDataForRestriccion $data, $errores)
    {
        //Aki hay un detalle y es q la clase Tier no se un Tier porqeu no se usa para nada el AwardValue
        // solo se usa porque tiene su arreglo de reglas pero su uso no queda muy claro aki
        //por ahora se queda asi
        foreach ($this->restrictionList as $tier) {
            $data->actualizarDatosCapa($tier);
            if (!$tier->valid($data, $errores)) {
                return false;
            }
        }

        return true;
    }
}
