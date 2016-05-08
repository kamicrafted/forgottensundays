<?php
namespace Craft;

class Craftnav_NavRecord extends BaseRecord
{
    
    public function getTableName()
    {
        return 'craftnav_items';
    }

    protected function defineAttributes()
    {
        return array(
            'nav_id' => array(AttributeType::Number),
            'parent' => array(AttributeType::Number),
            'order' => array(AttributeType::Number),
            'text' => array(AttributeType::String, 'required' => true),
            'link' => array(AttributeType::String, 'required' => true),
            'enabled' => array(AttributeType::Number)
        );
    }

}