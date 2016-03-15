<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 6/21/2015
 * Time: 9:56 AM
 */

//namespace GR\APIBundle\Model;


class ObjectoJSON {
    public function __construct(){

    }
    public function create($json){
        $o = json_decode($json);
        $campana = new Campaing();
        for($o->tiers as $tier){
            /**
             * @var Tier $tierObj
             */
            $tierObj =  $this->createTier($tier);
            foreach($tier->amount_client  as $currencie){
                $awardObj = new Money();
                $awardObj->setValue($currencie->amount);
                //El valor del q se le otorga al referido se puede dejar aki
                //$awardObj->setRefererValue();
                $awardObj->setMoneyType($currencie->id);
                $tierObj->addRefererAward($awardObj);
            }
        }
        //
       /**
        * Configurando Restricciones de fechas
        */
        if($o->date_range==0){
            //En este caso  0 sera last

            $restriction = new LastDateRestriction($o->in_last->amount,$o->in_last->type);
            $campana->addRestriction($restriction);

        }elseif($o->date_range==1){
            //En este caso  1 sera beetwen

            $startDate = new DateTime();
            $startDate->setDate($startDate->format("Y"),$o->between->first_month,$o->between->first_month_day);

            $endDate = new DateTime();
            $endDate->setDate($endDate->format("Y"),$o->between->second_month,$o->between->second_month_day);
            /**
             * Debido a q no se cuenta con el anno para conformar la feha completaen el caso de q el mes inicia sea mayor q el final
             * ent se le adicionara un anno para q el rango de fechas este correcto
             */
            if($startDate>$endDate) $endDate->modify('+1 year');
            $restriction = new DateRangeRestriction($startDate,$endDate);
            $tierObj->addRestriction($restriction);
        }

        /**
         * Configurando Restriccion Days
         * How many days after sign up will the campaign be active.
         */
        if($o->days){
            $restriction = new CampaingActiveDateRestriction($o->days);
            $tierObj->addRestriction($restriction);
        }

        /**
         * TODAVIA CON DUDAS ***************************************************
         * Este tb ahyq  explicarlo
         *
         * Campaign 1
            Tiers:
            0+ bonuses = $500
            1+ bonuses = $1000
            Restrictions:
            Amount of payments: 2
         * Configurando Restriccion Payments
         */
        if($o->payments){
            $restriction = new AmountOfPaymentRestriction($o->payments);
            $tierObj->addRestriction($restriction);
        }

        /**         *
         * Configurando Restriccion payment_min_value
         */
        if($o->payment_min_value){
            $restriction = new MinPaymentRestriction();
            $this->loadMoneyArray($restriction,$o->payment_min_value);
            $tierObj->addRestriction($restriction);
        }

        /**
         * SIN HACER
         * Minimum value to give to the advocate as a bonus
         * The minimum value to give as a bonus every time a client makes a payment.
         * ESTO NO LE VEO MUCHO SENTIDO YA CUANDO SE ANALIZA PARA PROGRAMARLO Y LE VEO EL PROBLEMA DE Q HABRIA Q ESTARR
         * ALMACENEDO POR DONDE VA LA SUMA DE DINERO Q SE DEBIA OTORGAR A UN CLIENTE PARA UNA VEZ Q LA SOBREPASE DARLE EL BONO
         * CREO Q ESTO NO ES NECESARIO PROQEU SE PODRIA MODELAR DE OTRA FORMA SIN ENTRAR EN EL TEMA DE ARRIBA
         * Configurando Restriccion min_advocate_bonus
         */
        if($o->payments){
            $restriction = new AmountOfPaymentRestriction($o->payments);
            $tierObj->addRestriction($restriction);
        }

        /**
         * Maximum value to give to the advocate as a bonus
         * The maximum value to give as a bonus every time a client makes a payment.
         * ESTO TAMPOCO TIENE SENTIDO PORQEU ESTA PENSADO PARA UN SOLO  PAGO PARA ESO sE PONE EN EL TIER
         * VALORES MENORES Y RESUELTO EL PROBLEMA
         * Configurando Restriccion max_advocate_bonus
         */
        if($o->max_advocate_bonus){
            $restriction = new MaxMoneyRestriction();//ESTA CLASE ES PARA EL TOTAL NO PARA UN SON BONO
            $this->loadMoneyArray($restriction,$o->max_advocate_bonus);
            $tierObj->addRestriction($restriction);
        }


        /**
         * The maximum value the advocate can receive. Includes all bonuses received among all referred clients
         * Configurando Restriccion max_advocate
         */
        if($o->max_advocate){
            $restriction = new MaxMoneyRestriction();
            $this->loadMoneyArray($restriction,$o->max_advocate);
            $tierObj->addRestriction($restriction);
        }

    }
    public function createTier($tier){
        $tierObj = new Tier();
        if($tier->tier_type==0){
            //En este caso seria Referrals tier_type=0
            $tierObj->setType(Tier::REFERRALS);

            /**
             * Configurando Restricciones minimo de referidos
             */
            if($tier->bonus_calculation==0){
                //En este caso seria Porcentaje 0
                //Numero minimo de referidos para q la restriccion sea valida
                $restriction = new MinReferralsRestriction($tier->percentage->amount);
                $tierObj->addRestriction($restriction);

            }elseif($tier->bonus_calculation==1){
                //En este caso seria un valor Fijo
                //Numero minimo de referidos para q la restriccion sea valida
                $restriction = new MinReferralsRestriction($tier->fixed->amount);
                $tierObj->addRestriction($restriction);
            }

        }elseif($tier->tier_type==1){
            $tierObj->setType(Tier::BONUS);
            /**
             * Configurando Restricciones minimo de bonos
             */
            if($tier->bonus_calculation==0){
                //En este caso seria Porcentaje 0
                //Numero minimo de referidos para q la restriccion sea valida
                $restriction = new MinBonusRestriction($tier->percentage->amount);
                $tierObj->addRestriction($restriction);

            }elseif($tier->bonus_calculation==1){
                //En este caso seria un valor Fijo
                //Numero minimo de referidos para q la restriccion sea valida
                $restriction = new MinBonusRestriction($tier->fixed->amount);
                $tierObj->addRestriction($restriction);
            }
        }
        /**
         * Configurando Restricciones de fechas
         */
        if($tier->date_range==0){
            //En este caso  0 sera last

            $restriction = new LastDateRestriction($tier->in_last->amount,$tier->in_last->type);
            $tierObj->addRestriction($restriction);

        }elseif($tier->date_range==1){
            //En este caso  1 sera beetwen

            $startDate = new DateTime();
            $startDate->setDate($startDate->format("Y"),$tier->between->first_month,$tier->between->first_month_day);

            $endDate = new DateTime();
            $endDate->setDate($endDate->format("Y"),$tier->between->second_month,$tier->between->second_month_day);
            /**
             * Debido a q no se cuenta con el anno para conformar la feha completaen el caso de q el mes inicia sea mayor q el final
             * ent se le adicionara un anno para q el rango de fechas este correcto
             */
            if($startDate>$endDate) $endDate->modify('+1 year');
            $restriction = new DateRangeRestriction($startDate,$endDate);
            $tierObj->addRestriction($restriction);
        }

        $awardValues = $this->createAwardValues($tier);
        $tierObj->setAwardValues($awardValues);



        return $tierObj;
    }

