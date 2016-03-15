<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 3/22/2015
 * Time: 2:27 PM.
 */
interface IGrClient
{
    public function addAdvocate(Advocate $advocate);
    public function addAdvocateFull(Advocate $advocate);
    //public function giveBonusInGR(Advocate $advocate);
    public function giveBonusInGR(Advocate $advocate, $amount_of_payments, $payment_amount);
    public function getAdvocate($grAdvocateToken);
    public function redemptionBonus(Advocate $advocate, Bonus $bonus);
    public function getAdvocateByEmail($grCustomerEmail);
}
class Bonus
{
    public $currencyCode;
    public $amount;
    public $advocate;

    public function __construct($amount, $currencyCode)
    {
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public static function buildFromJson($jsonBonus)
    {
        $objBonus = json_decode($jsonBonus);
        $bonus = new self($objBonus->data->amount, $objBonus->data->_currency->code);
        $advocate = new Advocate($objBonus->data->_advocate->name, $objBonus->data->_advocate->lastname, $objBonus->data->_advocate->email, $objBonus->data->_advocate->_currency->code);
        $advocate->setStrAdvocateToken($objBonus->data->_advocate->token);
        $bonus->setAdvocate($advocate);

        return $bonus;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param mixed $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return Advocate
     */
    public function getAdvocate()
    {
        return $this->advocate;
    }

    /**
     * @param mixed $advocate
     */
    public function setAdvocate($advocate)
    {
        $this->advocate = $advocate;
    }
}
class Advocate
{
    private $advocate_name;
    private $advocate_lastname;
    private $advocate_email;
    private $isoCode;
    private $strAdvocateToken;

    private $grAdvocateReferrerReferralOriginSlug;
    private $grAdvocateReferrerCampaignSlug;
    private $grAdvocateReferrerToken;

    public static function buildFromJson($jsonAdvocate)
    {
        $objAdvocate = json_decode($jsonAdvocate);
        $advocate = new self($objAdvocate->data->name, $objAdvocate->data->lastname, $objAdvocate->data->email, $objAdvocate->data->_currency->code);

        return $advocate;
    }
    public function __construct($advocate_name, $advocate_lastname, $advocate_email, $isoCode, $grAdvocateReferrerReferralOriginSlug = null, $grAdvocateReferrerCampaignSlug = null, $grAdvocateReferrerToken = null)
    {
        $this->advocate_email = $advocate_email;
        $this->advocate_lastname = $advocate_lastname;
        $this->advocate_name = $advocate_name;
        $this->isoCode = $isoCode;
        $this->grAdvocateReferrerReferralOriginSlug = $grAdvocateReferrerReferralOriginSlug;
        $this->grAdvocateReferrerCampaignSlug = $grAdvocateReferrerCampaignSlug;
        $this->grAdvocateReferrerToken = $grAdvocateReferrerToken;
    }

    /**
     * @return mixed
     */
    public function getAdvocateEmail()
    {
        return $this->advocate_email;
    }

    /**
     * @param mixed $advocate_email
     */
    public function setAdvocateEmail($advocate_email)
    {
        $this->advocate_email = $advocate_email;
    }

    /**
     * @return mixed
     */
    public function getAdvocateLastname()
    {
        return $this->advocate_lastname;
    }

    /**
     * @param mixed $advocate_lastname
     */
    public function setAdvocateLastname($advocate_lastname)
    {
        $this->advocate_lastname = $advocate_lastname;
    }

    /**
     * @return mixed
     */
    public function getAdvocateName()
    {
        return $this->advocate_name;
    }

    /**
     * @param mixed $advocate_name
     */
    public function setAdvocateName($advocate_name)
    {
        $this->advocate_name = $advocate_name;
    }

    /**
     * @return mixed
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * @param mixed $isoCode
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
    }

    /**
     * @return mixed
     */
    public function getStrAdvocateToken()
    {
        return $this->strAdvocateToken;
    }

    /**
     * @param mixed $strAdvocateToken
     */
    public function setStrAdvocateToken($strAdvocateToken)
    {
        $this->strAdvocateToken = $strAdvocateToken;
    }

    /**
     */
    public function getGrAdvocateReferrerCampaignSlug()
    {
        return $this->grAdvocateReferrerCampaignSlug;
    }

    /**
     * @param null $grAdvocateReferrerCampaignSlug
     */
    public function setGrAdvocateReferrerCampaignSlug($grAdvocateReferrerCampaignSlug)
    {
        $this->grAdvocateReferrerCampaignSlug = $grAdvocateReferrerCampaignSlug;
    }

    /**
     */
    public function getGrAdvocateReferrerReferralOriginSlug()
    {
        return $this->grAdvocateReferrerReferralOriginSlug;
    }

    /**
     * @param null $grAdvocateReferrerReferralOriginSlug
     */
    public function setGrAdvocateReferrerReferralOriginSlug($grAdvocateReferrerReferralOriginSlug)
    {
        $this->grAdvocateReferrerReferralOriginSlug = $grAdvocateReferrerReferralOriginSlug;
    }

    public function hasReferrals()
    {
        //si tiene referidos entonces es true sino false
        if ($this->grAdvocateReferrerReferralOriginSlug && $this->grAdvocateReferrerCampaignSlug) {
            return true;
        }

        return false;
    }

    /**
     */
    public function getGrAdvocateReferrerToken()
    {
        return $this->grAdvocateReferrerToken;
    }

    /**
     * @param null $grAdvocateReferrerToken
     */
    public function setGrAdvocateReferrerToken($grAdvocateReferrerToken)
    {
        $this->grAdvocateReferrerToken = $grAdvocateReferrerToken;
    }
}
