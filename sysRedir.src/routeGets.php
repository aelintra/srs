<?php

	require_once __DIR__ . "/model/getController";

        $app->get('/provisioning', function ($request, $response, $args) {
        	
        	if (empty($_GET['mac'])) {
            	return $this->response->withJson('Not Found',400);
        	} 
			$mac = $_GET['mac'];
            $getter = new getController($this->db,$this->response);
            return $getter->get($mac);
        });

        $app->get('/provisioning/{macfile}', function ($request, $response, $args) {
        	$macArray = explode('.',$args['macfile']);
        	$mac = $macArray[0];
        	if (empty($mac)) {
                $result ['reason'] = "No MAC route";
                return $this->response->withJson($result,404);                  
        	}          	
            $getter = new getController($this->db,$this->response);
            return $getter->get($mac);
        });        
