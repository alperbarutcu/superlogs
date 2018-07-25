<?php
$hostaddr = ""; //your mysql url => for example localhost
$dbname   = ''; ///your mysql db name
$username = ''; //your mysql username
$password = ''; //your mysql password
function url_origin($s, $use_forwarded_host = false)
{
    $ssl      = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
    $sp       = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port     = $s['SERVER_PORT'];
    $port     = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    $host     = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host     = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url($s, $use_forwarded_host = false)
{
    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
$absolute_url = full_url($_SERVER);
$extension    = pathinfo($absolute_url, PATHINFO_EXTENSION);

    
    try {
        $db = new PDO("mysql:host=" . $hostaddr . ";dbname=" . $dbname, $username, $password);
    }
    catch (PDOException $e) {
        //print $e->getMessage();  //is there any problem? open this line
    }
    
    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }    
    function recursive_print($array, $m = '')
    {
        $m .= "\n";
        foreach ($array as $k => $v) {
            $m .= $k;
            
            if (is_array($v) || is_object($v))
                $m .= recursive_print($v);
            else
                $m .= ' => ' . $v;
            
            $m .= "\n";
        }
        return $m;
    }    
    $gets  = recursive_print($_GET);
    $posts = recursive_print($_POST);  
    $query  = $db->prepare("INSERT INTO `superlogs` (`id`,  `userip`, `timestamp`, `url`,`posts`, `gets`) VALUES (NULL,  :userip, :timestamp, :url,  :posts, :gets);");
    $insert = $query->execute(array(
        ":userip" => get_client_ip(),
        ":timestamp" => time(),
        ":url" => $absolute_url,
        ":posts" => $posts,
        ":gets" => $gets
    ));
    /*     
    //is there any problem? open this block 
    if ( !$insert ){
    print_r($db->errorInfo()); 
    }
    */
    $db     = null;?>
