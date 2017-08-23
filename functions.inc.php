<?php
/*
		jGallery 1.3
		------------------------------------------------------------------
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/






// ----------------------------------------------------------------------------------- 
// Function to read dir and return values
// ----------------------------------------------------------------------------------- 
function JGALL_ReadDir($dir='',$return='gall',$sort='no')
{
   global $G_JGALL;
   global $C_JGALL;
   
   $extentions = "jpg|jpeg|gif|png";
   
   $images = array();
   $directorys = array();
   $gallery_items = array();
   
   if($dirhandle = opendir($G_JGALL['inc_path'] . $C_JGALL['gall_dir'] . $dir))
   {
      while(false !== ($file = readdir($dirhandle))) 
      {
         if($file != '.' && $file != '..')
         {
            if(eregi($extentions,JGALL_ext($file)))
            {
               $getimagesize = getimagesize($G_JGALL['inc_path'] . $C_JGALL['gall_dir'] . $dir . $file);
               $mime = explode('/',$getimagesize['mime']);
               if(eregi($extentions,$mime[1]))
               {
                  $images[] = array(
                     'type' => 'image',
                     'name' => $file,
                     'size' => filesize($G_JGALL['inc_path'] . $C_JGALL['gall_dir'] . $dir . $file),
                     'info' => $getimagesize
                  );
                  if(!empty($G_JGALL['get']['img']) && $file == $G_JGALL['get']['img'])
                  {
                     $current = $file;
                  }
               }
            }
            elseif(is_dir($G_JGALL['inc_path'] . $C_JGALL['gall_dir'] . $dir . $file))
            {
               $directorys[] = array(
                  'type' => 'dir',
                  'name' => $file,
                  'first_image' => JGALL_ReadDir($dir . $file . '/','first',$sort)
               );
            }
         }
      }
      closedir($dirhandle);
      // add more sort functions here
      if($C_JGALL['sort_alphabetic'] == 'y')
      {
         sort($directorys);
         sort($images);
      }
      if($return == 'first')
      {
         $folderthumb_size = ceil($C_JGALL['gall_thumb_size'] / 100 * 80 - 20);
         if(empty($images) && empty($directorys))
         {
            $src = 'question';
            $getimagesize = getimagesize($G_JGALL['inc_path'] . 'themes/' . $C_JGALL['gall_theme'] . '/images/no_image.jpg');
            $html_size = JGALL_resize($getimagesize,$folderthumb_size);
            return '<img ' . $html_size . ' border="0" style="' . $G_JGALL['style']['gallery_thumb'] . '" src="' . $G_JGALL['inc_path'] . 'thumb.php?MaxSize=' . $folderthumb_size . '&src=' . $src . '">'; // alt="Open dir: ' . $dir . $file . '">';
         }
         elseif(empty($images) && !empty($directorys))
         {
            return JGALL_ReadDir($dir . $directorys[0]['name'] . '/','first',$sort);
         }
         else
         {
            $src = $C_JGALL['gall_dir'] . $dir . $images[0]['name'];
            $html_size = JGALL_resize($images[0]['info'],$folderthumb_size);
            return '<img ' . $html_size . ' border="0" style="' . $G_JGALL['style']['gallery_thumb'] . '" src="' . $G_JGALL['inc_path'] . 'thumb.php?MaxSize=' . $folderthumb_size . '&src=' . $src . '">'; // alt="Open dir: ' . $dir . $file . '">';
         }
      }
      elseif($return == 'gall')
      {
         $gallery_items = array_merge($directorys,$images);
         $gallery_items['images'] = count($images);
         $gallery_items['directorys'] = count($directorys);
         $gallery_items['total'] = $gallery_items['directorys'] + $gallery_items['images'];
         if(!empty($G_JGALL['get']['img']))
         {
            $gallery_items['first'] = $gallery_items[$gallery_items['directorys']]['name'];
            for($i=$gallery_items['directorys']; $i<$gallery_items['total']; $i++)
            {
               if($gallery_items[$i]['name'] == $current)
               {
                  $gallery_items['prev'] = ($i > $gallery_items['directorys']) ? $gallery_items[$i-1]['name'] : '';
                  $gallery_items['current'] = array(
                     'num' => $i - $gallery_items['directorys'] + 1,
                     'name' => $gallery_items[$i]['name'],
                     'page' => floor($i / ($C_JGALL['gall_cols'] * $C_JGALL['gall_rows'])) + 1,
                     'size' => $gallery_items[$i]['size'],
                     'info' => $gallery_items[$i]['info']
                  );
                  $gallery_items['next'] = ($i < $gallery_items['total']-1) ? $gallery_items[$i+1]['name'] : '';
               }
            }
            $gallery_items['last'] = $gallery_items[$gallery_items['total']-1]['name'];
         }
         return $gallery_items;
      }
   }
   else
   {
      die('<b>Fatal error</b>: Could not read directory: \'' . $G_JGALL['inc_path'] . $C_JGALL['gall_dir'] . $dir . '\'.');
   }
}



// ----------------------------------------------------------------------------------- 
// Function to put stylesheet in to array
// ----------------------------------------------------------------------------------- 
function JGALL_GetStyle($theme)
{
   global $G_JGALL;
   global $C_JGALL;
   
   $path = $G_JGALL['inc_path'] . 'themes/' . $theme . '/';
   $needed = array('body','maintable','location','font.location','pagelink','font.pagelink','gallery_info','font.gallery_info','gallery','font.emptydir','gallery_thumb','gallery_image');
   
   if(is_dir($path) && file_exists($path . $theme . '.css') && file_exists($path . $theme . '.conf.php'))
   {
      include($path . $theme . '.conf.php');
      $css = '<style type="text/css">' . "\n";
      $sheet = file_get_contents($path . $theme . '.css');
      $sheet = preg_replace(array('~/\*.*\*/~Us','~\s\s~','~\r|\n~'),'',$sheet);
      $selectors = explode('}',$sheet);
      $selector_count = count($selectors)-1;
      for($s=0; $s < $selector_count; $s++)
      {
         $selector = explode('{',$selectors[$s]);
         $selector[0] = trim($selector[0]);
         if(preg_match('~^a.*\.JGALL_~',$selector[0]))
         {
            $css .= trim($selector[0]) . '{' . trim($selector[1]) . '}' . "\n";
         }
         elseif(!empty($selector[0]))
         {
            $propertys = explode(';',trim($selector[1]));
            $propertys[$selector[0]] = '';
            $property_count = count($propertys)-1;
            for($p=0; $p < $property_count; $p++)
            {
               $property = preg_split('~:~',trim($propertys[$p]),2);
               if(!empty($property[0]))
               {
                  if(is_numeric(substr(trim($property[1]),0,1)))
                  {
                     $numeric = explode('px',trim($property[1]));
                     $selectors['numeric'][trim($selector[0])][trim($property[0])] = trim($numeric[0]);
                  }
                  $propertys[$selector[0]] .= trim($property[0]) . ':' . str_replace('url(\'','url(\'' . $G_JGALL['inc_path'],trim($property[1])) . ';';
               }
               unset($propertys[$p]);
            }
            $selectors[$selector[0]] = (!empty($propertys[$selector[0]])) ? $propertys[$selector[0]] : '';
         }
         unset($selectors[$s]); 
      }
      foreach($needed as $key => $select)
      {
         $selectors[$select] = (array_key_exists($select,$selectors)) ? $selectors[$select]: '';
      }
      $css .= '</style>' . "\n";
      $selectors['css_output_header'] = ($G_JGALL['filepath'] == $G_JGALL['rootpath']) ? $css : '';
      $selectors['css_output_body'] = ($G_JGALL['filepath'] != $G_JGALL['rootpath']) ? $css : '';
      $selectors['images'] = $path . 'images/';
      return $selectors;
   }
   else
   {
      die(
         '<b>Fatal Error:</b> Theme \'<b>' . $C_JGALL['gall_theme'] . '</b>\' don\'t exist or important file(s) missing in \'<b>jGallery/themes/' . $C_JGALL['gall_theme'] . '/</b>\'.<br /><br />' .
         'Upload valid theme \'' . $C_JGALL['gall_theme'] . '\' to \'jGallery/themes/\' or change $C_JGALL[\'gall_theme\'] in config.inc.php.'
      );
   }
} // -> END function GetStyle()



