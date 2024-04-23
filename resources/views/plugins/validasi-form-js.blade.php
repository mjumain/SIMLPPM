<script>

(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keypress keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));

$(document).ready(function() {
  $("input[name=nik]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
   $("input[name=nisn]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=jumlah_saudara]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=tahun_lahir_mahasiswa]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=rt]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=rw]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=kode_pos]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=nik_ayah]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=tahun_lahir_ayah]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=angka_penghasilan_ayah]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
   $("input[name=nik_ibu]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=tahun_lahir_ibu]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=angka_penghasilan_ibu]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });

  $("input[name=tahun_lahir_wali]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=angka_penghasilan_wali]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
   $("input[name=tahun_masuk]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=tahun_lulus]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=angka_penghasilan_ayah]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  
  $("input[name=nomor_bdt]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=nomor_kartu_keluarga]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=nik_kepala_keluarga]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=telp_ayah]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=telp_ibu]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=telp_wali]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=estimasi_penghasilan]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
  $("input[name=tahun_lulus]").inputFilter(function(value) {
    return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  });
});
function penghasilan_ayah()
{

  if ($("#check_peng_ayah:checked").length) {
    $("#input_peng_ayah").val(0);
    $("#input_peng_ayah").prop('readonly',true);
  }else{
    $("#input_peng_ayah").prop('readonly',false);
  }
}
function penghasilan_ibu()
{

  if ($("#check_peng_ibu:checked").length) {
    $("#input_peng_ibu").val(0);
    $("#input_peng_ibu").prop('readonly',true);
  }else{
    $("#input_peng_ibu").prop('readonly',false);
  }
}
function penghasilan_wali()
{

  if ($("#check_peng_wali:checked").length) {
    $("#input_peng_wali").val(0);
    $("#input_peng_wali").prop('readonly',true);
  }else{
    $("#input_peng_wali").prop('readonly',false);
  }
}
function saudara()
{

  if ($("#check_jumlah_saudara:checked").length) {
    $("#input_jumlah_saudara").val(0);
    $("#input_jumlah_saudara").prop('readonly',true);
  }else{
    $("#input_jumlah_saudara").prop('readonly',false);
  }
}
function listik()
{

  if ($("#check_daya_listrik:checked").length) {
    $("#input_daya_listrik").val(0);
    $("#input_daya_listrik").prop('readonly',true);
  }else{
    $("#input_daya_listrik").prop('readonly',false);
  }
}

function isNumber(evt){
    
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
    {

        return false;
    }else{

        return true;  
    }
}
$("input[name='angka_penghasilan_ayah']").keyup(function(e){
  e.preventDefault();
  $("#text_penghasilan_ayah").html(terbilang($(this).val()));
});
$("input[name='angka_penghasilan_ibu']").keyup(function(e){
  e.preventDefault();
  $("#text_penghasilan_ibu").html(terbilang($(this).val()));
});
$("input[name='angka_penghasilan_wali']").keyup(function(e){
  e.preventDefault();
  $("#text_penghasilan_wali").html(terbilang($(this).val()));
});
$("input[name='estimasi_penghasilan']").keyup(function(e){
  e.preventDefault();
  $("#text_estimasi").html(terbilang($(this).val()));
});


$(".select2").select2({
  placeholder:"Tentukan pilihan",
});
$("#provinsi_kode").select2({
  placeholder:"Pilih Provinsi Tinggal Saat Ini",
});
 $("#id_kecamatan").select2({
    placeholder:"Tentukan kecamatan..",

    ajax:{
        url:"{{url('pendaftar/pendaftaran/load-kecamatan')}}",
        dataTyper:"json",
        data:function(param)
        {
            var value= {
                search:param.term,
            };
            return value;
        },
        processResults:function(hasil)
        {
            return {
                results:hasil,
            };
        }
    },


  });




function berikutnya() {
  alertify.confirm("Konfirmasi!","Apakah anda yakin sudah mengisi data dengan benar?",function(){
    $("#overlay").fadeIn(300);  
    $('#form-pendaftaran').submit();
  },function(){

  });
}



  $.validator.addMethod('filesize', function (value, element, param) {
    
    return this.optional(element) || (element.files[0].size <= param)
  });
  $.validator.addMethod('extensi', function (value, element, param) {
    
    return this.optional(element) || (element.files[0].type <= param)
  });
  
  
  var ukuran=501200;
 $("#form-pendaftaran").validate({
  focusInvalid: false,
    invalidHandler: function(form, validator) {
       $("#overlay").fadeOut(300);
        if (!validator.numberOfInvalids())

            return;

        $('html, body').animate({
            scrollTop: $(validator.errorList[0].element).offset().top
        }, 1000);


    },
  errorPlacement: function(label, elem) {
   
    elem.closest(".form-group").find(".message").append(label);
    elem.closest(".form-group").find(".message").append('<br>');

  },
   highlight: function(element, errorClass, validClass) {
    $(element).parents("div.form-group").addClass('has-error');
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).parents("div.form-group").removeClass('has-error');
  },
  focusCleanup: true,
  rules: {
    nik: {
      required: true,
      minlength: 16,
      maxlength: 16,
    },
    nomor_kartu_keluarga: {
      required: true,
      minlength: 16,
      maxlength: 16,
    },
    nik_kepala_keluarga: {
      required: true,
      minlength: 16,
      maxlength: 16,
    },
    nisn: {
      required: true,
      minlength: 10,
      maxlength: 10,
    },
    tahun_lahir_mahasiswa: {
      required: true,
      minlength: 4,
      maxlength: 4,
    }, 
    email: {
      required: true,
      email: true
    },
    scan_ijazah : {
      
      filesize : ukuran, // 500kb
    },
    scan_skl : {
      
      filesize : ukuran, // 500kb
    },
    scan_biodata : {
      
      filesize : ukuran, // 500kb
    },
    scan_kartu_masuk_pt : {
      
      filesize : ukuran, // 500kb
    },
    scan_surat_penghasilan : {
      
      filesize : ukuran, // 500kb
    },
    
    scan_rekening_listrik : {
      
      filesize : ukuran, // 500kb
    },
    scan_pembayaran_pdam : {
      
      filesize : ukuran, // 500kb
    },
    pas_foto : {
      
      filesize : ukuran, // 500kb
    },
    scan_pembayaran_pbb : {
      
      filesize : ukuran, // 500kb
    },
    scan_kk : {
      
      filesize : ukuran, // 500kb
    },
    scan_akte_kelahiran : {
      
      filesize : ukuran, // 500kb
    },
    foto_rumah_depan : {
      
      filesize : ukuran, // 500kb
    },
    foto_ruang_tamu : {
      
      filesize : ukuran, // 500kb
    },
    foto_dapur : {
      
      filesize : ukuran, // 500kb
    },
    foto_kamar : {
      
      filesize : ukuran, // 500kb
    },
    foto_kamar_mandi : {
      
      filesize : ukuran, // 500kb
    },
    foto_keluarga : {
      
      filesize : ukuran, // 500kb
    },
    scan_formulir_beasiswa : {
      
      filesize : ukuran, // 500kb
    },
    scan_kip : {
      
      filesize : ukuran, // 500kb
    },
    scan_rapor_sma : {
      
      filesize : 3072000, // 500kb
    },
    scan_surat_keterangan_tidak_mampu : {
      
      filesize : ukuran, // 500kb
    },
    bukti_sehat_jasmani : {
      
      filesize : ukuran, // 500kb
    },
    bukti_bebas_narkoba : {
      
      filesize : ukuran, // 500kb
    },
    bukti_sehat_pendengaran : {
      
      filesize : ukuran, // 500kb
    },
    bukti_bebas_buta_warna : {
      
      filesize : ukuran, // 500kb
    },
    scan_ktp : {
      
      filesize : ukuran, // 500kb
    },
    scan_ktp_ayah : {
      
      filesize : ukuran, // 500kb
    },
    scan_ktp_ibu : {
      
      filesize : ukuran, // 500kb
    },
    scan_pernyataan_orang_tua : {
      
      filesize : 3072000, 
    },



  },
  messages: {
    required:"Wajib diisi!",
    nik:{
      minlength:'Input harus 16 karakter',
      maxlength:'Input harus 16 karakter',
    },
    nisn:{
      minlength:'Input harus 10 karakter',
      maxlength:'Input harus 10 karakter',
    },
    tahun_lahir_mahasiswa:{
      minlength:'Input harus 4 karakter',
      maxlength:'Input harus 4 karakter',
    },
    email: {
      email: "Your email address must be in the format of name@domain.com"
    },
    scan_ijazah : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    pas_foto : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus jpg,jpeg tau png',
      
    },
    scan_skl : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_biodata : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_kartu_masuk_pt : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_surat_penghasilan : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_rekening_listrik : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_pembayaran_pdam : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_pembayaran_pbb : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_kk : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_akte_kelahiran : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    foto_rumah_depan : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus .jpg, .jpeg, atau .png',
    },
    foto_ruang_tamu : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus jpg, jpeg, atau png',
    },
    foto_dapur : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus jpg, jpeg, atau png',
    },    

    foto_kamar : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus jpg, jpeg, atau png',
    },
    foto_kamar_mandi : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus jpg, jpeg, atau png',
    },
    foto_keluarga : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus jpg, jpeg, atau png',
    },
    scan_formulir_beasiswa : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_rapor_sma : {
      
      filesize : 'Maksimal Ukuran file  3 MB',
      accept:'Format file harus pdf',
    },
    scan_kip : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_surat_keterangan_tidak_mampu : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    bukti_sehat_pendengaran : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    bukti_sehat_jasmani : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    bukti_bebas_narkoba : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    bukti_bebas_buta_warna : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_ktp_ayah : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_ktp_ibu : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_ktp : {
      
      filesize : 'Maksimal Ukuran file 500 KB',
      accept:'Format file harus pdf',
    },
    scan_pernyataan_orang_tua : {
      
      filesize : 'Maksimal Ukuran file 3 MB',
      accept:'Format file harus pdf',
    },

  }
});

jQuery.extend(jQuery.validator.messages, {
  required: "Input harus diisi!",
});

    
</script>