<?php

require( dirname(__FILE__) . '/wp-load.php' );
/*
    try {
         $dbh = new PDO("mysql:host='127.0.0.1';dbname='wordpress'",
                        "wordpressuser",
                        "password",
                        array(
                              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                        )
         );
         echo "connect success\n";
    }
    catch(PDOException $e){
         echo "error connect database\n";
         var_dump($e->getMessage());
         die();
    }
  echo  $dbh->get_results("select * from user_comment");
*/
	global $wpdb;
	$query = "select * from user_comment where showed =0 and device_token !='' and comment != '' and notified = 0 ";
	$result = $wpdb->get_results($query);
	foreach($result as $row)
	{
		if($row->device_token != '')
		{
			system("/home/admin/php/bin/php /home/admin/nginx/html/wordpress/pusher.php '{$row->device_token}' " );
			$query = "update user_comment set notified = 1 where id = {$row->id} ";
			$wpdb->get_results($query);
		}
	}




?>
