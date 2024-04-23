<div class="form-group col-lg-3 col-md-3 col-xs-12">
    <label>Tahun Penerimaan</label>
    <select class="form-control" name="tahun_anggaran_id" id="tahun_anggaran_id" onchange="load_data()" style="width: 100%">
        @foreach (App\TahunAnggaran::orderBy('tahun', 'desc')->get() as $t)
            <option value="{{ $t->id_tahun_anggaran }}">{{ $t->tahun }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-lg-3 col-md-3 col-xs-12">
    <label>Jenis Usulan</label>
    <select class="form-control " name="jenis_usulan_id" id="jenis_usulan_id" onchange="load_data()" style="width: 100%">
        <option value="">Semua</option>
        @foreach (App\JenisUsulan::get() as $t)
            <option value="{{ $t->id_jenis_usulan }}">{{ $t->jenis_usulan }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-lg-3 col-md-3 col-xs-12">
    <label>Sumber Dana</label>
    <select class="form-control " name="sumber_dana_id" id="sumber_dana_id" onchange="load_data()" style="width: 100%">
        <option value="">Semua</option>
        @foreach (App\SumberDana::get() as $t)
            <option value="{{ $t->id_sumber_dana }}">{{ $t->nama_sumber_dana }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-lg-3 col-md-3 col-xs-12">
    <label>Unit Kerja</label>
    <select class="form-control " name="unit_kerja_id" id="unit_kerja_id" onchange="load_data()" style="width: 100%">
        <option value="">Semua</option>
        @foreach (App\UnitKerja::get() as $t)
            <option value="{{ $t->id_unit_kerja }}">{{ $t->nama_unit }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-lg-3 col-md-3 col-xs-12">
    <label>Skim</label>

    <select class="form-control select2" name="skim_id" id="skim_id" onchange="load_data()" style="width: 100%">
        <option value="">Semua</option>
        @foreach (App\Skim::get() as $t)
            <option value="{{ $t->id_skim }}">{{ $t->nama_skim }}</option>
        @endforeach
    </select>
</div>
<div class="form-group col-lg-3 col-md-3 col-xs-12">
    <label>Jenis Skema</label>

    <select class="form-control" name="jenis_skema_id" id="jenis_skema_id" onchange="load_data()" style="width: 100%">
        <option value="">Semua</option>
        @foreach (App\JenisSkema::get() as $t)
            <option value="{{ $t->id_jenis_skema }}">{{ $t->nama_jenis_skema }}</option>
        @endforeach
    </select>
</div>
