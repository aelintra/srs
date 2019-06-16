<?php
/*
 * POST (INSERT)
 * Requires mac, redirecturl and customer
 */	
	$app->post('/endpoint', function ($request, $response, $args) {
    	$input = $request->getParsedBody();
    	if (empty($input['mac'])) {
        	$result ['reason'] = "NULL MAC address";
            return $this->response->withJson($result,400);      	
    	}
    	if (empty($input['redirecturl'])) {
        	$result ['reason'] = "NULL redirecturl";
            return $this->response->withJson($result,400);      	
    	}
    	if (empty($input['customer'])) {
        	$result ['reason'] = "NULL customer";
            return $this->response->withJson($result,400);      	
    	}  
/*
 * Check if the row exists 
 */
   	 	$sth = $this->db->prepare("SELECT mac FROM endpoint WHERE mac = ?");
   	 	try {
    		$sth->execute(array($input['mac']));
    		$res = $sth->fetch(); 
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        }  
    	if ( !empty($res['mac']) ) { 
            $result ['reason'] = "Duplicate MAC " . $res['mac'];
            return $this->response->withJson($result,403);
    	} 
/*
 * Try the INSERT
 */    	          	      	  	    	
    	try {
        	$sth = $this->db->prepare("INSERT INTO endpoint(mac,redirecturl,customer,z_updater) values (?,?,?,?);");
        	$sth->execute(array($input['mac'],$input['redirecturl'],$input['customer'],getRemoteIP()) );
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        }        
    	return $this->response->withJson($input,201);
	});