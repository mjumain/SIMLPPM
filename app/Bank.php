<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Bank extends Model
{
  protected $connection = 'mysql';
  protected $primaryKey='id_bank';
  protected $guarded=[];
  public $timestamps=false;
  protected $table='bank';

  
    
    

}
