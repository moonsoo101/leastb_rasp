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
// shell_exec("curl --data 'param1=value1&param2=value2' http://ec2-13-124-33-214.ap-northeast-2.compute.amazonaws.com/darknet/Doyolo.php" . " > /dev/null 2>/dev/null &");
$params = array ('filename' => $sourceFile);

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
?>
