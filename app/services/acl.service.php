<?php

/**
 * Description of 
 *
 * @author Bjorn
 */
class ACLService extends Service {

    public $app;
    private $access = false;
    private $userid = 0;
    private $route, $errorRoute = '';

    public function run() {
        $this->route = $this->getRoute();
        $this->errorRoute = $this->getErrorRoute();
        $this->userid = $this->getUserID();
        if ($this->denied($this->route, $this->userid)):
            //die('DENIED');
        endif;
        $this->access = true;
    }

    public function access($route, $userid = NULL) {
        if (is_null($userid)):
            $userid = $this->getUserID();
        endif;
        return $this->isAllowed($route, $userid);
    }

    public function denied($route, $userid = NULL) {
        if (is_null($userid)):
            $userid = $this->getUserID();
        endif;
        return !$this->access($route, $userid);
    }

    private function isAllowed($route, $userid) {
        return $this->dao('acl')->isAllowed($route, $userid);
    }

    private function getRoute() {
        return Configuration::read('route.name');
    }

    private function getErrorRoute() {
        return Configuration::read('enviroment.errorroute');
    }

    private function getUserID() {
        return $this->service('authentication')->id();
    }

}
