<?php
/*
		jGallery1.2
		------------------------------------------------------------------
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/



include('config.inc.php');
$C_JGALL['extentions'] = "jpg|jpeg|gif|png";

if(is_dir('themes/' . $C_JGALL['gall_theme']) && file_exists('themes/' . $C_JGALL['gall_theme'] . '/' . $C_JGALL['gall_theme'] . '.css') && file_exists('themes/' . $C_JGALL['gall_theme'] . '/' . $C_JGALL['gall_theme'] . '.conf.php'))
{
   include('themes/' . $C_JGALL['gall_theme'] . '/' . $C_JGALL['gall_theme'] . '.conf.php');
}
else
{
   die('corrupt theme');
}


$MaxSize = $_GET['MaxSize'];

switch ($_GET['src']) {
   case 'folder': 
      $src = $G_JGALL['inc_path'] . 'themes/' . $C_JGALL['gall_theme'] . '/images/folder.jpg';
      break;
   case 'question': 
      $src = $G_JGALL['inc_path'] . 'themes/' . $C_JGALL['gall_theme'] . '/images/no_image.jpg';
      break;
   default: 
      $src=$_GET['src'];
      break;
}

function GetExtention($filename) {
   $FileNameArray = explode('.',$filename);
   return($FileNameArray[count($FileNameArray)-1]);
}
$ext = GetExtention($src);

$srcSize = getImageSize($src);

$srcRatio = $srcSize[0]/$srcSize[1];
$destRatio = $MaxSize/$MaxSize;

if ($destRatio > $srcRatio) {
   $MaxSize = (($C_JGALL['gall_show_filenames'] != 'y') OR isset($_GET['view'])) ? $MaxSize : $MaxSize / 100 * 80;
   $destSize[1] = $MaxSize;
   $destSize[0] = $MaxSize*$srcRatio;
}
else {
   $destSize[0] = $MaxSize;
   $destSize[1] = $MaxSize/$srcRatio;
}

if(eregi($C_JGALL['extentions'],$ext) AND (substr($srcSize['mime'],0,5) == 'image'))
   if(eregi("jpg|jpeg",$ext)) {
      $destImage = imageCreateTrueColor($destSize[0],$destSize[1]);
      $srcImage = imageCreateFromJpeg($src);
      imageCopyResampled($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);
      imageJpeg($destImage,'',80);
   } 
   elseif(eregi("gif",$ext)) {
      $destImage = imageCreateTrueColor($destSize[0],$destSize[1]);
      $srcImage = imageCreateFromGIF($src);
      imageCopyResampled($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);
      imageGIF($destImage,'',80);
   }
   elseif(eregi("png",$ext)) {
      $destImage = imageCreateTrueColor($destSize[0],$destSize[1]);
      $srcImage = imageCreateFromPNG($src);
      imageCopyResampled($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);
      imagePNG($destImage,'',80);
   } 
else {
   die('ongeldige extentie of mime-type.');
}

// --> End
?>