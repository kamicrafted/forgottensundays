<?php
namespace Craft;

class Craftnav_NavGroupRecord extends BaseRecord
{
    
    public function getTableName()
    {
        return 'craftnav_navs';
    }

    protected function defineAttributes()
    {
        return array(
            'name' => array(AttributeType::String, 'required' => true),
            'title' => array(AttributeType::String, 'required' => true),
            'desc' => array(AttributeType::String, 'required' => true),
        );
    }

}