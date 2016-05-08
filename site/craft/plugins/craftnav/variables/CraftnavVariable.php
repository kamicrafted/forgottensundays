<?php
namespace Craft;

class CraftnavVariable
{

    // Navs for display in the admin panel
	public function grabNavs()
	{
		return craft()->craftnav->nav_list();
	}

    // Nav items for display in the admin panel
    public function grablists($navId)
    {
        return craft()->craftnav->navitems_list($navId);
    }

    // Grab nav items as selectable options
    public function grablistsoptions($navId)
    {
        return craft()->craftnav->grablistsoptions($navId);
    }

    public function checkFormId()
    {
        return craft()->craftnav->check_form();
    }

    public function getFormName()
    {
        return craft()->craftnav->get_name();
    }

    public function getById($navId) {
        return craft()->craftnav->getById($navId);
    }

    public function getByName($navName) {
        return craft()->craftnav->getByName($navName);
    }
    
    // Reference for Nav embed
    // Output: {{ craft.craftnav.nav("main_nav") }}
    public function nav($navName)
    {
        return craft()->craftnav->getNav($navName);
        
    }

}