<?php
/* memeroy usage*/
print(memory_get_usage() . "<br>");
ob_start();

for ($i = 0; $i < 5000000; $i++)
    print(" ");

print(memory_get_usage() . "<br>");
$foo = ob_get_contents();
print(memory_get_usage() . "<br>");
ob_end_flush();
die();

/*suffle random*/
function shuffle_keys(&$array){
    $keys = array_keys($array);

    shuffle($keys);

    $result = array();

    foreach($keys as $key){ $result[$key] = $array[$key]; }

    $array = $result;
}
$answers = array(0,2,3,4,5,6,7);
shuffle_keys($answers);
foreach($answers as $value){
        echo $value;
        echo "<br>";
}
die();

?>

<?php
$a = 5388+360;$b = 1;
$a = 'TEC';
$a++;
echo $a;
// for ($i=5389; $i <= 5748 ; $i++){
//     $a = $i + 1;
//     echo "INSERT INTO  el_rpilihan (kd_pilihan,kd_test,type_pilihan,user_update,last_update,pilihan) values ('$i',";
//     // echo "INSERT INTO  el_jabatan (kd_jabatan) values ($i);";
//     // echo "UPDATE el_jabatan SET kd_jabatan = '$a' WHERE id_jabatan = '$i';";
//     echo "<br>";
//     $b++;
// }
die();
?>
<!-- SetCookie Countdown-->
<!DOCTYPE html>
<html>
<head>
	<title>Count</title>
</head>
<body>
	<div id="cd"></div>
	<form name="counter">
    	<input type="text" size="8" name="chandresh" id="counter">
	</form>
</body>
</html>
<script type="text/javascript">
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

var cnt = 60;
function counter(){
    if(getCookie("cnt") > 0){
        cnt = getCookie("cnt");
    }
    cnt -= 1;
    document.cookie = "cnt="+ cnt;
    var minutes = Math.floor(cnt / 60);
    var seconds = cnt - minutes * 60;
    var a = ('0' + minutes).slice(-2);  // '04'
    var b = ('0' + seconds).slice(-2);  // '04'
    document.getElementById("counter").value = JSON.parse(getCookie("cnt"));
    document.getElementById("counter").value = minutes+":"+seconds;

    if(cnt>0){
        setTimeout(counter,1000);
    }

}
counter();
</script>
<!-- testMinutes -->
<?php
$jam      = 22;
$intJam   = 24;
$menit    = 22;
$intMenit = 60;
if($menit >= $intMenit){
	$menit = $menit - $intMenit;
	$jam++;
}
if($jam >= $intJam){
	$jam = $jam - $intJam;
}
echo $jam.":".$menit;
die();

/*recored times*/
$tRecordStart = microtime(true);
function recordTime () {
  global $tRecordStart;
  $tS = microtime(true);
  $tElapsedSecs = $tS - $tRecordStart;
  $sElapsedSecs = str_pad(number_format($tElapsedSecs, 3), 10, " ", STR_PAD_LEFT);
  echo 'Excute time render page: '.$sElapsedSecs."s"."<br></br>";
}

for ($i = 0; $i < 94959; $i ++) {
  recordTime();
}
die();

/* snappy */
require __DIR__ . '/vendor/autoload.php';
use Knp\Snappy\Pdf;
$snappy = new Pdf;
$snappy->setBinary('C://"Program Files"/wkhtmltopdf/bin/wkhtmltopdf.exe');
// echo $snappy->generateFromHtml($html, 'temp/f.pdf');
header('Content-Type: application/pdf');
// header('Content-Disposition: attachment; filename="file.pdf"');
echo $snappy->getOutput('http://localhost/test/content.php', array(
	// 'orientation'=>'Landscape',
	// 'javascript-delay' => 1000, 
	'no-stop-slow-scripts' => true, 
	// 'no-background' => false, 
	'lowquality' => true,
	'page-height' => 420,
	'page-width'  => 310,
	// 'page-size' => 'A4',
	// 'encoding' => 'utf-8',
	// 'images' => true,
	// 'cookie' => array(),
	// 'dpi' => 300,
	// 'image-dpi' => 300,
	// 'enable-external-links' => true,
	// 'enable-internal-links' => true
	));
die();

//  index.php:
require_once('top.php');

echo "<html>\n<head>\n<title>Gzip Test</title>\n<body>\n<h1>testing</h1>\n</body>\n</html>";

require_once('bottom.php');
?>

<!-- gz_header.php - taken form phpBB's page_header.php -->
<?php
    $phpver = phpversion();

    $useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;

    if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
    {
        if ( extension_loaded('zlib') )
        {
            ob_start('ob_gzhandler');
        }
    }
    else if ( $phpver > '4.0' )
    {
        if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
        {
            if ( extension_loaded('zlib') )
            {
                $do_gzip_compress = TRUE;
                ob_start();
                ob_implicit_flush(0);

                header('Content-Encoding: gzip');
            }
        }
    }
?>
<!-- gz_footer.php - taken form phpBB's page_tail.php -->
<?php
// Compress buffered output if required and send to browser
if ( $do_gzip_compress )
{
    //
    // Borrowed from php.net!
    //
    $gzip_contents = ob_get_contents();
    ob_end_clean();

    $gzip_size = strlen($gzip_contents);
    $gzip_crc = crc32($gzip_contents);

    $gzip_contents = gzcompress($gzip_contents, 9);
    $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

    echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
    echo $gzip_contents;
    echo pack('V', $gzip_crc);
    echo pack('V', $gzip_size);
}

exit;
die();