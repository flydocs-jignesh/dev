<?php

namespace App\Models;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model {
    protected $table = 'aircraft_tail';

    protected $fillable = [
        'MSNNO', 'TAIL', 'TYPEID'
    ];
	
	public function getAircaftList($strwhere)
	{
		$Aircrafts = DB::select('SELECT *
					FROM (
					SELECT arc.ID,arc.CLIENTID,arc.AIRCRFTTYPE,arc.TAIL,arc.MANUFACTURER,arc.MANDATE,arc.REGDATE,arc.RETDATE,arc.TABNO,arc.OWNER,arc.lessor,arc.CADATE,arc.LINENO,arc.STATUS,arc.RETIREDDATE,arc.SOLDDATE,arc.SOLDTO,arc.TRANSFERTYPE,arc.NEWTAIL,arc.TAILTRANSFERDATE,arc.IS_DELETE,arc.AUD_START_DATE,arc.archive_reason_id,arc.operators,arc.lease_from,arc.lease_to,arc.db_status,arc.DateTime,arc.last_scan_date,arc.bible_template_id,co.country_name AS REGCOUNTRY, ata.is_cmc,ata.TAIL AS tailName,ata.ID tailId,ata.MSNNO AS tailMSNO, fa.COMP_NAME,airt.ID AS aircraftTypeId,airt.ICAO
					FROM aircraft_tail AS ata
					LEFT JOIN archive AS arc ON arc.TAIL=ata.ID
					LEFT JOIN fd_country AS co ON co.id= arc.REGCOUNTRY
					LEFT JOIN fd_airlines AS fa ON fa.ID = ata.CLIENTID
					JOIN aircrafttype AS airt ON airt.ID = arc.AIRCRFTTYPE
					WHERE 1=1 AND ata.DELFLG !=1 AND fa.DELFLG = 0) AS a
					WHERE 1=1 AND IS_DELETE IN (0,2)
					ORDER BY ICAO,COMP_NAME,tailName,CLIENTID');
		return $Aircrafts;
	}
}