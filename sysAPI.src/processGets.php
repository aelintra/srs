<?php

require_once __DIR__ . "/model/getClass";
/*
    GET processors
 */ 

// 
// endpoint
// 
        $app->get('/endpoint', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getAll('endpoint');
        });

        $app->get('/endpoint/{pkey}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getId('endpoint',$args['pkey']);
        }); 

        $app->get('/endpoint/{pkey}/{column}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getRowColumn('endpoint',$args['pkey'],$args['column']);
        });   

// 
// template
// 
        $app->get('/template', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getAll('template');
        });

        $app->get('/template/{pkey}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getId('template',$args['pkey']);
        }); 

        $app->get('/template/{pkey}/{column}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getRowColumn('template',$args['pkey'],$args['column']);
        });   

// 
// customer
// 
        $app->get('/customer', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getAll('customer');
        });

        $app->get('/customer/{pkey}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getId('customer',$args['pkey']);
        }); 

        $app->get('/customer/{pkey}/{column}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getRowColumn('customer',$args['pkey'],$args['column']);
        });           