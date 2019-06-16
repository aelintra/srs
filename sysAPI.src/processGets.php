<?php

require_once __DIR__ . "/model/getClass";
/*
    GET processors
 */ 

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
            return $getter->getColumn('endpoint',$args['pkey'],$args['column']);
        });   


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
            return $getter->getColumn('template',$args['pkey'],$args['column']);
        });   


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
            return $getter->getColumn('customer',$args['pkey'],$args['column']);
        });           