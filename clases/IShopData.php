<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 3/22/2015
 * Time: 2:29 PM.
 */
interface IShopData
{
    public function getCustomerId($customerEmail);
    public function CambiarBonosEnTienda();
    public function getGrUsername();
    public function getGrAuthToken();
    public function getGrAccountSlug();
    public function giveBonusInShop(Bonus $bonus, $customerEmail);
    public function getShopId();
    public function setShopId($shop_id);
    public function getWhereGiveBonus();
    public function existCurrency($currencyCode);
    public function getAmountOfPayment();
    public function canShowGRPOSInOrderConfirmation();
    public function expirationDayForBonus();
    /**
     * @return mixed totala apagar por la orden
     */
    public function getTotal();
}
