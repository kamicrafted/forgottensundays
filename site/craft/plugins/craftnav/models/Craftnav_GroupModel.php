<?php

namespace Craft;

class Craftnav_GroupModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'id' => AttributeType::Number,
            'name' => array(AttributeType::String, 'required' => true),
            'title' => array(AttributeType::String, 'required' => true),
            'desc' => array(AttributeType::String, 'required' => true)
        );
    }
}