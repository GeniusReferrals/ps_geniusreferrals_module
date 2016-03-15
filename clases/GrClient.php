<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 3/22/2015
 * Time: 2:32 PM.
 */
require_once dirname(__FILE__).'/IGrClient.php';
require_once dirname(__FILE__).'/GrClientException.php';

class GrClient implements IGrClient
{
    private $gr_username;
    private $gr_auth_token;
    private $account_slug;
    private $objGeniusReferralsAPIClient;

    public function __construct($gr_username, $gr_auth_token, $account_slug)
    {
        $this->gr_username = $gr_username;
        $this->gr_auth_token = $gr_auth_token;
        $this->account_slug = $account_slug;
        $this->objGeniusReferralsAPIClient = new \GeniusReferrals\GRPHPAPIClient($this->gr_username, $this->gr_auth_token);
        try {
            //Test authentication
            $this->objGeniusReferralsAPIClient->testAuthentication();
        } catch (\Exception $exc) {
            throw new GrClientException('Connexion error o authentication problem');
        }
    }

    /**
     * Crea el advocate y le setea el tipo de moneda.
     *
     * @param Advocate $advocate
     *
     * @return Advocate|bool
     */
    public function addAdvocate(Advocate $advocate)
    {
        try {
            $arrParams = array(
                'advocate' => array(
                    'name' => $advocate->getAdvocateName(),
                    'lastname' => $advocate->getAdvocateLastname(),
                    'email' => $advocate->getAdvocateEmail(),
                    'payout_threshold' => 150,
                ), );

            $objResponse = $this->objGeniusReferralsAPIClient->postAdvocate($this->account_slug, $arrParams);
            if ($this->objGeniusReferralsAPIClient->getResponseCode() == 201) {
                $arrLocation = $objResponse->getHeader('Location')->raw();
                $strLocation = $arrLocation[0];
                $arrParts = explode('/', $strLocation);
                $strAdvocateToken = end($arrParts);
                $advocate->setStrAdvocateToken($strAdvocateToken);
                //Updating the advocate currency
                $arrParams = array('currency_code' => $advocate->getIsoCode());
                $objResponse = $this->objGeniusReferralsAPIClient->patchAdvocate($this->account_slug, $strAdvocateToken, $arrParams);
                $intResponseCode1 = $this->objGeniusReferralsAPIClient->getResponseCode();
                if ($intResponseCode1 == 204) {
                    return $advocate;
                }
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Inserta el advocate a los usuarios referidos por quien lo refirio.
     *
     * @param Advocate $advocate
     *
     * @return Advocate|bool
     */
    public function addReferredAdvocate(Advocate $advocate)
    {
        $arrParams = array(
            'referral' => array(
                'referred_advocate_token' => $advocate->getStrAdvocateToken(), //the one created when the advocate was registered.
                'referral_origin_slug' => $advocate->getGrAdvocateReferrerReferralOriginSlug(),
                'campaign_slug' => $advocate->getGrAdvocateReferrerCampaignSlug(),
                'http_referer' => $_SERVER['HTTP_REFERER'],
            ),
        );
        $this->objGeniusReferralsAPIClient->postReferral($this->account_slug, $advocate->getGrAdvocateReferrerToken(), $arrParams);

        $intResponseCode = $this->objGeniusReferralsAPIClient->getResponseCode();
        if ($intResponseCode == 201) {
            return $advocate;
        }

        return false;
    }

    /**
     * Hace el llamado seguido addAdvocate y addReferredAdvocate.
     *
     * @param Advocate $advocate
     *
     * @return Advocate|bool
     */
    public function addAdvocateFull(Advocate $advocate)
    {
        if ($advocate = $this->addAdvocate($advocate)) {
            if ($advocate->hasReferrals()) {
                if ($advocate = $this->addReferredAdvocate($advocate)) {
                    return $advocate;
                }
            } else {
                return $advocate;
            }
        }

        return false;
    }

    /**
     * Da el bono en GR al usuario q refiere al $advocate.
     *
     * @param Advocate $advocate
     * @param $amount_of_payments
     * @param $payment_amount
     *
     * @return Bonus|bool
     */
    public function giveBonusInGR(Advocate $advocate, $amount_of_payments = null, $payment_amount = null)
    {
        $arrParams = null;
        try {
            if ($amount_of_payments == null or $payment_amount == null) {
                $arrParams = array(
                    'bonus' => array(
                        'advocate_token' => $advocate->getStrAdvocateToken(), //the advocate who made the payment
                        'reference' => time(), //A reference number, could be the payment id

                    ),
                );
            } else {
                $arrParams = array(
                    'bonus' => array(
                        'advocate_token' => $advocate->getStrAdvocateToken(), //the advocate who made the payment
                        'reference' => time(), //A reference number, could be the payment id
                        'amount_of_payments' => $amount_of_payments,
                        'payment_amount' => $payment_amount,
                    ),
                );
            }
            $objResponse = $this->objGeniusReferralsAPIClient->postBonuses($this->account_slug, $arrParams);
            $intResponseCode = $this->objGeniusReferralsAPIClient->getResponseCode();
            if ($intResponseCode == 201) {
                $arrLocation = $objResponse->getHeader('Location')->raw();
                $strLocation = $arrLocation[0];
                $arrParts = explode('/', $strLocation);
                $bonusId = end($arrParts);
                $strBonus = $this->objGeniusReferralsAPIClient->getBonus($this->account_slug, $bonusId);
                $intResponseCode = $this->objGeniusReferralsAPIClient->getResponseCode();
                if ($intResponseCode == 200) {
                    return Bonus::buildFromJson($strBonus);
                }
            }

            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Busca el advocate en GR dado el token.
     *
     * @param $grAdvocateToken
     *
     * @return Advocate|bool
     */
    public function getAdvocate($grAdvocateToken)
    {
        $strResponse = $this->objGeniusReferralsAPIClient->getAdvocate($this->account_slug, $grAdvocateToken);
        $intResponseCode = $this->objGeniusReferralsAPIClient->getResponseCode();
        if ($intResponseCode == 200) {
            $advocate = Advocate::buildFromJson($strResponse);

            return $advocate;
        }

        return false;
    }

    /**
     * Busca el advocate en GR dado el email.
     *
     * @param $grAdvocateToken
     *
     * @return Advocate|bool
     */
    public function getAdvocateByEmail($grCustomerEmail)
    {
        $strResponse = $this->objGeniusReferralsAPIClient->getAdvocates($this->account_slug, 1, 1, 'email::'.$grCustomerEmail);
        $objResponse = json_decode($strResponse);
        if ($objResponse->data->total == 1) {
            $advocate = new Advocate();
            $advocate->setAdvocateEmail($objResponse->data->results[0]->email);
            $advocate->setAdvocateName($objResponse->data->results[0]->name);
            $advocate->setAdvocateLastname($objResponse->data->results[0]->lastname);
            $advocate->setStrAdvocateToken($objResponse->data->results[0]->token);
            $advocate->setIsoCode($objResponse->data->results[0]->_currency->code);

            return $advocate;
        }

        return false;
    }

    /**
     * Libera el bono de GR
     * $advocate usuario q tiene el bono
     * $bonus  bono.
     *
     * @param Advocate $advocate
     * @param Bonus    $bonus
     *
     * @return bool
     */
    public function redemptionBonus(Advocate $advocate, Bonus $bonus)
    {
        //preparing the data to be sent on the request
        $arrParams = array(
            'redemption_request' => array(
                'advocate_token' => $advocate->getStrAdvocateToken(),
                'request_status_slug' => 'requested',
                'request_action_slug' => 'credit',
                'currency_code' => $bonus->getCurrencyCode(),
                'amount' => $bonus->getAmount(),
                'description' => 'Redeeming as credit',
            ),
        );

        //trying to create a new redemption request for the advocate
        $objResponse = $this->objGeniusReferralsAPIClient->postRedemptionRequest($this->account_slug, $arrParams);
        $intResponseCode = $this->objGeniusReferralsAPIClient->getResponseCode();
        if ($intResponseCode == 201) {
            $arrLocation = $objResponse->getHeader('Location')->raw();
            $strLocation = $arrLocation[0];
            $arrParts = explode('/', $strLocation);
            $redemptionId = end($arrParts);
            // Redemption request successfully created
            $objResponse = $this->objGeniusReferralsAPIClient->patchRedemptionRequestRedemption($this->account_slug, $redemptionId);
            $intResponseCode = $this->objGeniusReferralsAPIClient->getResponseCode();
            if ($intResponseCode == 204) {
                return true;
            }
        }

        return false;
    }
}
