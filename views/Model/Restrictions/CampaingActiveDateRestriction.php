<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 7/5/2015
 * Time: 10:04 PM.
 */
class CampaingActiveDateRestriction implements   IRestriction
{
    private $amountOfDays;

    /**
     * $value valor con e tenianl cual se calculara la fecha de inicio teniendo en cuenta el tipo de unidad de tiempo q se usara
     * $type unidad de timepo HOURS,DAYS y MONTHS.
     *
     * @param int $value
     * @param int $type
     */
    public function __construct($amountOfDays)
    {
        $this->amountOfDays = $amountOfDays;
    }

    public function getName()
    {
        return 'CampaingActiveDateRestriction';
    }

    public function valid(IDataForRestriccion $data, $errores)
    {
        /*
         * dateCampaingStart se usara para calcular el start date y end date
         */
        $startDate = $data->dateCampaingStart();
        $endDate = $startDate->modify("+$amountOfDays day");

        if ($startDate <= $data->dateToProcess() && $endDate > $data->dateToProcess()) {
            return true;
        }
        //esto no lo veo muy logico pues habria q poner cada capa q fallo porqeu lo hizo
        $errores[] = 'The date out of range.The campaing is inactive';

        return false;
    }

    /**
     * @param DateTime $currentDate
     * @param $value
     * @param $type
     *
     * @return DateTime
     */
    public function buildStartDate($currentDate, $value, $type)
    {
        $result = null;
        switch ($type) {
            case self::HOURS:
                $result = $currentDate->modify("-$value hour");
            case self::DAYS:
                $result = $currentDate->modify("-$value day");
            case self::MONTHS:
                $result = $currentDate->modify("-$value month");
        }

        return $result;
    }
}
