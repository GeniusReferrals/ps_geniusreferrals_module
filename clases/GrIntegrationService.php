<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 3/22/2015
 * Time: 6:07 PM.
 */
require_once dirname(__FILE__).'/GrOrderControl.php';
require_once dirname(__FILE__).'/PrestaShopData.php';
require_once dirname(__FILE__).'/GrClient.php';

/**
 * Me queda el tema de la cantidad de la cantidad de ordenes en BD
 * antes de dar el bono hay q pensar en el modelo
 * Class GrIntegrationService.
 */
class GrIntegrationService
{
    const  WHERE_GIVE_PAYMENT = 'payment';
    const  WHERE_GIVE_CREATE_USER = 'sign-up';
    /**
     * @var IGrClient
     */
    private $grClient;
    /**
     * @var IShopData
     */
    private $shopData;

    /**
     * @param IGrClient $grClient
     * @param IShopData $shopData
     */
    public function __construct($shopData)
    {
        $this->shopData = $shopData;
        $this->grClient = new GrClient($this->shopData->getGrUsername(), $this->shopData->getGrAuthToken(), $this->shopData->getGrAccountSlug());
    }

    public function actionAfterCreateUserInShop(Advocate $advocate)
    {
        //crea el advocate e inserta la moneda
        /**
         * @var Advocate
         */
        if ($advocate = $this->grClient->addAdvocateFull($advocate)) {
            if ($this->shopData->getWhereGiveBonus() == self::WHERE_GIVE_CREATE_USER) {
                /**
                 * @var Bonus
                 */
                if ($bonus = $this->grClient->giveBonusInGR($advocate)) {
                    //El bono tiene el Advocate que recibio el bono

                    //Si existe la moneda del bono en la tienda
                    if ($this->shopData->existCurrency($bonus->getCurrencyCode())) {
                        /**
                         * @var Advocate
                         */
                        $advocateReferrer = $bonus->getAdvocate();
                        if ($this->shopData->CambiarBonosEnTienda()) {
                            /*
                             * Verifacar que la tienda desee cambiar los bonos de Genius referral en la tienda automaticamente
                             * Verificando la existencia del customer
                             */
                            if ($idCustomer = $this->shopData->getCustomerId($advocateReferrer->getAdvocateEmail())) {
                                //Libera el bono de GR
                                if ($this->grClient->redemptionBonus($advocateReferrer, $bonus)) {
                                    //                                    $this->shopData->giveBonusInShop($idCustomer, $bonus, $this->shopData->getShopId());
                                    $this->shopData->giveBonusInShop($bonus, $advocateReferrer->getAdvocateEmail());
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $email usuario q hace la compra
     */
    public function actionAfterPaymentConfirmation($email)
    {
        $amount_of_payments = $this->shopData->getAmountOfPayment() + 1;
        $payment_amount = $this->shopData->getTotal();
        //crea el advocate e inserta la moneda
        if ($this->shopData->getWhereGiveBonus() == self::WHERE_GIVE_PAYMENT) {
            /**
             * @var Advocate
             */
            if ($advocate = $this->grClient->getAdvocateByEmail($email)) {

                /**
                 * @var Bonus
                 */
                if ($bonus = $this->grClient->giveBonusInGR($advocate, $amount_of_payments, $payment_amount)) {

                    //Si existe la moneda del bono en la tienda
                    if ($this->shopData->existCurrency($bonus->getCurrencyCode())) {
                        /**
                         * Esta llamada es inecesaria proque el bono tiene el usuario q lo refirio
                         * $this->grClient->getAdvocate($bonus->getAdvocate()->getStrAdvocateToken())
                         * Despues quitarla.
                         *
                         * @var Advocate
                         */
                        $advocateReferrer = $bonus->getAdvocate();
                        if ($this->shopData->CambiarBonosEnTienda()) {

                            //                    if ($this->shopData->CambiarBonosEnTienda() && $advocateReferrer = $this->grClient->getAdvocate($bonus->getAdvocate()->getStrAdvocateToken())) {
                            /*
                             * Verifacar que la tienda desee cambiar los bonos de Genius referral en la tienda automaticamente
                             * Verificando la existencia del customer
                             */
                            if ($idCustomer = $this->shopData->getCustomerId($advocateReferrer->getAdvocateEmail())) {
                                //Libera el bono de GR
                                if ($this->grClient->redemptionBonus($advocateReferrer, $bonus)) {
                                    //this->shopData->giveBonusInShop($idCustomer, $bonus, $this->shopData->getShopId());
                                    $this->shopData->giveBonusInShop($bonus, $advocateReferrer->getAdvocateEmail());
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
