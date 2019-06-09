<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
	
	function getRemoteIP() {
    	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        	$ip = $_SERVER['HTTP_CLIENT_IP'];
    	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    	}else{
       		$ip = $_SERVER['REMOTE_ADDR'];
    	}
		return($ip);
	}
	
    $container = $app->getContainer();
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

/*
 * PUT (UPDATE) requires mac.  Customer and requesturl may be updated
 */	
	$app->put('/endpoint', function ($request, $response, $args) {
    	$input = $request->getParsedBody();
    	if (empty($input['mac'])) {
        	$result ['reason'] = "NULL MAC address";
            return $this->response->withJson($input,400);      	
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
    	if ( empty($res['mac']) ) { 
            $result ['reason'] = "MAC does not exist " . $res['mac'];
            return $this->response->withJson($result,403);
    	} 
/*
 * Try the UPDATE
 */    	          	      	  	    	
    	try {
        	$sth = $this->db->prepare("UPDATE endpoint SET redirecturl=?,customer=?, z_updater=? WHERE mac=?;");
        	$sth->execute(array($input['redirecturl'],$input['customer'],getRemoteIP(),$input['mac']));
        	$endpoint = $sth->fetchAll();
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        }        
    	return $this->response->withJson($input,200);
	});
/*
 * DELETE requires mac.
 */
 
	$app->delete('/endpoint/{mac}', function ($request, $response, $args) {
/*
 *  check for key
 */ 
    	if ( ! isset($args['mac'])) {
        	$result ['reason'] = "No Key given";
        	return $this->response->withJson($result,400);
    	}

/*
 * Check the row exists 
 */
   	 	$sth = $this->db->prepare("SELECT mac FROM endpoint WHERE mac = ?");
   	 	try {
    		$sth->execute(array($args['mac']));
    		$res = $sth->fetch(); 
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        }    	  
    	if ( ! isset($res['mac']) ) { 
            $result ['reason'] = "Not Found";
            return $this->response->withJson($result,404);
    	} 
		try {    	 	
        	$sth = $this->db->prepare("DELETE FROM endpoint where mac=?");
        	$sth->execute(array($args['mac']));
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        }   
               	
        $result ['reason'] = $res['mac'] . " deleted OK"; 
        return $this->response->withJson($result,200);
	});
	
//usage	
	
    $app->get('/', function (Request $request, Response $response, array $args) {

        $lexicon = array (
        		"endpoint" => "http://{URL}/endpoint",
                "mac" => "http://{URL}/endpoint{/mac}",
                "customer" => "http://{URL}/endpoint{/mac}/customer",
                "firstseen" => "http://{URL}endpoint{/mac}/firstseen",
                "lastseen" => "http://{URL}/endpoint{/mac}/lastseen",
                "redirecturl" => "http://{URL}/endpoint{/mac}/redirecturl",
                "vendor" => "http://{URL}/endpoint{/mac}/vendor",                 
        );

    	// Sample log message
    	$this->logger->info("Slim-Skeleton '/' route");
  
    	// Render index view
    	return $this->response->withJson($lexicon,NULL,JSON_UNESCAPED_SLASHES);
	});
	

};
