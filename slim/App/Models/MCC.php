<?php
namespace App\Models;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class MCC extends Model {
    protected $table = 'tbl_maintenance_centre';

    protected $fillable = [
        'check_name', 'work_id'
    ];   
    public $timestamps = false; 
	
	public function getWorkPackList($strwhere = NULL,$whereArr = NULL)
	{
		$sql = 'SELECT  tmc.*,cl.ID AS CLID,cl.COMP_NAME,tail.TAIL,tail.MSNNO,tail.id AS tailid,tmc.is_delete,arc.operators AS operators
				FROM tbl_maintenance_centre AS tmc,aircraft_tail AS tail,fd_airlines AS cl,archive AS arc
				WHERE tmc.tail_id = tail.ID AND tail.CLIENTID=cl.ID AND tmc.tail_id =arc.TAIL '.$strwhere.' AND tmc.is_delete=0
				ORDER BY tmc.add_date DESC';
		$Results = DB::select($sql,$whereArr);
		return $Results;
	}
	public function getHeaders($args)
    {     
		$strwher = '';   
		$whereArr = [];
		print_r($args);
		if(isset($args['client_id']))
		{
			$strwher.= ' client_id=:client_id';
			$whereArr['client_id'] = $args['client_id'];
		}
		if(isset($args['doc_type']))
		{
			$strwher.= ' AND doc_type=:doc_type';
			$whereArr['doc_type'] = $args['doc_type'];
		}
		$sql = 'select * from tbl_mcc_livefields where '.$strwher.'  and is_delete = 0 and is_fix = 0 and mainUser_flag = 0 order by display_order' ;	 	 
		$Results = DB::select($sql,$whereArr);
		return $Results;
	}
}