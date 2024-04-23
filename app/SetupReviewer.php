<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetupReviewer extends Model
{
	protected $connection = 'mysql';
    protected $primaryKey='id_reviewer_tahun_anggaran';
    protected $guarded=[];
    public $timestamps=false;
    protected $hidden=[];
    protected $table='reviewer_tahun_anggaran';

    public function pegawai()
    {
    	return $this->belongsTo("App\Pegawai","pegawai_id","id_pegawai");
    }    

}
