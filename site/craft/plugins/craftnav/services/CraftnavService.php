<?php
namespace Craft;

class CraftnavService extends BaseApplicationComponent
{

	protected $record;
    protected $recordlist;

	/**
	 * Constructor
     * @param NavRecord = $record
	 * @param Recordlist = $recordlist
	 */
	public function __construct()
    {
        $record = null;
        $recordlist = null;

        $this->record = $record;
        if (is_null($this->record)) {
            $this->record = Craftnav_NavGroupRecord::model();
        }

        $this->recordlist = $recordlist;
        if (is_null($this->recordlist)) {
            $this->recordlist = Craftnav_NavRecord::model();
        }
    }

	public function create($attributes = array())
	{
		$model = new Craftnav_GroupModel();
		$model->setAttributes($attributes);

		return $model;
	}

    public function createitem($attributes = array())
    {
        $model = new CraftnavModel();
        $model->setAttributes($attributes);

        return $model;
    }


	public function save(Craftnav_GroupModel &$model)
	{
		if ($id = $model->getAttribute('id')) {
			if (null === ($record = $this->record->findByPk($id))) {
				throw new Exception(Craft::t('Can\'t find Nav with ID "{id}"', array('id' => $id)));
			}
		} else {
			$record = new Craftnav_NavGroupRecord();
		}

		$record->setAttributes($model->getAttributes());

		if ($record->save()) {
			$model->setAttribute('id', $record->getAttribute('id'));
			return true;
		} else {
			$model->addErrors($record->getErrors());
			return false;
		}
	}

    public function saveitem(CraftnavModel &$model)
    {
        if ($id = $model->getAttribute('id')) {
            if (null === ($recordlist = $this->recordlist->findByPk($id))) {
                throw new Exception(Craft::t('Can\'t find Nav with ID "{id}"', array('id' => $id)));
            }
        } else {
            $recordlist = new Craftnav_NavRecord();
        }

        $recordlist->setAttributes($model->getAttributes());

        if ($recordlist->save()) {
            $model->setAttribute('id', $recordlist->getAttribute('id'));
            return true;
        } else {
            $model->addErrors($recordlist->getErrors());
            return false;
        }
    }

    public function getById($navId)
    {
        if ($record = $this->record->findByPk($navId)) {
            return Craftnav_GroupModel::populateModel($record);
        }
    }

    public function getByName($navName)
    {
        if ($record = $this->record->findByAttributes(array('title'=>$navName))) {
            return Craftnav_GroupModel::populateModel($record);
        }
    }

    public function getItemsById($navId)
    {
        if ($record = $this->record->findByPk($navId)) {
            return CraftnavModel::populateModel($record);
        }
    }

    public function getNav($navName)
    {

        //lookup my codename
        $id = $this->getByName($navName);

        if (isset($id)) {

            $nav_id = $id->id;
            
            // grab the nav with id
            $nav = $this->navitems_list($nav_id);
        }
        
        if (isset($nav)) {
            $output = '<nav><ul>';
            foreach ($nav[0] as $item) {
               // var_export(craft()->getSiteUrl());
               // die();
                //craft()->config->parseEnvironmentString($item['link']);
                $output .= '<li>' .'<a href="'. craft()->config->parseEnvironmentString($item['link']) . '">' . $item['text'] . '</a>';
                if (!empty($item['children'])) {
                    $output .= "<ul>";
                    foreach ($item['children'] as $child) {
                        $output .= '<li>' .'<a href="'. craft()->config->parseEnvironmentString($child['link']) . '">' . $child['text'] . '</a>';
                        if (!empty($child['children'])) {
                            $output .= "<ul>";
                            foreach ($child['children'] as $childen) {
                                $output .= '<li>' .'<a href="'. craft()->config->parseEnvironmentString($childen['link']) . '">' . $childen['text'] . '</a>';
                            };
                            $output .= "</li></ul>";
                        } else {
                            $output .= '</li>';
                        }
                    };
                    $output .= "</li></ul>";
                } else {
                    $output .= '</li>';
                }
            }
            $output .= '</ul></nav>';
            return TemplateHelper::getRaw($output);
        }
    }

	public function nav_list()
    {

        $navs = craft()->db->createCommand()
            ->select('*')
            ->from('craftnav_navs')
            ->where('')
            ->queryAll();

        return $navs;
    }
    // not using - just backup
    public function navitems_list_backup()
    {
        $navs = craft()->db->createCommand()
            ->select('*')
            ->from('craftnav_items')
            ->order('order')
            ->where('')
            ->queryAll();

        return $navs;
    }

    public function navitems_list($navId)
    {
        $navs = craft()->db->createCommand()
            ->select('*')
            ->from('craftnav_items')
            ->order('order')
            ->where("nav_id = " . $navId)
            ->queryAll();

        if (empty($navs)) {
            return null;
        } else {
            return CraftnavModel::convertToTree($navs);
        }
    }

    // Grab nav items for use as selectable options
    public function grablistsoptions($navId)
    {
        $listitems = $this->navitems_list($navId);
        $options = array(''=>'-----');
        foreach ($listitems[0] as $item) {
            $options[$item['id']] = $item['text'];
            if (isset($item['children'])) {
                foreach ($item['children'] as $child) {
                    $options[$child['id']] = '---' . $child['text'];
                }
            }
        }
        return $options;
    }

    private function get_url() {
        $uri = $_SERVER['REQUEST_URI'];
        $pieces = explode("/", $uri);
        $form_id = $pieces[4];
        return $form_id;
    }

    public function check_form()
    {

        $form_id = $this->get_url();

        $a = craft()->db->createCommand()
        ->select("id")
        ->from("craftnav_navs")
        ->queryAll();

        // Check the form ID against the URL
        function check_form_id($a, $form_id) {
            foreach ($a as $b)
                if (isset($b['id']) && $b['id'] == $form_id)
                    return true;
            return false;
        }
        $checked = check_form_id($a,$form_id);
        return $checked;
    }

    public function get_name()
    {

        $form_id = $this->get_url();

        $a = craft()->db->createCommand()
        ->select("name")
        ->where("id = " . $form_id)
        ->from("craftnav_navs")
        ->queryRow();

        return $a['name'];
    }
	
}