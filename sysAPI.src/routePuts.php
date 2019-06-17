<?php

require_once __DIR__ . "/model/putClass";
require_once __DIR__ . "/model/helperClass";

        $app->put('/endpoint', function ($request, $response, $args) {
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
            $putter = new putController($this->db,$input,$this->response);
            return $putter->put('endpoint');
        });

        $app->put('/template', function ($request, $response, $args) {
        	$input = $request->getParsedBody();    		
            $putter = new putController($this->db,$input,$this->response);
            return $putter->put('template');
        });

        $app->put('/customer', function ($request, $response, $args) {
        	$input = $request->getParsedBody();	    		
            $putter = new putController($this->db,$input,$this->response);
            return $putter->put('customer');
        });
