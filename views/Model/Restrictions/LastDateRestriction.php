<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 7/5/2015
 * Time: 10:04 PM.
 */
class LastDateRestriction extends  DateRangeRestriction
{
    const HOURS = 0;
    const DAYS = 1;
    const MONTHS = 2;

    /**
     * $value valor con e tenianl cual se calculara la fecha de inicio teniendo en cuenta el tipo de unidad de tiempo q se usara
     * $type unidad de timepo HOURS,DAYS y MONTHS.
     *
     * @param int $value
     * @param int $type
     */
    public function __construct($value, $type)
    {
        $this->endDate = new DateTime('now');
        $tmpDate = new DateTime('now');

        $this->startDate = $this->buildStartDate($tmpDate, $value, $type);
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
