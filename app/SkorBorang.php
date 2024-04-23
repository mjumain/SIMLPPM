<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class SkorBorang extends Model
{
	protected $connection = 'mysql';
  protected $primaryKey='id_skor_borang';
  protected $guarded=[];
  public $timestamps=false;
  protected $table='skor_borang';

  	public function usulan()
    {
    	return $this->belongsToMany("App\Usulan",'usulan_has_skor_borang','skor_borang_id','usulan_id');	
    }

  
    
    

}
