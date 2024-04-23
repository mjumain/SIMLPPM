<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class UnitKerja extends Model
{
  protected $connection = 'mysql';
  protected $primaryKey='id_unit_kerja';
  protected $guarded=[];
  public $timestamps=false;
  protected $table='unit_kerja';

  
    
    

}
