<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/25/2015
 * Time: 10:56 PM.
 */

//use GR\APIBundle\Model\ObjectoJSON;

require_once 'AwardValue.php';
require_once 'IDataForRestriccion.php';
/**
 * Restriciones.
 */
require_once 'Restrictions/IRestriction.php';
require_once 'Restrictions/AmountOfPaymentRestriction.php';
require_once 'Restrictions/DateRangeRestriction.php';
require_once 'Restrictions/MaxMoneyRestriction.php';
require_once 'Restrictions/MinBonusRestriction.php';
require_once 'Restrictions/MinReferralsRestriction.php';

require_once 'Campaing.php';
require_once 'DataForRestriccion.php';
require_once 'NewDataForRestriccion.php';
require_once 'Tier.php';
require_once 'CampaingProcessor.php';
require_once 'ObjectoJSON.php';

//url    http://www.transportadora.dev/modules/geniusreferrals/views/Model/index.php

$json = '{"tiers":[{"tier_type":0,"bonus_calculation":1,"percentage":{"amount":null,"percentage":null},"fixed":{"amount":1,"currencies":[{"amount":3000,"id":"39"},{"amount":7000,"id":"40"}]},"date_range":null,"in_last":{"amount":null,"type":null},"between":{"first_month":null,"first_month_day":null,"second_month":null,"second_month_day":null}},{"tier_type":1,"bonus_calculation":0,"percentage":{"amount":2,"percentage":15},"fixed":{"amount":null,"currencies":[]},"date_range":null,"in_last":{"amount":null,"type":null},"between":{"first_month":null,"first_month_day":null,"second_month":null,"second_month_day":null}},{"tier_type":0,"bonus_calculation":1,"percentage":{"amount":null,"percentage":null},"fixed":{"amount":30,"currencies":[{"amount":500,"id":"39"},{"amount":400,"id":"40"}]},"date_range":1,"in_last":{"amount":null,"type":null},"between":{"first_month":1,"first_month_day":1,"second_month":6,"second_month_day":30}},{"tier_type":0,"bonus_calculation":1,"percentage":{"amount":null,"percentage":null},"fixed":{"amount":30,"currencies":[{"amount":500,"id":"39"},{"amount":400,"id":"40"}]},"date_range":1,"in_last":{"amount":null,"type":null},"between":{"first_month":7,"first_month_day":1,"second_month":12,"second_month_day":30}}],"days":7,"payments":4,"payment_min_value":[{"amount":99,"id":"39"},{"amount":80,"id":"40"}],"min_advocate_bonus":[{"amount":100,"id":"39"},{"amount":80,"id":"40"}],"max_advocate_bonus":[{"amount":10000,"id":"39"},{"amount":10000,"id":"40"}],"amount_client":[{"amount":100,"id":"39"},{"amount":80,"id":"40"}],"max_advocate":[{"amount":10,"id":"39"},{"amount":10,"id":"40"}],"date_range":1,"in_last":{"amount":null,"type":null},"between":{"first_month":10,"first_month_day":1,"second_month":12,"second_month_day":30}}';
$pp = new ObjectoJSON();
$pp->create($json);
