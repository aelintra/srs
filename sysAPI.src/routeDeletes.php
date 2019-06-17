<?php

require_once __DIR__ . "/model/deleteController";

        $app->delete('/endpoint/{pkey}', function ($request, $response, $args) {
            $deleter = new deleteController($this->db,$this->response);
            return $deleter->delete('endpoint',$args['pkey']);
        });

        $app->delete('/template/{pkey}', function ($request, $response, $args) {
            $deleter = new deleteController($this->db,$this->response);
            return $deleter->delete('template',$args['pkey']);
        });

        $app->delete('/customer/{pkey}', function ($request, $response, $args) {
            $deleter = new deleteController($this->db,$this->response);
            return $deleter->delete('customer',$args['pkey']);
        });
