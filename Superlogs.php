<?php
function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}
$absolute_url = full_url( $_SERVER );
$extension = pathinfo($absolute_url, PATHINFO_EXTENSION); 

if (1==1 &&  !isset($_COOKIE['ghost'])){

try {
     $db = new PDO("mysql:host=localhost;dbname=DBNAME", "DBUSERNAME", "PASSWORD");
} catch ( PDOException $e ){

}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}



function recursive_print ($array,$m='')
{
    $m.= "\n";
    foreach ($array as $k => $v)
    {
        $m.= $k;

        if (is_array ($v) || is_object ($v)) $m.=recursive_print ($v);
        else $m.= ' => ' . $v;

        $m.="\n";
    }
   return $m;
}

  $gets= recursive_print($_GET);

  $posts= recursive_print($_POST);


$cnn=explode('/',$absolute_url);
if (!isset($cnn[4])){
      $cnn='';
}else{
   $cnn=$cnn[4];
}
   
   $query = $db->prepare("INSERT INTO `superlogs` (`id`,  `userip`, `timestamp`, `url`,`posts`, `gets`) VALUES (NULL,  :userip, :timestamp, :url,  :posts, :gets);");
   $insert = $query->execute(array(
      ":userip" => get_client_ip(),
      ":timestamp" => time(),
      ":url" => $absolute_url,
      ":posts" => $posts,
      ":gets" => $gets,
));
/*
if ( $insert ){
  $last_id = $db->lastInsertId();
 }else{
    echo 'hata'; 
       print_r($db->errorInfo()); 
}*/
$db = null;
   
}

?>