// ----------------------------------------------------------------------------------- 
// Function to create tabs in front of lines
// ----------------------------------------------------------------------------------- 
function JGALL_TBS($num=0) 
{
   $tab = "   ";
   $temp = '';
   for($i=0; $i<$num; $i++)
   {
      $temp .= $tab;
   }
   return "\n" . $temp;
}



// ----------------------------------------------------------------------------------- 
// Function to get extention 
// ----------------------------------------------------------------------------------- 
function JGALL_ext($filename)
{
    $FileNameArray = explode('.',$filename);
    return($FileNameArray[count($FileNameArray)-1]);
}



// ----------------------------------------------------------------------------------- 
// Function to calculate resized image size
// ----------------------------------------------------------------------------------- 
function JGALL_resize($srcSize,$MaxSize)
{
   global $C_JGALL;
   global $G_JGALL;
   $srcRatio = $srcSize[0]/$srcSize[1];
   $destRatio = $MaxSize/$MaxSize;
   if ($destRatio > $srcRatio)
   {
      $MaxSize = ($C_JGALL['gall_show_filenames'] == 'y' && empty($G_JGALL['get']['img'])) ? $MaxSize / 100 * 80 : $MaxSize;
      $destSize[1] = $MaxSize;
      $destSize[0] = $MaxSize*$srcRatio;
   }
   else 
   {
      $destSize[0] = $MaxSize;
      $destSize[1] = $MaxSize/$srcRatio;
   }
   return 'width="' . floor($destSize[0]) . '" height="' . floor($destSize[1]) . '"';
}



// ----------------------------------------------------------------------------------- 
// Function to get users get-vars
// ----------------------------------------------------------------------------------- 
function JGALL_UserGets() 
{
   $UserGets = '?';
   foreach ($_GET as $key => $value) 
   { 
      if (!eregi('JGALL_',$key)) 
      { 
         $UserGets .= $key.'=' . strip_tags($value); 
         $UserGets .= ($UserGets) ? '&' : '?'; 
      } 
   } 
   return str_replace('../','',strip_tags(str_replace('\\','',$UserGets))); 
} 



// --> END
?>