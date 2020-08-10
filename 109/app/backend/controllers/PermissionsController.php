<?php namespace Modules\Backend\Controllers;

use Modules\Models\Profiles;
use Modules\Models\Permissions;

class PermissionsController extends BaseController
{
    public function indexAction()
    {
        $this->view->setTemplateBefore('private');

        

        //Pass all the active profiles
        $this->view->profiles = Profiles::find(array(
            "conditions" => "id != 1 AND active = 'Y'"
        ));
    }
    
    public function searchAction()
    {
        $this->view->setTemplateBefore('private');
        
        if ($this->request->isPost()) {

            //Validate the profile
            $profile = Profiles::findFirstById($this->request->getPost('profileId'));


            if ($profile) {
                if ($this->request->hasPost('permissions')) {
                    // echo '<pre>'; print_r($this->request->getPost('permissions'));   echo '</pre>';die;
                        
                    //Deletes the current permissions
                    $profile->getPermissions()->delete();
                    
                    

                    //Save the new permissions
                    foreach ($this->request->getPost('permissions') as $permission) {
                        $parts = explode('.', $permission);

                        $permission = new Permissions();
                        $permission->profilesId = $profile->id;
                        $permission->resource = $parts[0];
                        $permission->action = $parts[1];

                        $permission->save();
                    }

                    $this->flash->success('Permissions were updated with success');
                }

                //Rebuild the ACL with
                $this->acl->rebuild();

                //Pass the current permissions to the view
                $this->view->permissions = $this->acl->getPermissions($profile);
            }

            $this->view->profile = $profile;
        }
    }
    
    public function editAction($id)
    {
        $this->view->setTemplateBefore('private');
        
       

        //Validate the profile
        $profile = Profiles::findFirstById($id);

        if (!$profile) {
            $this->flash->error("Profile was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if ($profile) {
            if ($this->request->hasPost('permissions')) {
                //echo '<pre>'; print_r($this->request->getPost('permissions'));   echo '</pre>';die;

                //Deletes the current permissions
                $profile->getPermissions()->delete();


                //Save the new permissions
                foreach ($this->request->getPost('permissions') as $permission) {
                    $parts = explode('.', $permission);

                    $permission = new Permissions();
                    $permission->profilesId = $id;
                    $permission->resource = $parts[0];
                    $permission->action = $parts[1];

                    $permission->save();
                }

                $this->flash->success('Permissions were updated with success');
            }

            //Rebuild the ACL with
            $this->acl->rebuild();

            //Pass the current permissions to the view
            $this->view->permissions = $this->acl->getPermissions($profile);
        }

        $this->view->profile = $profile;
    }
}
