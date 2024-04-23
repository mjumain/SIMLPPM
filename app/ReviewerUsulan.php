<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewerUsulan extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey='id_reviewer_usulan';
    protected $guarded=[];
    public $timestamps=false;
    protected $table='reviewer_usulan';
    public function pegawai()
    {
        return $this->belongsTo('App\Pegawai','reviewer_id','id_pegawai');
    }
    public function usulan()
    {
        return $this->belongsTo('App\Usulan','usulan_id','id_usulan');
    }

    

    

}
