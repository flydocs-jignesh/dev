<?php
namespace App\Controllers\MCC;
use App\Models\MCC as MCC;

class MCCController  {

    public function getWorkPackList($request, $response, $args) {
        try
        {        
			$params = $request->getParsedBody();  
			$strwhere = '';
			$whereArr = [];
			
			$MCCHeader = MCC::getHeaders($params); 
			$headers = [];
			foreach($MCCHeader as $header)
			{
				if($header['column_id']!='')
				{
					$headers[] =
				}
			}

			if(isset($params['view_type']))
			{
				$strwhere.= ' AND tmc.viewtype=:viewtype';
				$whereArr['viewtype'] = $params['view_type'];
			}
			if(isset($params['tail_id']))
			{
				$strwhere.= ' AND tmc.tail_id=:tail_id';
				$whereArr['tail_id'] = $params['tail_id'];
			}
			if(isset($params['doc_type']))
			{
				$strwhere.= ' AND doc_type=:doc_type';
				$whereArr['doc_type'] = $params['doc_type'];
			}			   		
            $MCC = MCC::getWorkPackList($strwhere,$whereArr); 
            return $response->withJson(['sucess'=> true,'data' => $MCC]);                     
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }

    public function getWorkPack($request, $response, $args) {
        try
        {        
            $id  = isset($args['id']) ? (int) $args['id'] : 0;          
            if($id > 0)
            {
                $MCC = MCC::find($id); 
                return $response->withJson(['sucess'=> true,'data' => $MCC]);         
            }
            else
            {
                return $response->withJson(['sucess'=> true,'msg' => 'No Data Found']);         
            }
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }

    public function CreateWorkPack($request, $response, $args) {
        try
        {        
            $data = $request->getParsedBody();         
            $MCC =  new MCC; 
            $MCC->check_name = $data['checkName'];
            $MCC->save(); 
            return $response->withJson(['sucess'=> true,'msg' => 'Added Sucessfully']);   
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }
	
	public function UpdateWorkPack($request, $response, $args) {
        try
        {        
            $data = $request->getParsedBody();         
            $MCC =  new MCC; 
            $MCC->check_name = $data['checkName'];
            $MCC->save(); 
            return $response->withJson(['sucess'=> true,'msg' => 'Added Sucessfully']);   
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }
        
}