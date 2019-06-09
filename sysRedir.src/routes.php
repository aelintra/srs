<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {

    $container = $app->getContainer();
    
	$app->get('/provisioning', function ($request, $response, $args) {

// get the MAC from the query
		if (empty ($_GET['mac'])) {
			return $this->response->withJson('Not Found',400);
		}	
		$mac = $_GET['mac'];
		try {
        	$sth = $this->db->prepare("SELECT * FROM endpoint where mac=?");
        	$sth->execute(array($mac));
        	$endpoint = $sth->fetch();
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        } 
        if (empty($endpoint)) {
        	$result ['reason'] = $mac . " Not Found";
            return $this->response->withJson($result,404);   
        }
        $redirURL = $endpoint['redirecturl'] . $_SERVER["REQUEST_URI"];   
        return $response->withRedirect($redirURL, 301);
	});
	
	$app->get('/provisioning/{macfile}', function ($request, $response, $args) {
	
// get the MAC from the URI
		$macArray = explode('.',$args['macfile']);
		$mac = $macArray[0];
		if (empty($mac)) {
        		$result ['reason'] = "Not Found";
            	return $this->response->withJson($result,404);  				
		}
		try {
        	$sth = $this->db->prepare("SELECT * FROM endpoint where mac=?");
        	$sth->execute(array($mac));
        	$endpoint = $sth->fetch();
        } catch (exception $e) {
        	$result ['reason'] = $e->getMessage();
            return $this->response->withJson($result,500);        
        } 
        if (empty($endpoint)) {
        	$result ['reason'] = $mac . " Not Found";
            return $this->response->withJson($result,404);   
        }
        $redirURL = $endpoint['redirecturl'] . $_SERVER["REQUEST_URI"];
        return $response->withRedirect($redirURL, 301);		
	});

};
