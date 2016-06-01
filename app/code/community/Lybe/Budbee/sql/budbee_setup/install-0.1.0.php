<?php

$installer = $this;

$installer->startSetup();


// add Product attribute ship_with_budbee
$data = array(
    'type'=>'int',
    'input'=>'boolean',
    'sort_order'=>50,
    'label'=>'Shippable with Budbee',
    'global'=>Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required'=>'0',
    'comparable'=>'0',
    'searchable'=>'0',
    'is_configurable'=>'1',
    'user_defined'=>'1',
    'visible_on_front' => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 0,
    'required'=> 0,
    'unique'=>false,
    'apply_to' => 'simple,configurable,bundled,grouped',
    'is_configurable' => false
);

$installer->addAttribute('catalog_product','ship_with_budbee',$data);
$installer->addAttributeToSet( 'catalog_product', 'Default', 'General', 'ship_with_budbee');

$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote_address'), 'budbee_desired_delivery_date', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        'NULLABLE'  => true,
        'COMMENT'   => 'Budbee Desired Delivery Date'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote_address'), 'budbee_door_code', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'NULLABLE'  => true,
        'COMMENT'   => 'Door Code'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote_address'), 'budbee_outside_door', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'NULLABLE'  => false,
        'COMMENT'   => 'Outside Door'
    ));

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'budbee_desired_delivery_date', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        'NULLABLE'  => true,
        'COMMENT'   => 'Budbee Desired Delivery Date'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'budbee_door_code', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'NULLABLE'  => true,
        'COMMENT'   => 'Door Code'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'budbee_outside_door', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'NULLABLE'  => false,
        'COMMENT'   => 'Outside Door'
    ));

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order_grid'), 'budbee_desired_delivery_date', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        'NULLABLE'  => true,
        'COMMENT'   => 'Budbee Desired Delivery Date'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('sales/order_grid'), 'budbee_door_code', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'NULLABLE'  => true,
        'COMMENT'   => 'Door Code'
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('sales/order_grid'), 'budbee_outside_door', array(
        'TYPE'      => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'NULLABLE'  => false,
        'COMMENT'   => 'Outside Door'
    ));

$installer->getConnection()
    ->addKey($installer->getTable('sales/order_grid'), 'budbee_desired_delivery_date_idx', 'budbee_desired_delivery_date'
    );


$installer->endSetup();