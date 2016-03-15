<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 7/6/2015
 * Time: 11:44 PM.
 */
interface IMoneyCollection
{
    /**
     * @param Money $money
     */
    public function addMoney($money);
    /**
     * @param int $moneyType
     *
     * @return bool
     */
    public function hasMoney($moneyType);
    /**
     * @param int $moneyType
     *
     * @return Money
     */
    public function getMoney($moneyType);
}
