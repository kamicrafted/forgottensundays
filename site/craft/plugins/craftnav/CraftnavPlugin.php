<?php
namespace Craft;

class CraftnavPlugin extends BasePlugin
{

	function getName()
	{
		return Craft::t('CraftNav');
	}

	function getVersion()
	{
		return '1.0';
	}

	function getDeveloper()
	{
		return 'Craftpl.us';
	}

	function getDeveloperUrl()
	{
		return 'http://craftpl.us';
	}

	function hasCpSection()
    {
        return true;
    }
    public function registerCpRoutes()
    {
        return array(

    	//delete form action
        'craftnav/deletenav' => array('action' => 'craftnav/deletenav'),
        'craftnav/deleteitem' => array('action' => 'craftnav/deleteitem'),
        'craftnav/ordernav' => array('action' => 'craftnav/ordernav'),
       
        // edit urls - check then reroute
        'craftnav/form/(?P<navId>\d+)' => 'craftnav/form',
        'craftnav/build/(?P<navId>\d+)' => 'craftnav/build',
        );

	}	

    public function onAfterInstall()
    {
        craft()->request->redirect(UrlHelper::getCpUrl('/craftnav/thanks/'));
    }

     public function getSettingsHtml()
    {
        return craft()->templates->render('craftnav/settings', array(
            'settings' => $this->getSettings()
        ));
    }
}
