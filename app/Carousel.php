<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_carousel';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='carousel';

    

    

}
