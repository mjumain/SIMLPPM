 <script src="{{asset('js/FileUpload.js')}}"></script>
 <script type="text/javascript">
   function upload_file(jenis,size=512000,tipe=null) { //default ukuran 400kb
      
      var ukuran=size;
      var link_upload="{{url('upload-file-camaru')}}/"+jenis+"/{{encrypt($data->id_camaru)}}";
      var link_load="{{url('load-file-camaru')}}/"+jenis+"/{{encrypt($data->id_camaru)}}";
      var link_delete="{{url('delete-file-camaru')}}/"+jenis+"/{{encrypt($data->id_camaru)}}";
      var link_download="{{url('download-file-camaru')}}/"+jenis+"/{{encrypt($data->id_camaru)}}";
      acceptFile="application/pdf";
      allowedFile="pdf";

      if (tipe=='gambar') {
        acceptFile="image/*";
        allowedFile="jpg,jpeg,png,JPEG,JPEG,PNG";
      }

    $("#"+jenis).uploadFile({
        url:link_upload,
        dragDrop:true,
        multiple:false,
        maxFileCount:1,
        fileName:"file",
        acceptFiles:acceptFile,
        allowedTypes:allowedFile,
        maxFileSize:ukuran, //500kb
        returnType: "json",
        showDelete: true,
        showDownload:true,
        abortStr:"Hapus File",
        cancelStr:"Batal",
        doneStr:"Selesai",
        extErrorStr:"Ekstensi file salah, pastikan ekstensi file harus : ",
        sizeErrorStr:"Ukuran file terlalu besar, maksimal : ",
        uploadErrorStr:"Gagal Upload ",
        uploadStr:"Upload File",
        headers: {_token:"{{csrf_token()}}"},
        onLoad:function(obj)
         {
          $.ajax({
              cache: false,
              url: link_load,
              dataType: "json",
              success: function(data) 
              {
                obj.createProgress(data["name"],data["path"],data["size"]);
              }
          });
        },
        onSelect: function (files) {
            console.log(files);
        },
        
        deleteCallback: function (data, pd) {
            
                $.get(link_delete, {op: "delete",name: data},
                    function (resp,textStatus, jqXHR) {
                        //Show Message  
                        alert("File Deleted");
                    });
            
            pd.statusbar.hide(); //You choice.

        },
        downloadCallback:function(filename,pd)
          {
             $.get(link_download, {name: data},
                function (resp,textStatus, jqXHR) {
                        //Show Message  
                 
                      location.href="download.php?filename="+filename;
              });
          }

      });
    }
 </script>