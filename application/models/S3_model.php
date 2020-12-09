<?php
/**
* Handles image upload and resizing
* version: 2.0 (14-08-2018)
*/
class S3_model extends CI_Model{
    
  public function __construct(){
    parent::__construct();
    $this->load->helper('string');
  }
  // This function for upload files
  public function uploadImgS3($uploadFor='jobs')
  {
    $msg = 0;
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
      $valid_formats  = array("jpg", "png", "gif", "bmp","jpeg","pdf","csv","doc","docx","PNG","JPG","JPEG","GIF","BMP","PDF","CSV","DOC","DOCX");

      $name           = $_FILES['file']['name'];
      $size           = $_FILES['file']['size'];
      $tmp            = $_FILES['file']['tmp_name'];
      $ext            = $this->getExtension($name);

      if(strlen($name) > 0)
      {
        if(in_array($ext,$valid_formats))
        {
          //Rename image name.
          $actual_image_name = time().".".$ext;
          if($this->s3->putObjectFile($tmp, BUCKETNAME , $uploadFor.'/'.$actual_image_name, S3::ACL_PUBLIC_READ) )
          {
            $msg    = $actual_image_name;
          }
          else
            $msg    = 3; //"S3 Upload Fail.";
          }
      else
      $msg = 1; //"Invalid file, please upload image file.";
    }
    else
      $msg = 0; //"Please select image file.";
    }
    return $msg;
  }//End Function
  // Return the extension of the given file
  public function getExtension($str)
  {
    $i    = strrpos($str,".");
    if (!$i) { return ""; }
    $l    = strlen($str) - $i;
    $ext  = substr($str,$i+1,$l);
    return $ext;
  }//End Function
  // Delete The file form S3
  public function deleteImg($objectOf, $object)
  {
    return $this->s3->deleteObject(BUCKETNAME,"$objectOf/$object");
  }//End Function
}// End of class 