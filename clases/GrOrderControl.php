
<?php
/**
 * Created by PhpStorm.
 * User: Yunior
 * Date: 3/23/2015
 * Time: 9:25 PM.
 */
class GrOrderControl  extends ObjectModel
{
    public $id;
    public $id_costumer;
    public $id_order;
    public $change_state;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'geniusreferrals_orders_control', 'primary' => 'id', 'multilang' => false,
        'fields' => array(
            'id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_costumer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'change_state' => array('type' => self::TYPE_BOOL),
        ),
    );
}
