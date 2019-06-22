<?php

require_once __DIR__ . "/model/putController";
require_once __DIR__ . "/model/helperClass";

        $app->put('/endpoint', function ($request, $response, $args) {
        	$input = $request->getParsedBody();		    		    		    		
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
