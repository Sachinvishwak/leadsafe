<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Manage extends Common_Front_Controller {
 
    /**
     * load list modal 
     */
 	function __Construct(){
       	parent::__Construct();
		$this->load->library('smtp_email');
		$this->load->library('background');
 	}
	function mailSent(){
		
		$email 		= trim($this->input->post('email'));
		$subject 	= trim($this->input->post('subject'));
		$path 		= trim($this->input->post('path'));
		$data 		= $this->input->post('msg');
		$message 	= $this->load->view('email/'.$path,$data,TRUE);
		$response 	= $this->smtp_email->send_mail($email,$subject,$message);
		log_event($response,'background_log.txt');  //create log of notifcation
	}//ENd FUnction
	function weatherHourly(){
		$customers =  $this->common_model->customerAddresses();
		$this->db->truncate('weatherNotification');
		if(!empty($customers)){
			foreach ($customers as $key => $value) {
				$url 	= base_url()."manage/weatherHourlyWithCustomer";
				$param 	= array('customerId' => $value->customerId,'addressId'=> $value->addressId,'latitude'=> $value->latitude,'longitude'=> $value->longitude);
				$res = $this->background->do_in_background($url, $param);
			}
			sleep(50);
			$this->weatherEmailSent();
		}
		$responce = array('status'=>SUCCESS,'message'=>"Ok");
		echo json_encode($responce);
	}//End Function
	function weatherHourlyWithCustomer(){
		//weatherTemperature
		$setTemp = 0;
		$admin = $this->common_model->getsingle('admin');
		if(!empty($admin)){
			$setTemp = $admin['weatherTemperature'];
		}
		
		$res 						= $this->input->post();
		$latitude 					= $res['latitude'];
		$longitude 					= $res['longitude'];
		$location 					= true;
		$excludeParameterMetadata 	= true;
		$addressId 					= $res['addressId'];
		$customerId 			    = $res['customerId'];
		/*$data_val['addressId'] 	= $addressId;
		$data_val['customerId'] 	= $data['customerId'];
		$data_val['temperature'] 	= "test";
		$data_val['alertDate'] 		= date("Y-m-d");
		$data_val['alertTime'] 		= date('H:i A');*/
		//$this->common_model->insertData('weatherNotification',$data_val);
		/***********************************************************************/
		/*$client_id 			= "8b291788-f31d-4d57-8c94-1ffff528d739" ;
		$client_secret_key 	= "E6pM1yQ3sU0uC4nQ0dX2rE3rV5bX2vX5uQ3yM6xW8oB7cF8oL2";*/
		$client_secret_key  = "yU4fL0cB2qB0jL1eM0lG5bA8cA4jT6dO5rM7mS1yS3yH0hY3fF";
		$client_id      	= "2e873775-fdff-440e-ba04-2b66054945cc" ;
		$url 				= "https://api-metoffice.apiconnect.ibmcloud.com/metoffice/production/v0/forecasts/point/hourly?excludeParameterMetadata=".$excludeParameterMetadata."&includeLocationName=".$location."&latitude=".$latitude."&longitude=".$longitude;
		$curl 				= curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL 				=> $url,
		  CURLOPT_RETURNTRANSFER 	=> true,
		  CURLOPT_ENCODING 			=> "",
		  CURLOPT_MAXREDIRS 		=> 10,
		  CURLOPT_TIMEOUT 			=> 30,
		  CURLOPT_HTTP_VERSION 		=> CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST 	=> "GET",
		  CURLOPT_HTTPHEADER 		=> array(
		    "accept: application/json",
		    "x-ibm-client-id:$client_id",
		    "x-ibm-client-secret:$client_secret_key"
		  ),
		));

		$response 	= curl_exec($curl);
		$err 		= curl_error($curl);

		curl_close($curl);

		if ($err) {
		  return "cURL Error #:" . $err;
		}else{
			log_event($response,'background_log.txt'); 
	  		$data = json_decode($response,true);
	  		if(isset($data['features']) && !empty($data['features'])){

		  		/*test*/
				for ($i=0; $i <sizeof($data['features']) ; $i++) { 

					for ($j=0; $j <sizeof($data['features'][$i]['properties']['timeSeries']) ; $j++) { 

						$d = $data['features'][$i]['properties']['timeSeries'][$j]['time'];
						$screenTemperature = $data['features'][$i]['properties']['timeSeries'][$j]['screenTemperature'];
						$weatherdate = strtotime(date('Y-m-d',strtotime($d)));
						$today = strtotime(date('Y-m-d'));
						if($today==$weatherdate){
							if($screenTemperature <=$setTemp){
								$data_val = array();
								$data_val['addressId'] 		= $addressId;
								$data_val['customerId'] 	= $res['customerId'];
								$data_val['temperature'] 	= $screenTemperature;
								$data_val['alertDate'] 		= date("Y-m-d",strtotime($d));
								$data_val['alertTime'] 		= date('h:i A',strtotime($d));
								$this->common_model->insertData('weatherNotification',$data_val);
							}
						}//end
					/*$data['features'][$i]['properties']['timeSeries'][$j]['time']=date('d-m-Y h:i A',strtotime($d));*/
					}
				}
		  		/*test*/
	  		}else{
	  			log_event($data['httpMessage'],'background_log.txt'); 
		  	}
		return true;
		}
	/**************************************************************/

	}//End Function
	function weatherEmailSent(){
		$customerNotification 			 = $this->common_model->customerNotification();
		$data['url']    				 = base_url();
		$data['customerNotification']    = $customerNotification;
		$emails                      	 = $this->common_model->adminEmails();
		$message        				 = $this->load->view('emails/weatherEmail',$data,TRUE);
		$subject         				 = "Weather Alert";
		$response 					     = 0;
		if(!empty($customerNotification)){
		//	pr($customerNotification);
			$response 		= $this->smtp_email->send_mail_multiple($emails,$subject,$message);
		}
		if ($response)
		{  
			$res = array('emailType'=>'ES' ); //ES emailSend
		}else{ 
			$res = array('emailType'=>'NS') ; //NS NotSend
		}
		echo json_encode($res);
	}//end function
}//End Class
?>
