<?php

require_once __DIR__ . "/model/deleteClass";

        $app->delete('/endpoint/{pkey}', function ($request, $response, $args) {
            $deleter = new deleteController($this->db,$this->response);
            return $deleter->delete('endpoint',$args['pkey']);
        });

        $app->get('/template/{pkey}', function ($request, $response, $args) {
            $deleter = new deleteController($this->db,$this->response);
            return $deleter->delete('template',$args['pkey']);
        });

        $app->get('/customer/{pkey}', function ($request, $response, $args) {
            $deleter = new deleteController($this->db,$this->response);
            return $deleter->delete('customer',$args['pkey']);
        });
