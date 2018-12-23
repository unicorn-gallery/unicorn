<?php
namespace Mre\Unicorn\lib;

require 'vendor/autoload.php';
require_once ("config.php");

function listFolderFiles($dir, $ext = "jpg"){
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

	$retVal = array();
    foreach($ffs as $ff){
        if(strpos(strtolower($ff), $ext) && strpos($dir, "thumbs") == 0 ) {
			$retVal[] = $dir."/".$ff;
		}
        if(is_dir($dir.'/'.$ff)) {
			$retVal = array_merge($retVal, listFolderFiles($dir.'/'.$ff, $ext));
		}
    }
	return $retVal;
}

$current = isset($_GET['current']) && is_numeric($_GET['current']) ? $_GET['current'] : -1;
$files = listFolderFiles("cache/mails");
$next = $current;
if($current == -1) {
	$next = rand(0, count($files) -1);
} else if($next >= 0) {
	$next++; // next image
	if(count($files) <= $next) {
		$next = 0;
	}
} else {
	$next = 0;
}
$theImage = $files[$next];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="<?php echo Config::read("image_refresh")?>; url=<?php echo Config::read("site_url")?>?current=<?php echo $next;?>" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title><?php echo Config::read("gallery_name")?></title>

<style>
body {background-color: black;}
#fixed-div {    position: fixed;    top: 1em;    right: 1em; }
</style>

</head>

<body>
<div id="fixed-div">
</div>
<?php
echo "<img src=\"".$theImage."\" width=\"100%\"/>";
?>


</body>

</html>