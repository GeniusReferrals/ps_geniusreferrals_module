<?php

global $smarty;
include '../../config/config.inc.php';
$smarty->display(dirname(__FILE__).'/breadcrumb.tpl');
include '../../header.php';
$smarty->assign(array(
    'GR_USERNAME' => Configuration::get('GR_USERNAME'),
    'GR_TEMPLATESLUG' => Configuration::get('GR_TEMPLATESLUG'),
    'GR_TEMPLATENOTAUTHSLUG' => Configuration::get('GR_TEMPLATENOTAUTHSLUG'),
    'grCustomerEmail' => $context->customer->email,
    'grCustomerName' => $context->customer->firstname,
    'grCustomerLastname' => $context->customer->lastname,
    'grCustomerCurrencyCode' => $context->currency->iso_code,
));
$smarty->display(dirname(__FILE__).'/gr_clients.tpl');
include '../../footer.php';
