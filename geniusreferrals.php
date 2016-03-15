<?php
if ( !defined( '_PS_VERSION_' ) )
    exit;

require_once(dirname(__FILE__).'/clases/GrIntegrationService.php');

class GeniusReferrals extends Module
{
    public function __construct()
    {
        $this->name = 'geniusreferrals';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'Genius Referrals';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l( 'Referrals Programs Made Easy' );
        $this->description = $this->l( 'Increase your customer acquisition and sales with ours refer a friend programs. Take advantage of our low-cost plans. Best price in the market!' );
    }


    public function install()
    {
         return  parent::install()
            && $this->registerHook( 'actionCustomerAccountAdd' )
            && $this->registerHook( 'actionPaymentConfirmation' )
            && $this->registerHook( 'displayOrderConfirmation' )
            && $this->registerHook( 'actionValidateOrder' )
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayNav')
            && $this->registerHook('displayLeftColumn')
            && $this->registerHook('displayRightColumn')
            && $this->registerHook('displayFooter');
    }


    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->_deleteContent())
            return false;
    }

    public function hookDisplayOrderConfirmation($params){
        $shopConfig = new PrestaShopData($this->context->shop->id);
        if($shopConfig->canShowGRPOSInOrderConfirmation()) {
            $this->context->controller->addCSS('http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.css', 'all');
            $this->context->controller->addCSS("http://getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.css", 'all');
            $this->context->controller->addCSS(($this->_path) . 'js/bootstrap-modal/css/bootstrap-modal-bs3patch.css', 'all');
            $this->context->controller->addCSS(($this->_path) . 'js/bootstrap-modal/css/bootstrap-modal.css', 'all');

            $this->context->controller->addJS("http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js", 'all');
            //$this->context->controller->addJS("http://getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.js");
            $this->context->controller->addJS("http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.js");

            $this->context->controller->addJS($this->_path . 'js/bootstrap-modal/js/bootstrap-modalmanager.js', 'all');
            $this->context->controller->addJS($this->_path . 'js/bootstrap-modal/js/bootstrap-modal.js', 'all');
            $this->context->controller->addJS('https://www.geniusreferrals.com/bundles/portal/js/geniusreferrals-api-client_1.0.6.js', 'all');
            $this->context->controller->addJS($this->_path . 'js/pos.js', 'all');

            $urlImagen = ($this->_path) ."js/bootstrap-modal/img/loader.gif";

            $d = array(
                'grUsername' => $shopConfig->getGrUsername(),
                'grTemplateSlug' => $shopConfig->getGrTemplateSlug(),
                'grTemplateSlugConfirmPage' => $shopConfig->getGrTemplateSlugConfirmPage(),
                "grCustomerEmail" => $this->context->customer->email,
                'grCustomerName' => $this->context->customer->firstname,
                'grCustomerLastname' => $this->context->customer->lastname,
                'grCustomerCurrencyCode' => $this->context->currency->iso_code,
                'urlImagen' => $urlImagen
            );
            $this->context->smarty->assign($d);

            return $this->display(__FILE__, 'pos.tpl');
        }
    }

    public function hookDisplayHeader()
    {
        try {
            require_once 'vendor/autoload.php';
            $this->context->controller->addJS('https://www.geniusreferrals.com/bundles/portal/js/geniusreferrals-tool-box_1.0.9.js', 'all');
            $this->context->controller->addJS($this->_path . 'js/g.js', 'all');
        }catch (Exception $ex){
            print_r($ex);
        }
    }

    public function hookActionCustomerAccountAdd($params){
        $error = false;
        require_once 'vendor/autoload.php';
        $advocate_name = $params['newCustomer']->firstname;
        $advocate_lastname = $params['newCustomer']->lastname;
        $advocate_email = $params['newCustomer']->email;
        if(array_key_exists('grCampaignSlug',$_COOKIE)){
            $grAdvocateReferrerCampaignSlug = $_COOKIE['grCampaignSlug'];
            $grAdvocateReferrerReferralOriginSlug = $_COOKIE['grReferralOriginSlug'];
            $grAdvocateReferrerToken = $_COOKIE['grAdvocateReferrerToken'];

            $darBonoEnCreacion = true;
            $grIntegrationService = new GrIntegrationService(new PrestaShopData($this->context->shop->id));

            $advocate = new Advocate($advocate_name,$advocate_lastname,$advocate_email,$this->context->currency->iso_code,$grAdvocateReferrerReferralOriginSlug,$grAdvocateReferrerCampaignSlug,$grAdvocateReferrerToken);
            $grIntegrationService->actionAfterCreateUserInShop($advocate);
            
            $this->deleteCookies();
        }
    }

    public function hookActionPaymentConfirmation($params){
        require_once 'vendor/autoload.php';
        $total = $params['cart']->getOrderTotal(false);
        $grIntegrationService = new GrIntegrationService(new PrestaShopData($this->context->shop->id,$this->context->customer->id,$total));
        /**
         * email del usuario q esta realizando la compra
         */
        $grIntegrationService->actionAfterPaymentConfirmation($this->context->customer->email);
    }

    public function hookDisplayLeftColumn()
    {
        $this->context->smarty->assign(array(
            'placement' => 'left',
        ));
        return $this->display(__FILE__, 'left.tpl');
    }

    public function hookDisplayRightColumn()
    {
        return $this->hookDisplayLeftColumn();
    }

    public function hookDisplayNav($params)
    {
        global $smarty;
        $tpl = 'blockcontact';

        $this->context->smarty->assign(array(
            'menu_name' => $this->l('Referral Program'),
        ));
        return $this->display(__FILE__, $tpl . '.tpl', $this->getCacheId());
    }

    public function getContent()
    {
        if(isset($this->context->controller)){
            
            $this->context->controller->addJS("http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js", 'all');
            $this->context->controller->addJS("http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.js");

            $this->context->controller->addJS($this->_path . 'js/bootstrap-modal/js/bootstrap-modalmanager.js', 'all');
            $this->context->controller->addJS($this->_path . 'js/bootstrap-modal/js/bootstrap-modal.js', 'all');

            $this->context->controller->addCSS(array($this->_path.'css/geniusreferrals.css'));
            $this->context->controller->addCSS(array($this->_path.'css/bootstrap.min.css'));
        }
        $message = '';
        if (Tools::isSubmit('submit_admin_gr'))
            $message = $this->_saveContent();

        $this->_displayContent($message);

        return $this->display(__FILE__, 'settings.tpl');
    }


    private function _saveContent()
    {
        $message = '';

        if (Tools::getValue('GENIUSREFERRALS_CONFIG')) {
            $config = Tools::getValue('GENIUSREFERRALS_CONFIG');
            $obj = json_decode($config);
            ConfigurationCore::updateValue("GR_USERNAME", $obj->grUsername);
            ConfigurationCore::updateValue("GR_AUTH_TOKEN", $obj->grAuthToken);
            ConfigurationCore::updateValue("GR_TEMPLATESLUG", $obj->grTemplateSlug);
            ConfigurationCore::updateValue("GR_TEMPLATENOTAUTHSLUG", $obj->grTemplateNotAuthSlug);
            ConfigurationCore::updateValue("GR_TEMPLATESLUG_CONFIRMPAGE", $obj->grTemplateSlugConfirmPage);
            ConfigurationCore::updateValue("GR_ACCOUNT_SLUG", $obj->grAccountSlug);
            ConfigurationCore::updateValue("GR_REDEEMAUTO_INTOCREDIT", $obj->grRedeemAutoIntoCredit);
            ConfigurationCore::updateValue("GR_WHERE_GIVE_CREDIT", $obj->grWhereToGiveCredit);
            ConfigurationCore::updateValue("GR_SHOWGRPOS", $obj->grShowGrPOS);

            Configuration::updateValue('GENIUSREFERRALS_CONFIG',$config);
            $message = $this->displayConfirmation($this->l('Your settings have been saved'));
        }
        else
            $message = $this->displayError($this->l('There was an error while saving your settings'));

        return $message;
    }

    private function _displayContent($message)
    {
        $this->context->smarty->assign(array(
            'message' => $message,
            'GENIUSREFERRALS_CONFIG' => Configuration::get('GENIUSREFERRALS_CONFIG')
        ));
    }

    private function _deleteContent()
    {
        Configuration::deleteByName('GENIUSREFERRALS_CONFIG');
        Configuration::deleteByName('GR_USERNAME');
        Configuration::deleteByName('GR_AUTH_TOKEN');
        Configuration::deleteByName('GR_TEMPLATESLUG');
        Configuration::deleteByName('GR_TEMPLATENOTAUTHSLUG');
        Configuration::deleteByName('GR_TEMPLATESLUG_CONFIRMPAGE');
        Configuration::deleteByName('GR_ACCOUNT_SLUG');
        Configuration::deleteByName('GR_REDEEMAUTO_INTOCREDIT');
        Configuration::deleteByName('GR_WHERE_GIVE_CREDIT');
        Configuration::deleteByName('GR_SHOWGRPOS');
        return true;
    }

    public function deleteCookies()
    {
        unset($_COOKIE['grCampaignSlug']);
        unset($_COOKIE['grReferralOriginSlug']);
        unset($_COOKIE['grAdvocateReferrerToken']);
        
        $strDomain = $this->getDomain(); 
         
        setcookie('grCampaignSlug', null, -1, "/", $strDomain);
        setcookie('grReferralOriginSlug', null, -1, "/", $strDomain);
        setcookie('grAdvocateReferrerToken', null, -1, "/", $strDomain);
    }
    
    public function getDomain()
    {
        $arrParts = explode('.', _PS_BASE_URL_); 
        $strDomain = '';
        if(count($arrParts) == 4)
        {
            $strDomain .= '.' . $arrParts[count($arrParts) -3] . '.' . $arrParts[count($arrParts) -2] . '.' . $arrParts[count($arrParts) -1] ; 
        }
        else 
        {
            $strDomain .= '.' . $arrParts[count($arrParts) -2] . '.' . $arrParts[count($arrParts) -1] ; 
        }
        
        return $strDomain;
    }

}