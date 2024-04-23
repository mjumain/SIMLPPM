<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Prodi extends Model
{
  protected $connection = 'mysql';
    protected $primaryKey='id_prodi';
    public $incrementing=false;
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='prodi';

    
}