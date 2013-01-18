<?php
namespace Application\Controller;

use Zend\View\Model\JsonModel;
use ZfcUser\Controller\UserController as BaseController;


class UserController extends BaseController {
    private function ajaxLoginImpl() {
        $res = array(
            'success' => false,
            'message' => $this->failedLoginMessage
        );

        $auth_service = $this->zfcUserAuthentication()->getAuthService();
        if ($auth_service->hasIdentity()) {
            $res['message'] = 'Already logged in';
            return $res;
        }

        $request = $this->getRequest();
        $form    = $this->getLoginForm();

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $res;
        }

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $adapter->prepareForAuthentication($this->getRequest());

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);


        if (!$auth->isValid()) {
            $adapter->resetAdapters();
            return $res;
        }


        return array(
            'success' => true
        );
    }

    public function ajaxLoginAction() {
        return new JsonModel($this->ajaxLoginImpl());
    }
}
