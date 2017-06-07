<?php
$index = $_POST['index'];
$id = $_POST['id'];
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
// $index =1;
$sourceFile = $id.$year.$month.$day.'-'.$index;
// if($index!=='1')
$last_line = system('raspistill -q 10 -t 1 -o '.$sourceFile.'.jpg' , $retval);
$hostname = "ec2-13-124-33-214.ap-northeast-2.compute.amazonaws.com";
$username = "ubuntu";
$password = "leastb";
$targetFile = '/var/www/html/darknet/data/'.$sourceFile.'.jpg';
$connection = ssh2_connect($hostname, 22);
ssh2_auth_password($connection, $username, $password);
ssh2_scp_send($connection, $sourceFile.'.jpg', $targetFile, 0777);
system('rm '.$sourceFile.'.jpg', $retval);
$params = array ('filename' => $sourceFile, 'id' => $id, 'year' => $year, 'month' => $month, 'day' => $day);

// Build Http query using params
$query = http_build_query ($params);

// Create Http context details
$contextData = array (
                'method' => 'POST',
                'header' => "Connection: close\r\n".
                            "Content-Length: ".strlen($query)."\r\n",
                'content'=> $query );

// Create context resource for our request
$context = stream_context_create (array ( 'http' => $contextData ));

// Read page rendered as result of your POST request
$result =  file_get_contents (
                  'http://ec2-13-124-33-214.ap-northeast-2.compute.amazonaws.com/darknet/Doyolo.php',  // page url
                  false,
                  $context);
                  echo $result;
// function curl_request_async($url, $params, $type='POST')
// {
//     foreach ($params as $key => &$val)
//     {
//         if (is_array($val))
//             $val = implode(',', $val);
//         $post_params[] = $key.'='.urlencode($val);
//     }
//     $post_string = implode('&', $post_params);
//
//     $parts=parse_url($url);
//
//     if ($parts['scheme'] == 'http')
//     {
//         $fp = fsockopen($parts['host'], isset($parts['port'])?$parts['port']:80, $errno, $errstr, 30);
//     }
//     else if ($parts['scheme'] == 'https')
//     {
//         $fp = fsockopen("ssl://" . $parts['host'], isset($parts['port'])?$parts['port']:443, $errno, $errstr, 30);
//     }
//
//     // Data goes in the path for a GET request
//     if('GET' == $type)
//         $parts['path'] .= '?'.$post_string;
//
//     $out = "$type ".$parts['path']." HTTP/1.1\r\n";
//     $out.= "Host: ".$parts['host']."\r\n";
//     $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
//     $out.= "Content-Length: ".strlen($post_string)."\r\n";
//     $out.= "Connection: Close\r\n\r\n";
//     // Data goes in the request body for a POST request
//     if ('POST' == $type && isset($post_string))
//         $out.= $post_string;
//
//     fwrite($fp, $out);
//     fclose($fp);
// }
//
// curl_post_async("http://ec2-13-124-33-214.ap-northeast-2.compute.amazonaws.com/darknet/Doyolo.php",array ('filename' => $sourceFile));
?>
