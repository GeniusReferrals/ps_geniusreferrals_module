<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 3/22/2015
 * Time: 2:33 PM.
 */
require_once dirname(__FILE__).'/IShopData.php';

class PrestaShopData implements IShopData
{
    private $shop_id;
    private $customer_id;
    private $totalPay;
    private $bonusName;
    private $bonusDescription;

    /**
     * @return mixed
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * @param mixed $shop_id
     */
    public function setShopId($shop_id)
    {
        $this->shop_id = $shop_id;
    }

    public function __construct($shop_id, $customer_id = null, $totalPay = null)
    {
        $this->shop_id = $shop_id;
        $this->customer_id = $customer_id;
        $this->totalPay = $totalPay;
    }

    public function getCustomerId($customerEmail)
    {
        return CustomerCore::customerExists($customerEmail, true, true);
    }

    /**
     * Devuelve el valor de la configuracion elegida para el cambio de bonos
     * true si permite cambiar los bonos de forma automatica en la tienda
     * false si no permite cambiar los bonos de forma automatica en la tienda.
     *
     * @return bool
     */
    public function CambiarBonosEnTienda()
    {
        return Configuration::get('GR_REDEEMAUTO_INTOCREDIT');
    }

    public function getGrUsername()
    {
        return Configuration::get('GR_USERNAME');
    }

    public function getGrAuthToken()
    {
        return Configuration::get('GR_AUTH_TOKEN');
    }

    public function getGrAccountSlug()
    {
        return Configuration::get('GR_ACCOUNT_SLUG');
    }

    public function getWhereGiveBonus()
    {
        return Configuration::get('GR_WHERE_GIVE_CREDIT');
    }

    public function getGrTemplateSlug()
    {
        return Configuration::get('GR_TEMPLATESLUG');
    }

    public function getGrTemplateNotAuthSlug()
    {
        return Configuration::get('GR_TEMPLATENOTAUTHSLUG');
    }

    public function getGrTemplateSlugConfirmPage()
    {
        return Configuration::get('GR_TEMPLATESLUG_CONFIRMPAGE');
    }

    /**
     * Devuelve si se quiere mostrar un POPUP donde se muestre un POS de genius referrals.
     *
     * @return bool
     */
    public function canShowGRPOSInOrderConfirmation()
    {
        return Configuration::get('GR_SHOWGRPOS');
    }

    /**
     * @return mixed
     */
    public function expirationDayForBonus()
    {
        return 30;
    }

    /**
     * @return mixed
     */
    public function getBonusDescription()
    {
        return $this->bonusDescription;
    }

    /**
     * @param mixed $bonusDescription
     */
    public function setBonusDescription($bonusDescription)
    {
        $this->bonusDescription = $bonusDescription;
    }

    /**
     * @return mixed
     */
    public function getBonusName()
    {
        return $this->bonusName;
    }

    /**
     * @param mixed $bonusName
     */
    public function setBonusName($bonusName)
    {
        $this->bonusName = $bonusName;
    }

///comprobar q si no se da el bono esto tampoco se hace
    public function giveBonusInShop(Bonus $bonus, $customerEmail)
    {
        $arr = CustomerCore::getCustomersByEmail($customerEmail);
        $id_currency = CurrencyCore::getIdByIsoCode($bonus->getCurrencyCode(), $this->getShopId());//id_shop  Context::getContext()->shop->id
        $cart_rule = new CartRule();
        $cart_rule->name[Configuration::get('PS_LANG_DEFAULT')] = 'Referral Bonus ['.$arr[0]['email'].']';
        $cart_rule->description = 'Rule created by the Genius Referrals Module for user: '.$arr[0]['firstname'];
        $cart_rule->id_customer = $arr[0]['id_customer'];
        $cart_rule->free_shipping = false;
        $cart_rule->quantity = 1;
        $cart_rule->quantity_per_user = 1;
        $cart_rule->minimum_amount_currency = 0;
        $cart_rule->reduction_currency = $id_currency;
        $cart_rule->reduction_amount = $bonus->getAmount();
        $cart_rule->highlight = 1;
        $cart_rule->date_from = date('Y-m-d H:i:s', time());
        $dayCount = $this->expirationDayForBonus();
        $cart_rule->date_to = date('Y-m-d H:i:s', time() + 24 * 3600 * $dayCount);
        $cart_rule->active = 1;
        $cart_rule->add();
    }

    /**
     * Salva el orden control en la BD.
     *
     * @param GrOrderControl $grOrderControl
     */
    public function saveOrderControl(GrOrderControl $grOrderControl)
    {
        $grOrderControl->save();
    }

    /**
     * Devuelve el id y el id_order el id se usara para borrar y el id_order para saber el monto de la orden.
     *
     * @param $id_costumer
     *
     * @return mixed
     */
    public function getOrdersToRedention($id_costumer)
    {
        $orders = Db::getInstance()->getValue('SELECT id,id_order FROM `'._DB_PREFIX_.'geniusreferrals_orders_control` gro
        where gro.change_state=1 and gro.id_costumer='.$id_costumer);

        return $orders;
    }

    /**
     * Actualiza la orden una vez q fue usada.
     *
     * @param $id
     */
    public function redentionOrder($id)
    {
        Db::getInstance()->update('geniusreferrals_orders_control', array('change_state' => 0), "id = $id");
    }

    /**
     * @return bool
     */
    public function existCurrency($currencyCode)
    {
        return CurrencyCore::exists($currencyCode, null, $this->getShopId());
    }

    /**
     * Get order history.
     *
     * @param int $id_customer Customer id
     *
     * @return array PaymentAmount for a customer
     */
    public function getAmountOfPayment()
    {
        $sql = 'SELECT COUNT(distinct o.`id_order`) orden_pagada
        FROM `'._DB_PREFIX_.'orders` o
        LEFT JOIN `'._DB_PREFIX_.'order_history` oh ON o.`id_order` = oh.`id_order`
        LEFT JOIN `'._DB_PREFIX_.'order_state` os ON os.`id_order_state` = oh.`id_order_state`
        LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON os.`id_order_state` = osl.`id_order_state`
        WHERE  os.paid = 1 and o.`id_customer`='.$this->customer_id;
        $result = Db::getInstance()->executeS($sql);

        return $result[0][orden_pagada];
    }

    /**
     * @return mixed totala apagar por la orden
     */
    public function getTotal()
    {
        return $this->totalPay;
    }
}