    /**
     * Esto devolvera un AwardValues uno por cada tipo de moneda
     * La cantidad de dinero dada a un usuario referido es fija siemrpe pues no hay  de dodne sacar porcientos
     * @param $tier
     */
    public function createAwardValues($tier){
        $awardValuesObj = new AwardValues();

        if($tier->bonus_calculation==0){
            //En este caso seria Porcentaje 0
            $awardValuesObj->setMeasureType(AwardValues::PERCENTAGE);
            //Creando el Award
            $awardObj = new Money();
            $awardObj->setValue($tier->percentage->percentage);
            $awardValuesObj->addAward($awardObj);
            //El valor del q se le otorga al referido se puede dejar aki
            //$awardObj->setRefererValue();
            //$awardObj->setMoneyType();

        }elseif($tier->bonus_calculation==1){
            //En este caso seria un valor Fijo
            $awardValuesObj->setMeasureType(AwardValue::FIXED);
            foreach($tier->fixed->currencies  as $currencie){
                $awardObj = new Money();
                $awardObj->setValue($currencie->amount);
                //El valor del q se le otorga al referido se puede dejar aki
                //$awardObj->setRefererValue();
                $awardObj->setMoneyType($currencie->id);
                $awardValuesObj->addAward($awardObj);
            }
        }


        return $awardValuesObj;
//        foreach($tier->amount_client as $amount){
//            $awardObj->setReferralsValue();
//            //El valor del q se le otorga al referido se puede dejar aki
//            $awardObj->setRefererValue();
//            $awardObj->setMoneyType();
//        }

    }

    /**
     * Carga el $collection con los datos de $moneyArray
     * [{"amount":99,"id":"39"},{"amount":80,"id":"40"}]
     * @param IMoneyCollection $collection
     * @param $moneyArray
     */
    public function loadMoneyArray(IMoneyCollection $collection,$moneyArray){
        foreach($moneyArray  as $currencie) {
            //si amount es null ent el valor no fue puesto por lo tanto eso no se usa
            if ($currencie->amount) {
                $awardObj = new Money();
                $awardObj->setValue($currencie->amount);
                $awardObj->setMoneyType($currencie->id);
                $collection->addMoney($awardObj);
            }
        }
    }
} 