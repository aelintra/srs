<?php
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