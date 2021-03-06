<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {

    $container = $app->getContainer();
	
	require __DIR__ . "/routeGets.php";
    require __DIR__ . "/routePosts.php";
    require __DIR__ . "/routePuts.php";
    require __DIR__ . "/routeDeletes.php";

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
//   	$this->logger->info("Slim-Skeleton '/' route");
  
    	// Render index view
    	return $this->response->withJson($lexicon,NULL,JSON_UNESCAPED_SLASHES);
	});
	

};
