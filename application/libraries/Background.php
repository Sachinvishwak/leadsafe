<?php  
class Background
{
  public function __construct()
  {
      $this->ci =& get_instance();
        $this->background_log_file = 'background_log.txt';    //background file path
  }
  function do_in_background($url, $params)
  {
    $post_string  = http_build_query($params);
    $parts        = parse_url($url);
    $errno        = 0;
    $errstr       = "";
    //Use SSL & port 443 for secure servers
    //Use otherwise for localhost and non-secure servers
    //For secure server
    //$fp = fsockopen('ssl://' . $parts['host'], isset($parts['port'])  ? $parts['port'] : 443, $errno, $errstr, 30);
    switch (ENVIRONMENT)
    {
      case 'development':
        $fp = fsockopen('ssl://' . $parts['host'], isset($parts['port'])  ? $parts['port'] : 443, $errno, $errstr, 30);
      break;

      case 'testing':
        //For localhost and un-secure server
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
      break;  
      case 'production':
        //For localhost and un-secure server
        $fp = fsockopen('ssl://' . $parts['host'], isset($parts['port'])  ? $parts['port'] : 443, $errno, $errstr, 30);
      break;
      default:
        //For localhost and un-secure server
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
    }
    
    if(!$fp)
    {
      log_event("Something going wrong.", $this->background_log_file);  //create log of notifcation
      //   echo "Some thing Problem";    
    }
    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;
    $s = fwrite($fp, $out);
    log_event($s, $this->background_log_file); 
    fclose($fp);
  }//ENd Function
}//ENd Class
?>
