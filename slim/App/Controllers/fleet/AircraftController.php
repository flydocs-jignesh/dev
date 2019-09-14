<?php
namespace App\Controllers\fleet;
use App\Models\Aircraft as Aircraft;

class AircraftController  {

    public function getAircaftList($request, $response, $args) {
        try
        {        
			$strwhere = '';
            $page  = isset($args['page']) ? (int) $args['page'] : 1;
            $limit = isset($args['limit']) ? (int) $args['limit'] : 10;
            $Aircrafts = Aircraft::getAircaftList($strwhere); 
		    return $response->withJson(['sucess'=> true,'data' => $Aircrafts]);                     
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }

    public function getAircaft($request, $response, $args) {
        try
        {        
            $id  = isset($args['id']) ? (int) $args['id'] : 0;          
            if($id > 0)
            {
                $Aircrafts = Aircraft::find($id); 
                return $response->withJson(['sucess'=> true,'data' => $Aircrafts]);         
            }
            else
            {
                return $response->withJson(['sucess'=> true,'msg' => 'No Data Found']);         
            }
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }
        
}