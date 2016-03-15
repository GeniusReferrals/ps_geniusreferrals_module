<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/20/2015
 * Time: 10:49 PM.
 */

//namespace GR\APIBundle\Model;

class ProcesadorCampanas
{
    private $errores;
    private $dataForRestriccion;
    public function __construct(IDataForRestriccion $data)
    {
        $this->errores = array();
        $this->dataForRestriccion = $data;
    }

    /**
     * @param $json
     *
     * @return Tier
     */
    public function processCampaing($json)
    {
        $objJson = new ObjectoJSON();
        /**
         * @var \GR\APIBundle\Model\Campaing
         */
        $campaing = $objJson->create($json);

        return $campaing->valid($this->dataForRestriccion, $this->errores);
    }
    public function hasError()
    {
        return count($this->errores) != 0;
    }
    public function getErrors()
    {
        return $this->errores;
    }
}
