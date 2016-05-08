<?php
namespace Craft;

class CraftnavController extends BaseController
{
	protected $allowAnonymous = false;


	public function __construct()
    {
        $this->requirePostRequest();
    }

    // Save Form
    public function actionSavenav()
    {
        $form = craft()->request->getPost();

        if ($id = craft()->request->getPost('navId')) {
            $model = craft()->craftnav->getById($id);
        } else {
            $model = craft()->craftnav->create();
        }

        // Get the submitted data
		$model->name = $form['name'];
		$model->title = $form['title'];
		$model->desc = $form['desc'];

        if($model->validate()) {
            craft()->craftnav->save($model);
            craft()->userSession->setNotice(Craft::t('Nav saved.'));
            $this->redirect(UrlHelper::getCpUrl('/craftnav/build/'.$model->id));
        } else {
            craft()->userSession->setError(Craft::t('Please make sure that all fields are complete. Thanks!'));
            craft()->urlManager->setRouteVariables(array('craftnav' => $model));
    	}
    }

    // Reorder nav
    public function actionOrdernav()
    {
        //var_export($_POST['data']);
        $post = $_POST['data'];
        $i = 1;
        
        foreach ($post as $postitem)
        {
            $params['order'] = $i;
            $params['parent'] = 0;
            craft()->db->createCommand()->update('craftnav_items', $params, 'id=:id', array(':id'=>$postitem['id']));
            $i++;
            $order = 1;
            
            // check for children
            if (isset($postitem['children'])){
                // check each child
                foreach ($postitem['children'] as $child) {
                    $params['parent'] = $postitem['id'];
                    $params['order'] = $order;
                    craft()->db->createCommand()->update('craftnav_items', $params, 'id=:id', array(':id'=>$child['id']));
                    $order++;

                    if (isset($child['children'])){
                    // check each child
                    foreach ($child['children'] as $childed) {
                        $params['parent'] = $child['id'];
                        $params['order'] = $order;
                        craft()->db->createCommand()->update('craftnav_items', $params, 'id=:id', array(':id'=>$childed['id']));
                        $order++;
                        var_export($childed['id']);
                        }

                    }

                }

            }
        }
        
        return true;   
    }

    // Delete Nav
    public function actionDeletenav()
    {
        $params['id'] = $_POST['id'];
        craft()->db->createCommand()->delete('craftnav_navs', $params);
    }

    // Delete Nav item
    public function actionDeleteitem()
    {
        $params['id'] = $_POST['id'];
        craft()->db->createCommand()->delete('craftnav_items', $params);
    }

    // Save Nav Item
    public function actionSavenavitem()
    {
        $form = craft()->request->getPost();

        $id = craft()->request->getPost('navId');

        $model = craft()->craftnav->createitem();

        $model->text = $form['text'];
        $model->id = $form['id'];
        $model->link = $form['link'];
        $model->nav_id = $form['navId'];
        if (isset($form['parent'])) $model->parent = $form['parent'];
        $model->order = 0;

        // AJM Note: Fix
        if($model->link == '' OR $model->text == '') {
            craft()->userSession->setError(Craft::t('Please make sure that all fields are complete. Thanks!'));
            $this->redirect(UrlHelper::getCpUrl('/craftnav/build/'.$id));
        }

        if($model->validate()) {
            craft()->craftnav->saveitem($model);
            craft()->userSession->setNotice(Craft::t('Nav item saved.'));
            $this->redirect(UrlHelper::getCpUrl('/craftnav/build/'.$id));
        } else {
            craft()->userSession->setError(Craft::t('Please make sure that all fields are complete. Thanks!'));
            craft()->urlManager->setRouteVariables(array('craftnav' => $model));
        }

    }

}
