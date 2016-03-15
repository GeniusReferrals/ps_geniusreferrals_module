<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/21/2015
 * Time: 3:21 PM.
 */

//namespace GR\APIBundle\Model\Restriction;

class DateRangeRestriction implements IRestriction
{
    /**
     * @var \DateTime
     */
    protected $startDate;
    /**
     * @var \DateTime
     */
    protected $endDate;

    /**
     * Ambos sdias estan incluidos en la comparacion.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->startDate->setTime(0, 0, 0);
        $this->endDate = $endDate;
        $this->endDate->add(new \DateInterval('1 day'));
        $this->endDate->setTime(0, 0, 0);
    }
    public function getName()
    {
        return 'DateRangeRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        // TODO: Implement valid() method.
//        if($this->intDays > 0 && date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($this->objAdvocate->getCreated()->format('Y-m-d H:i:s').' +'.$this->intDays.' days')))
        if ($this->startDate <= $data->dateToProcess() && $this->endDate > $data->dateToProcess()) {
            return true;
        }
        //esto no lo veo muy logico pues habria q poner cada capa q fallo porqeu lo hizo
        $errores[] = 'The date out of range';

        return false;
    }
}
