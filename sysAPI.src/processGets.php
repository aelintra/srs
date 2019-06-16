<?php

require_once __DIR__ . "/model/getClass";
/*
    GET processors
 */ 

        $app->get('/endpoint', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $this->response->withJson($getter->getAll('endpoint'));
        }

        $app->get('/endpoint/{pkey}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getId('endpoint',$args['pkey']);
        } 

        $app->get('/endpoint/{pkey}/{column}', function ($request, $response, $args) {
            $getter = new getController($this->db,$this->response);
            return $getter->getId('endpoint',$args['pkey'],$args['column']);
        }   

/*
        $app->get('/endpoint', function ($request, $response, $args) {
            try {   
                $sth = $this->db->prepare("SELECT * FROM endpoint order by mac");
                $sth->execute();
                $endpoint = $sth->fetchAll();
            } catch (exception $e) {
                $result ['reason'] = $e->getMessage();
                return $this->response->withJson($result,500);        
            }            
            return $this->response->withJson($endpoint);
        });
        
        $app->get('/endpoint/{mac}', function ($request, $response, $args) {
            try {
                $sth = $this->db->prepare("SELECT * FROM endpoint where mac=?");
                $sth->execute(array($args['mac']));
                $endpoint = $sth->fetchAll();
            } catch (exception $e) {
                $result ['reason'] = $e->getMessage();
                return $this->response->withJson($result,500);        
            } 
            if (empty($endpoint)) {
                $result ['reason'] = "MAC " . $args['mac'] . " Not Found";
                return $this->response->withJson($result,404);   
            }              
            return $this->response->withJson($endpoint);
        });

        $app->get('/endpoint/{mac}/{column}', function ($request, $response, $args) {
            try {
                $sth = $this->db->prepare("SELECT " . $args['column'] . " FROM endpoint where mac=?");
                $sth->execute(array($args['mac']));
                $endpoint = $sth->fetchAll();
            } catch (exception $e) {
                $result ['reason'] = $e->getMessage();
                return $this->response->withJson($result,500);        
            }
            if (empty($endpoint)) {
                $result ['reason'] = "MAC " . $args['mac'] . "/" . $args['column'] . " Not Found";
                return $this->response->withJson($result,404);   
            }            
            return $this->response->withJson($endpoint);
        });
*/

