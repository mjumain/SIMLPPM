<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Tema extends Model
{
	protected $connection = 'mysql';
  protected $primaryKey='id_tema';
  protected $guarded=[];
  public $timestamps=false;
  protected $table='tema';
  public function bidang()
  {
    return $this->belongsTo("App\Bidang","bidang_id","id_bidang");
  }

  
    
    

}
