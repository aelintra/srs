<?php

require_once __DIR__ . "/model/postControllers";
require_once __DIR__ . "/model/helperClass";

        $app->post('/endpoint', function ($request, $response, $args) {
        	$input = $request->getParsedBody();
    		if (empty($input['customer_pkey'])) {
        		$result ['reason'] = "NULL customer KEY";
            	return $this->response->withJson($result,400);      	
    		} 
    		$helper = new modelHelper();
            $input['vendor'] = $helper->getVendorFromMac($input['pkey']);
            if (empty($input['vendor'])) {
        		$result ['reason'] = "MAC (key) is not a supported vendor";
            	return $this->response->withJson($result,400);                	
            }    		    		    		    		
            $poster = new postController($this->db,$input,$this->response);
            return $poster->post('endpoint');
        });

        $app->post('/template', function ($request, $response, $args) {
        	$input = $request->getParsedBody();    		
            $poster = new postController($this->db,$input,$this->response);
            return $poster->post('template');
        });

        $app->post('/customer', function ($request, $response, $args) {
        	$input = $request->getParsedBody();	    		
            $poster = new postController($this->db,$input,$this->response);
            return $poster->post('customer');
        });