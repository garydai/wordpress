<?php


$deviceToken = $argv[1];
#$deviceToken= "7c6d2336 5eb0fbad 4ff39a68 9c3f6392 304bb6a6 fb6ec814 7d3844ba 93ca70a2";
$body = array("aps" => array("alert" => '您上传的图画已被评价',"badge" => 1,"sound"=>'default'));  //推送方式，包含内容和声音$$ctx = stream_context_create();
$ctx = stream_context_create();
//如果在Windows的服务器上，寻找pem路径会有问题，路径修改成这样的方法：
//$pem = dirname(__FILE__) . '/' . 'apns-dev.pem';
//linux 的服务器直接写pem的路径即可
stream_context_set_option($ctx,"ssl","local_cert","/home/admin/nginx/html/wordpress/apns-dev.pem");
$pass = "123456";
stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);//
//此处有两个服务器需要选择，如果是开发测试用，选择第二名sandbox的服务器并使用Dev的pem证书，如果是正式发布，使用Product的pem并选用正式的服务器
#$fp = stream_socket_client("ssl://gateway.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
$fp = stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
if (!$fp) 
    {echo "Failed to connect $err $errstr";return;}
print "Connection OK\n";
  $payload = json_encode($body);
	$msg = chr(0) . pack("n",32) . pack("H*", str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
  echo "sending message :" . $payload ."\n";
#fwrite($fp, $msg);
    $result1 = fwrite($fp, $msg, strlen($msg));  
       
    if (!$result1)  
        echo 'Message not delivered' . PHP_EOL;  
    else  
        echo 'Message successfully delivered' . PHP_EOL;  
fclose($fp);
?>



