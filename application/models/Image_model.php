<?php
/**
* Handles image upload and resizing
* version: 2.0 (14-08-2018)
*/
class Image_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('string');
    }
    
    //predefined image sizes (can be changed according to project requirement)
    function image_sizes(){
        //add folder name
        $img_sizes                  = array();
        $img_sizes['thumbnail']     = array('width'=>150, 'height'=>150, 'folder'=>'/thumb');
        $img_sizes['medium']        = array('width'=>600, 'height'=>600, 'folder'=>'/medium');
        return $img_sizes;
    }//end function
    
    /**
     * Make upload directory
     * Modified in ver 2.0
     */
    function make_dirs($folder='',$mode=DIR_WRITE_MODE, $defaultFolder='uploads/'){

        if(!@is_dir(FCPATH . $defaultFolder)){
            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if(!empty($folder)){

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }//end function
    /**
     * Upload image and create resized copies
     * Modified in ver 2.0
     */
    function upload_image( $image, $folder, $height=768, $width=1024, $path=FALSE ){
        
        $this->make_dirs($folder);
        
        $realpath       = 'uploads/';
        $allowed_types  = "gif|jpg|png|jpeg"; 	
        $img_name       = random_string('alnum', 16);  //generate random string for image name
        
        $config = array(
            'upload_path'       => $realpath.$folder,
            'allowed_types'     => $allowed_types,
            //'max_size'          => "10240",   // File size limitation, initially w'll set to 10mb (Can be changed)
            //'max_height'        => "4000", // max height in px
            //'max_width'         => "4000", // max width in px
            //    'min_width'         => "200", // min width in px
            //   'min_height'        => "200", // min height in px
            'file_name'         => $img_name,
            'overwrite'	        => FALSE,
            'remove_spaces'	    => TRUE,
            'quality'           => '100%',
            );
		
        $this->load->library('upload'); //upload library
        $this->upload->initialize($config);
 
        if(!$this->upload->do_upload($image)){
            $error = array('error' => $this->upload->display_errors());
            return $error; //error in upload

        }
        //image uploaded successfully - proceed to create resized copies
        $image_data = $this->upload->data(); //get uploaded data
        $this->load->library('image_lib'); //image library

        // create folder for thumb image
        $folder_thumb = $folder.'/thumb/';
        $this->make_dirs($folder_thumb);


        // create folder for medium image
        $folder_resize = $folder.'/medium/';
        $this->make_dirs($folder_resize);

        // create folder for large image
        $large = '/large';
        $folder_large = $folder.$large;
        $this->make_dirs($folder_large);

        $img_sizes_arr = $this->image_sizes();  //predefined sizes in model
        $thumb_img = '';

        foreach($img_sizes_arr as $k=>$v){

            $real_path                      = realpath(FCPATH .$realpath .$folder);

            $resize['image_library']        = 'gd2';
            $resize['source_image']         = $image_data['full_path'];
            $resize['new_image'] 	        = $real_path.$v['folder'].'/'.$image_data['file_name'];
            $resize['maintain_ratio']       = FALSE;
            $resize['width'] 	            = $v['width'];
            $resize['height'] 	            = $v['height'];
            $resize['quality'] 	            = '100%';

            $this->image_lib->initialize($resize);
            $this->image_lib->resize();   //create resized copies
        }

        //custom size 
        $real_path                   = realpath(FCPATH .$realpath .$folder);
        $resize1['source_image'] 	 = $image_data['full_path'];
        $resize1['new_image'] 	     = $real_path.$large.'/'.$image_data['file_name'];
        $resize1['maintain_ratio'] 	 = FALSE;
        $resize1['width']            = $width;
        $resize1['height'] 		     = $height;
        $resize1['quality']          = '100%';

        $this->image_lib->initialize($resize1);
        $this->image_lib->resize();
        $this->image_lib->clear(); //clear memory
        if(empty($thumb_img))
            $thumb_img = $image_data['file_name'];

        return array('image_name'=>$thumb_img);   
    }//end function 
    
    //delete(unlink) image from folder/server
    function delete_image($path,$file){
        
        $main   = $path.$file;
        $thumb  = $path.'thumb/'.$file;
        $medium = $path.'medium/'.$file;
        $large  = $path.'large/'.$file;
        //pr($thumb);
        if(file_exists(FCPATH.$main)):
            unlink( FCPATH.$main);
        endif;
        if(file_exists(FCPATH.$thumb)):
            unlink( FCPATH.$thumb);
        endif;
        if(file_exists(FCPATH.$medium)):
            unlink( FCPATH.$medium);
        endif;
        if(file_exists(FCPATH.$large)):
            unlink( FCPATH.$large);
        endif;
        return TRUE;
    }//end function
    function updateDocument($image,$folder){
     
        $this->make_dirs($folder);
        $realpath       = 'uploads/';
        $allowed_types  = "*";//"gif|jpg|png|jpeg"; 

        $config = array(
            'upload_path'       => $realpath.$folder,
            'allowed_types'     => $allowed_types,
            'max_size'          => "204800000",// Can be set to particular file size , here it is 2 MB(2048 Kb)
            'overwrite'         => false,
            //'encrypt_name'      => TRUE,
            'remove_spaces'     => TRUE,
            'quality'           => '100%',
        );
        
        $this->load->library('upload');
        $this->upload->initialize($config);
        if(!$this->upload->do_upload($image)){

            $error = array('error' => $this->upload->display_errors());
            return $error;

        } else {

            $image_data = $this->upload->data(); 
            $fileType   = explode("/",$image_data['file_type']);
            $file_type  = ($fileType[0]=='image') ?'image' :'doc' ;


            return array('image_name'=>$image_data['file_name'],'file_type'=> $file_type,'upload_path'=>base_url().$realpath.$folder.'/'.$image_data['file_name']);
        }

    } // End Function
  	function updateGallery($fileName,$folder,$hieght=250,$width=250)
	{
		$this->make_dirs($folder);
        $realpath           = 'uploads/';
        $allowed_types      = "gif|jpg|png|jpeg"; 	
        $storedFile 		= array();
        $allowed_types 		= "gif|jpg|png|jpeg"; 
        $files 				= $_FILES[$fileName];
        $number_of_files 	= sizeof($_FILES[$fileName]['tmp_name']);
        // we first load the upload library
        $this->load->library('upload');
        // next we pass the upload path for the images
        $configG = array(
                    'upload_path'       => $realpath.$folder,
                    'allowed_types'     => $allowed_types,
                    'max_size'          => "10240",   // File size limitation, initially w'll set to 10mb (Can be changed)
                    'max_height'        => "4000", // max height in px
                    'max_width'         => "4000", // max width in px
                    //    'min_width'         => "200", // min width in px
                    //   'min_height'        => "200", // min height in px
                    'encrypt_name'      => TRUE,
                    'overwrite'	        => FALSE,
                    'remove_spaces'	    => TRUE,
                    'quality'           => '100%',
                );
			// now, taking into account that there can be more than one file, for each file we will have to do the upload
			for ($i = 0; $i < $number_of_files; $i++)
			{
				$_FILES[$fileName]['name'] 		= $files['name'][$i];
				$_FILES[$fileName]['type'] 		= $files['type'][$i];
				$_FILES[$fileName]['tmp_name'] 	= $files['tmp_name'][$i];
				$_FILES[$fileName]['error'] 	= $files['error'][$i];
				$_FILES[$fileName]['size'] 		= $files['size'][$i];

				//now we initialize the upload library
				$this->upload->initialize($configG);
				if ($this->upload->do_upload($fileName))
				{
					$savedFile                 = $this->upload->data();//upload the image
					$storedFile[$i]['name']    = $savedFile['file_name'];
					$storedFile[$i]['type']    = $savedFile['file_type'];
				}else{
					$storedFile[$i]['error']   = $this->upload->display_errors();
				}
			} // END OF FOR LOOP 
		return $storedFile;	  
	}//FUnction
}// End of class Image_model