<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {

    $container = $app->getContainer();

require_once __DIR__ . "/model/getController";    
    
        $app->get('/provisioning', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->request,$this->response);
            return $getter->provision();
        });

        $app->get('/provisioning/{macfile}', function ($request, $response, $args) {        
            $getter = new getController($this->db,$this->request,$this->response);
            return $getter->provision($macfile);
        });
};