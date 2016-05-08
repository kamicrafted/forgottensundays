<?php

namespace Craft;

class CraftnavModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'id' => AttributeType::Number,
            'nav_id' => AttributeType::Number,
            'order' => AttributeType::Number,
            'parent' => AttributeType::Number,
            'text' => array(AttributeType::String, 'required' => true),
            'link' => array(AttributeType::String, 'required' => true),
            'enabled' => AttributeType::Number
        );
    }

    public static function convertToTree(array $flat, $idField = 'id',
        $parentIdField = 'parent', $childNodesField = 'children')
    {
        $indexed = array();
        foreach ($flat as $row) {
            if (empty($row['parent']))
                $row['parent'] = 'root';
            $indexed[$row[$idField]] = $row;
            $indexed[$row[$idField]][$childNodesField] = array();
        }

        $root = null;
        foreach ($indexed as $id => $row) {
            $indexed[$row[$parentIdField]][$childNodesField][] =& $indexed[$id];
            if (!$row[$parentIdField]) {
                $root = $id;
            }
        }

        // echo "<pre>";
        // var_export($indexed['root']['children']);die();

        return array($indexed['root']['children']);
    }

}

