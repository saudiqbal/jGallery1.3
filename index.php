<?php
/*
		jGallery 1.3
		------------------------------------------------------------------
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/




// ----------------------------------------------------------------------------------- 
// Some frontcontrolers:
// -----------------------------------------------------------------------------------
error_reporting(E_ALL);
$G_JGALL = array('version' => '1.3');
$G_JGALL['get']['dir'] = (IsSet($_GET['JGALL_DIR'])) ? str_replace('../','',strip_tags(str_replace('\\','/',$_GET['JGALL_DIR']))) : '';
$G_JGALL['get']['img'] = (IsSet($_GET['JGALL_IMG'])) ? str_replace('../','',strip_tags(str_replace('\\','/',$_GET['JGALL_IMG']))) : '';
$G_JGALL['get']['page'] = (IsSet($_GET['JGALL_PAGE'])) ? preg_replace('/[^0-9]/i','',$_GET['JGALL_PAGE']) : '';



// ----------------------------------------------------------------------------------- 
// Get include path string (see if script is included)
// -----------------------------------------------------------------------------------
$G_JGALL['filepath'] = explode('/',str_replace('\\','/',__FILE__));
$G_JGALL['rootpath'] = explode('/',str_replace('\\','/',$_SERVER['DOCUMENT_ROOT'].strip_tags(str_replace('\\','',$_SERVER['PHP_SELF']))));
$G_JGALL['inc_path'] = '';
if ($G_JGALL['filepath'] != $G_JGALL['rootpath']) 
{
   for($G_JGALL['i_inc']='0'; $G_JGALL['i_inc'] < count($G_JGALL['rootpath']); $G_JGALL['i_inc']++) 
   {
      if ($G_JGALL['filepath'][$G_JGALL['i_inc']] == $G_JGALL['rootpath'][$G_JGALL['i_inc']]) 
      {
         unset($G_JGALL['filepath'][$G_JGALL['i_inc']]);
      }
   }
   unset($G_JGALL['filepath'][$G_JGALL['i_inc']]);
   $G_JGALL['inc_path'] = implode('/',$G_JGALL['filepath']).'/';
}



// ----------------------------------------------------------------------------------- 
// Include files to handle
// -----------------------------------------------------------------------------------
include($G_JGALL['inc_path'] . 'config.inc.php');
include($G_JGALL['inc_path'] . 'language.inc.php');
include($G_JGALL['inc_path'] . 'functions.inc.php');



// -----------------------------------------------------------------------------------
// General presets and important functions:
// -----------------------------------------------------------------------------------
$G_JGALL['UserGets'] = JGALL_UserGets();
$G_JGALL['style'] = JGALL_GetStyle($C_JGALL['gall_theme']);
$G_JGALL['gall_items'] = JGALL_ReadDir($G_JGALL['get']['dir'],'gall');
$G_JGALL['dir_handle'] = $C_JGALL['gall_dir'] . $G_JGALL['get']['dir'];
$G_JGALL['dir_handle_array'] = explode ('/', $G_JGALL['get']['dir']);
$G_JGALL['dir_handle_count'] = count($G_JGALL['dir_handle_array']);
$G_JGALL['dir_handle_up'] = '';
for($G_JGALL['i_up']='1'; $G_JGALL['i_up'] < $G_JGALL['dir_handle_count']-1; $G_JGALL['i_up']++) 
{
   $G_JGALL['dir_handle_up'] .= $G_JGALL['dir_handle_array'][$G_JGALL['i_up']-1].'/'; 
}
$G_JGALL['sort'] = '';
$G_JGALL['tablewidth'] = ($C_JGALL['gall_thumb_size']*$C_JGALL['gall_cols']) + ($C_JGALL['gall_spacing']*($C_JGALL['gall_cols']+1));



// -----------------------------------------------------------------------------------
// Calculate number of pages ($G_JGALL['pages'])
// -----------------------------------------------------------------------------------
$G_JGALL['full_page'] = $C_JGALL['gall_cols'] * $C_JGALL['gall_rows'];
$G_JGALL['pages'] = ceil($G_JGALL['gall_items']['total'] / $G_JGALL['full_page']);
if(!empty($G_JGALL['get']['page'])) 
{
   $G_JGALL['current_page'] = $G_JGALL['get']['page']-1;
   $G_JGALL['i_pages'] = $G_JGALL['current_page']*($C_JGALL['gall_cols']*$C_JGALL['gall_rows']);
   $G_JGALL['Max'] = $G_JGALL['i_pages']+($C_JGALL['gall_cols']*$C_JGALL['gall_rows']);
   if($G_JGALL['Max'] > $G_JGALL['gall_items']['total']) 
   {
      $G_JGALL['Max'] = $G_JGALL['gall_items']['total'];
   }
}
else 
{
   $G_JGALL['i_pages'] = '0';
   $G_JGALL['Max'] = $C_JGALL['gall_cols']*$C_JGALL['gall_rows'];
   if ($G_JGALL['Max'] > $G_JGALL['gall_items']['total']) {
      $G_JGALL['Max'] = $G_JGALL['gall_items']['total'];
   }
}
$G_JGALL['temp_start'] = $G_JGALL['i_pages'];



// -----------------------------------------------------------------------------------
// CREATE MENU BAR -> Output to $C_JGALL['output_location'] 
// -----------------------------------------------------------------------------------
$C_JGALL['output_location'] = '';
$G_JGALL['mainfolder'] = '<b>' . $C_JGALL['lang_mainfolder'] . '</b>';
$G_JGALL['button_home'] = '<img src="' . $G_JGALL['style']['images'] . 'homefolder_gray.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_home'] . '">';
$G_JGALL['button_up'] = '<img src="' . $G_JGALL['style']['images'] . 'upfolder_gray.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_up'] . '">';
$G_JGALL['key_current'] = 0;
if((!empty($G_JGALL['get']['dir'])) OR ($G_JGALL['get']['page'] > 1) OR !empty($G_JGALL['get']['img'])) 
{
   $G_JGALL['button_home'] = '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR"><img src="' . $G_JGALL['style']['images'] . 'homefolder.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_gohome'] . '"></a>';
   if(!empty($G_JGALL['get']['dir'])) 
   {
      $G_JGALL['mainfolder'] = '<a class="JGALL_location" href="'.$G_JGALL['UserGets'].'JGALL_DIR">' . $C_JGALL['lang_mainfolder'] . '</a>&nbsp;/&nbsp;';
      $G_JGALL['button_up'] = '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['dir_handle_up'] . '"><img src="' . $G_JGALL['style']['images'] . 'upfolder.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_goup'] . '"></a>';
      $G_JGALL['key_current'] = $G_JGALL['dir_handle_count']-2;
   }
   elseif(!empty($G_JGALL['get']['img']) && file_exists($G_JGALL['inc_path'] . $G_JGALL['dir_handle'] . $G_JGALL['get']['img']))
   {
      $G_JGALL['mainfolder'] = '<a class="JGALL_location" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=&JGALL_PAGE=' . $G_JGALL['gall_items']['current']['page'] . '">' . $C_JGALL['lang_mainfolder'] . '</a>';
   }
}
$C_JGALL['output_location'] .= JGALL_TBS(1) . '<tr>';
$C_JGALL['output_location'] .= JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['location'] . '" width="' . $G_JGALL['tablewidth'] . '">';
$C_JGALL['output_location'] .= JGALL_TBS(3) . '<table width="' . $G_JGALL['tablewidth'] . '">';
$C_JGALL['output_location'] .= JGALL_TBS(4) . '<tr>';
$C_JGALL['output_location'] .= JGALL_TBS(5) . '<td align="left"><font style="' . $G_JGALL['style']['font.location'] . '">';
$C_JGALL['output_location'] .= JGALL_TBS(6) . '&nbsp;<b>' . $C_JGALL['lang_location'] . ':</b>&nbsp;&nbsp;' . $G_JGALL['mainfolder'];

$G_JGALL['crumb'] = '';
for($G_JGALL['i_crumb']=1; $G_JGALL['i_crumb'] < $G_JGALL['dir_handle_count']-1; $G_JGALL['i_crumb']++) 
{
   $G_JGALL['crumb'] .= $G_JGALL['dir_handle_array'][$G_JGALL['i_crumb']-1].'/';
   $C_JGALL['output_location'] .= '<a class="JGALL_location" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['crumb'] . '">' . $G_JGALL['dir_handle_array'][$G_JGALL['i_crumb']-1] . '</a>&nbsp;/&nbsp;';
}
if(!empty($G_JGALL['get']['img']) && file_exists($G_JGALL['inc_path'] . $G_JGALL['dir_handle'] . $G_JGALL['get']['img']))
{
   $C_JGALL['output_location'] .= '<a class="JGALL_location" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_PAGE=' . $G_JGALL['gall_items']['current']['page'] . '">' . $G_JGALL['dir_handle_array'][$G_JGALL['i_crumb']-1] . '</a>';
   $C_JGALL['output_location'] .= '&nbsp;/&nbsp;<b>' . $G_JGALL['get']['img'] . '</b>';
}
else
{
   $C_JGALL['output_location'] .= '<b>' . $G_JGALL['dir_handle_array'][$G_JGALL['key_current']] . '</b>';
}
$C_JGALL['output_location'] .= JGALL_TBS(5) . '</font></td>' . JGALL_TBS(5) . '<td align="right">';
$C_JGALL['output_location'] .= JGALL_TBS(6) . '&nbsp;' . $G_JGALL['button_home'] . '&nbsp;&nbsp;' . $G_JGALL['button_up'] . '&nbsp;&nbsp;<a href="javascript:location.reload();" target="_self"><img src="' . $G_JGALL['style']['images'] . 'refresh.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_refresh'] . '"></a>&nbsp;';
/*		Nice to see you like this script! You're welcome!
		In return, please do not remove the info-button, 
		or place a link to us on your website. Thank you!
		http://portal.kooijman-design.nl/jGallery/				*/
$C_JGALL['output_location'] .= '&nbsp;<a href="http://portal.kooijman-design.nl/jGallery/README.html" target="_blank"><img src="' . $G_JGALL['style']['images'] . 'info.gif" border="0" align="absmiddle" alt="powered by: jGallery"></a>&nbsp;';
$C_JGALL['output_location'] .= JGALL_TBS(5) . '</td>' . JGALL_TBS(4) . '</tr>' . JGALL_TBS(3) . '</table>' . JGALL_TBS(2) . '</td>' . JGALL_TBS(1) . '</tr>';



// -----------------------------------------------------------------------------------
// CREATE PAGE LINKS -> Output to $C_JGALL['output_pagelinks']
// -----------------------------------------------------------------------------------
$C_JGALL['output_pagelinks'] = '';
if(!empty($G_JGALL['gall_items']['total']) && $G_JGALL['gall_items']['total'] > $G_JGALL['full_page'])
{
   $C_JGALL['output_pagelinks'] .= JGALL_TBS(1) . '<tr>' . JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['pagelink'] . '" align="center" width="' . $G_JGALL['tablewidth'] . '"><font style="' . $G_JGALL['style']['font.pagelink'] . '">';
   if(!empty($G_JGALL['get']['img']) && file_exists($G_JGALL['inc_path'].$G_JGALL['dir_handle'].$G_JGALL['get']['img']))
   {
      // If you are Viewing one image
      if($G_JGALL['gall_items']['current']['num'] > 1)
      {
         // « first | prev
         $C_JGALL['output_pagelinks'] .= JGALL_TBS(3) . '&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_IMG=' . $G_JGALL['gall_items'][$G_JGALL['gall_items']['directorys']]['name'] . '">' . $C_JGALL['lang_first'] . '</a>&nbsp;&#124;&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_IMG=' . $G_JGALL['gall_items']['prev'] . '">' . $C_JGALL['lang_prev'] . '</a>&nbsp;&#124;';
      }
      $C_JGALL['output_pagelinks'] .= JGALL_TBS(3) . '&nbsp;' . str_replace('{CURRENT}',$G_JGALL['gall_items']['current']['num'],str_replace('{TOTAL}',$G_JGALL['gall_items']['images'],$C_JGALL['lang_image_info'])) . '&nbsp;';
      if($G_JGALL['gall_items']['current']['num'] < $G_JGALL['gall_items']['images'])
      {
         // next | last »
         $C_JGALL['output_pagelinks'] .= JGALL_TBS(3) . '&#124;&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_IMG=' . $G_JGALL['gall_items']['next'] . '">' . $C_JGALL['lang_next'] . '</a>&nbsp;&#124;&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'].'&JGALL_IMG=' . $G_JGALL['gall_items']['last'] . '">' . $C_JGALL['lang_last'] . '</a>&nbsp;';
      }
   }
   else
   {
      // If you are viewing thumbnails
      if ($G_JGALL['pages'] > 1) 
      {
         $G_JGALL['current'] = (!empty($G_JGALL['get']['page']) && is_numeric($G_JGALL['get']['page'])) ? $G_JGALL['get']['page'] : 1;
         $prev = $G_JGALL['current']-1;
         $next = $G_JGALL['current']+1;
         if($G_JGALL['current'] > '1') 
         {
            // « first | prev
            $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_PAGE=1">' . $C_JGALL['lang_first'] . '</a>&nbsp;&#124;&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_PAGE=' . $prev . '">' . $C_JGALL['lang_prev'] . '</a>&nbsp;&#124;' ;
         }
         for ($i=1; $i<=$G_JGALL['pages']; $i++) 
         {
            if($i == $G_JGALL['current']) 
            {
               $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<b>' . $i . '</b>&nbsp;';
            }
            else 
            {
               $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_PAGE='. $i .'"><b>' . $i . '</b></a>&nbsp;';
            }
         }
         if($G_JGALL['current'] < $G_JGALL['pages']) 
         {
            // next | last »
            $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&#124;&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_PAGE=' . $next . '">' . $C_JGALL['lang_next'] . '</a>&nbsp;&#124;&nbsp;<a class="JGALL_pagelink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_PAGE=' . $G_JGALL['pages'] . '">' . $C_JGALL['lang_last'] . '</a>&nbsp;';
         } 
      }
   }
   $C_JGALL['output_pagelinks'] .=  JGALL_TBS(2) . '</font></td>' . JGALL_TBS(1) . '</tr>';
}



// -----------------------------------------------------------------------------------
// Build layout order:
// -----------------------------------------------------------------------------------
foreach($C_JGALL['layout_order'] as $key => $value)
{
    $C_JGALL['layout_order'][$key] = '{' . strtoupper($value) . '}';
}
$G_JGALL['output_order'] = $C_JGALL['layout_order'][0] . '{FIRST_SPACING}' . $C_JGALL['layout_order'][1] . '{SECOND_SPACING}' . $C_JGALL['layout_order'][2];

foreach($C_JGALL['layout_spacing'] as $key => $spacing)
{
   $G_JGALL['output_spacing'][$key] = '';
   if($C_JGALL['layout_spacing'][$key] > '0')
   {
      $G_JGALL['output_spacing'][$key] = JGALL_TBS(1) . '<tr name="spacing" cellspacing="0" cellpadding="0" border="0"><td height="' . $spacing . '" width="' . $G_JGALL['tablewidth'] . '"></td></tr>';
   }
}
$G_JGALL['output_order'] = explode('{MAIN}',$G_JGALL['output_order']);



// -----------------------------------------------------------------------------------
// Place output into position and parse template:
// -----------------------------------------------------------------------------------
$G_JGALL['nav_output_top'] = str_replace('{LOCATION}',$C_JGALL['output_location'],$G_JGALL['output_order'][0]);
$G_JGALL['nav_output_top'] = str_replace('{PAGELINK}',$C_JGALL['output_pagelinks'],$G_JGALL['nav_output_top']);
$G_JGALL['nav_output_top'] = str_replace('{FIRST_SPACING}',$G_JGALL['output_spacing']['first'],$G_JGALL['nav_output_top']);
$G_JGALL['nav_output_top'] = str_replace('{SECOND_SPACING}',$G_JGALL['output_spacing']['second'],$G_JGALL['nav_output_top']);

$G_JGALL['nav_output_bottom'] = str_replace('{LOCATION}',$C_JGALL['output_location'],$G_JGALL['output_order'][1]);
$G_JGALL['nav_output_bottom'] = str_replace('{PAGELINK}',$C_JGALL['output_pagelinks'],$G_JGALL['nav_output_bottom']);
$G_JGALL['nav_output_bottom'] = str_replace('{FIRST_SPACING}',$G_JGALL['output_spacing']['first'],$G_JGALL['nav_output_bottom']);
$G_JGALL['nav_output_bottom'] = str_replace('{SECOND_SPACING}',$G_JGALL['output_spacing']['second'],$G_JGALL['nav_output_bottom']);



// -----------------------------------------------------------------------------------
// Start output:
// -----------------------------------------------------------------------------------
if($G_JGALL['filepath'] == $G_JGALL['rootpath']) 
{
   echo '<html>' . "\n" . '<head>' . "\n" . '<title>' . $C_JGALL['lang_title'] . '</title>' . "\n" . $G_JGALL['style']['css_output_header'] . '</head>' . "\n\n" . '<body style="' . $G_JGALL['style']['body'] . '">' . "\n\n";
}
echo '<div align="center" width="' . $G_JGALL['tablewidth'] . '">' . "\n" . $G_JGALL['style']['css_output_body'];
echo '<table name="jGalery' . $G_JGALL['version'] . '" cellspacing="0" cellpadding="0" border="0" style="' . $G_JGALL['style']['maintable'] . '" width="' . $G_JGALL['tablewidth'] . '">';



// -----------------------------------------------------------------------------------
// Output items on top of image-viewer
// -----------------------------------------------------------------------------------
echo $G_JGALL['nav_output_top'];



// -----------------------------------------------------------------------------------
// Output thumbnails or view image 
// -----------------------------------------------------------------------------------
$G_JGALL['view_image_size'] = (!empty($C_JGALL['gall_max_view_img_size'])) ? $C_JGALL['gall_max_view_img_size'] : $G_JGALL['tablewidth'] - ($C_JGALL['gall_spacing'] * 2);
$C_JGALL['gall_folder_size'] = $C_JGALL['gall_thumb_size'];
$spacing_keys = array('border','border-left','border-right','padding','padding-left','padding-right','margin','margin-left','margin-right','spacing','spacing-left','spacing-right');
foreach($spacing_keys as $property)
{
   $G_JGALL['view_image_size'] -= (!empty($G_JGALL['style']['numeric']['gallery_image'][$property])) ? $G_JGALL['style']['numeric']['gallery_image'][$property] * 2 : 0;
   $C_JGALL['gall_thumb_size'] -= (!empty($G_JGALL['style']['numeric']['gallery_thumb'][$property])) ? $G_JGALL['style']['numeric']['gallery_thumb'][$property] * 2 : 0;
}
echo JGALL_TBS(1) . '<tr>' . JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['gallery'] . '" width="' . $G_JGALL['tablewidth'] . '">';
echo JGALL_TBS(3) . '<table cellspacing="' . $C_JGALL['gall_spacing'] . '" border="0" cellpadding="0" width="' . $G_JGALL['tablewidth'] . '">';
if(!empty($G_JGALL['get']['img']) && file_exists($G_JGALL['inc_path'].$G_JGALL['dir_handle'].$G_JGALL['get']['img']))
{
   $G_JGALL['show_info'] = 'image';
   $G_JGALL['image_link_start'] = (eregi($C_JGALL['gall_link2source'],'y')) ? '<a href="' . $G_JGALL['inc_path'] . $G_JGALL['dir_handle'] . $G_JGALL['get']['img'] . '" target="_blank">' : ' ' ;
   $G_JGALL['image_link_end'] = (eregi($C_JGALL['gall_link2source'],'y')) ? '</a>' : '';
   echo JGALL_TBS(4) . '<tr>' . JGALL_TBS(5) . '<td align="center">';
   echo JGALL_TBS(6) . $G_JGALL['image_link_start'] . '<img name="image" ' . JGALL_resize($G_JGALL['gall_items']['current']['info'],$G_JGALL['view_image_size']) . ' border="0" style="' . $G_JGALL['style']['gallery_thumb'] . ';" src="' . $G_JGALL['inc_path'] . 'thumb.php?MaxSize=' . $G_JGALL['view_image_size'] . '&src=' . $G_JGALL['dir_handle'] . $G_JGALL['get']['img'] . '&view=y" alt="' . $C_JGALL['lang_downloadimage'] . ': ' . $G_JGALL['get']['img'] . '">' . $G_JGALL['image_link_end'];
   echo JGALL_TBS(5) .'</td>' . JGALL_TBS(4) . '</tr>';
}
else
{
   $G_JGALL['show_info'] = 'gall';
   if (!empty($G_JGALL['gall_items']['total']))
   {
      $C_JGALL['x'] = '0'; 
      $C_JGALL['y'] = '0';
      for ($G_JGALL['i_pages']=$G_JGALL['i_pages']; $G_JGALL['i_pages']<=$G_JGALL['Max']-1; $G_JGALL['i_pages']++) 
      {
         if(0==0) 
         {
            $C_JGALL['x']++;
   
            if($C_JGALL['x'] == 1) {
               echo JGALL_TBS(4) . '<tr>'; 
               $C_JGALL['y']++;
            }
            // For images
            if ($G_JGALL['gall_items'][$G_JGALL['i_pages']]['type'] == 'image') 
            {
               $G_JGALL['show_filename'] = ($C_JGALL['gall_show_filenames'] == 'y') ? '<br /><a class="JGALL_thumblink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_IMG=' . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '">' . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '</a>' : '';
               $G_JGALL['this_thumb_height'] = ($C_JGALL['gall_show_filenames'] == 'y') ? 'height="' . $C_JGALL['gall_thumb_size'] . '"' : '';
               echo JGALL_TBS(5) . '<td align="center" valign="middle" align="center" width="' . $C_JGALL['gall_thumb_size'] . '"' . $G_JGALL['this_thumb_height'] . '>';
               echo JGALL_TBS(6) . '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . '&JGALL_IMG=' . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '"><img ' . JGALL_resize($G_JGALL['gall_items'][$G_JGALL['i_pages']]['info'],$C_JGALL['gall_thumb_size']) . ' border="0" style="' . $G_JGALL['style']['gallery_thumb'] . '" src="' . $G_JGALL['inc_path'] . 'thumb.php?MaxSize=' . $C_JGALL['gall_thumb_size'] . '&src=' . $G_JGALL['dir_handle'] . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '" alt="' . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '">' . $G_JGALL['show_filename'] . '</a>';
               echo JGALL_TBS(5) . '</td>';
            }
            // For folders
            else
            {
               $G_JGALL['FolderTopSpacerHeight'] = round($C_JGALL['gall_thumb_size'] / 100 * 14);
               $G_JGALL['FolderBottomSpacerHeight'] = round($C_JGALL['gall_thumb_size'] / 100 * 8);
               $G_JGALL['FolderMainSpacerHeight'] = $C_JGALL['gall_thumb_size'] - ($G_JGALL['FolderTopSpacerHeight'] + $G_JGALL['FolderBottomSpacerHeight']);
               echo JGALL_TBS(5) . '<td width="' . $C_JGALL['gall_folder_size'] . '" height="' . $C_JGALL['gall_folder_size'] . '" style="background:url(\'' . $G_JGALL['inc_path'] . 'thumb.php?MaxSize=' . $C_JGALL['gall_folder_size'] . '&src=folder\');background-repeat:no-repeat;cursor:hand;cursor:pointer;" onclick="top.location.href=\'' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '/' . '\'" valign="middle">';
               echo JGALL_TBS(6) . '<table width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $C_JGALL['gall_thumb_size'] . '" cellspacing="0" cellpadding="0">' . JGALL_TBS(7) . '<tr>';
               echo JGALL_TBS(8) . '<td width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $G_JGALL['FolderTopSpacerHeight'] . '"><img src="' . $G_JGALL['style']['images'] . 'spacer.gif" width="' . $C_JGALL['gall_folder_size'] . '" height="' . $G_JGALL['FolderTopSpacerHeight'] . '"></td>';
               echo JGALL_TBS(7) . '</tr>' . JGALL_TBS(7) . '<tr>';
               echo JGALL_TBS(8) . '<td width="' . $C_JGALL['gall_folder_size'] . '" height="' . $G_JGALL['FolderMainSpacerHeight'] . '" valign="middle" align="center">';
               echo JGALL_TBS(9) . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['first_image'] . '<br />';
               echo JGALL_TBS(9) . '<a class="JGALL_folderlink" href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['get']['dir'] . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '/' . '">' . $G_JGALL['gall_items'][$G_JGALL['i_pages']]['name'] . '</a>';
               echo JGALL_TBS(8) . '</td>' . JGALL_TBS(7) . '</tr>' . JGALL_TBS(7) . '<tr>';
               echo JGALL_TBS(8) . '<td width="' . $C_JGALL['gall_folder_size'] . '" height="' . $G_JGALL['FolderBottomSpacerHeight'] . '"><img src="' . $G_JGALL['style']['images'] . 'spacer.gif" width="' . $C_JGALL['gall_folder_size'] . '" height="' . $G_JGALL['FolderBottomSpacerHeight'] . '"></td>';
               echo JGALL_TBS(7) . '</tr>' . JGALL_TBS(6) . '</table>' . JGALL_TBS(5) . '</td>';
            }
            if ($C_JGALL['x'] == $C_JGALL['gall_cols']) 
            {
               echo JGALL_TBS(4) . '</tr>'; 
               $C_JGALL['x'] = '0';
            }
         }
      }
      if($C_JGALL['x'] != '0') 
      {
         echo JGALL_TBS(4) . '</tr>'; 
      }
   }
   else 
   {
      echo JGALL_TBS(4) . '<tr>' . JGALL_TBS(5) . '<td colspan="' . $C_JGALL['gall_cols'] . '" align="center">';
      echo JGALL_TBS(6) . '<br /><font style="' . $G_JGALL['style']['font.emptydir'] . '"' . JGALL_TBS(6) . '<h3>' . $C_JGALL['lang_emptydir'] . '</font><br /><br />';
      echo JGALL_TBS(5) . '</td>' . JGALL_TBS(4) . '</tr>';
   }
}
echo JGALL_TBS(3) . '</table>';
echo JGALL_TBS(2) . '</td>' . JGALL_TBS(1) . '</tr>';

if($C_JGALL['gall_show_info'] == 'y')
{
   echo JGALL_TBS(1) . '<tr>' . JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['gallery_info'] . '" align="center"><font style="' . $G_JGALL['style']['font.gallery_info'] . '">';
   if($G_JGALL['show_info'] == 'image')
   {
      $C_JGALL['lang_image_detail'] = str_replace('{NAME}',$G_JGALL['gall_items']['current']['name'],$C_JGALL['lang_image_detail']);
      $C_JGALL['lang_image_detail'] = str_replace('{WIDTH}',$G_JGALL['gall_items']['current']['info'][0],$C_JGALL['lang_image_detail']);
      $C_JGALL['lang_image_detail'] = str_replace('{HEIGHT}',$G_JGALL['gall_items']['current']['info'][1],$C_JGALL['lang_image_detail']);
      $C_JGALL['lang_image_detail'] = str_replace('{FILESIZE}',round($G_JGALL['gall_items']['current']['size']/1000),$C_JGALL['lang_image_detail']);
      $C_JGALL['lang_image_detail'] = str_replace('{MIME}',$G_JGALL['gall_items']['current']['info']['mime'],$C_JGALL['lang_image_detail']);
      echo JGALL_TBS(3) . $C_JGALL['lang_image_detail'];
   }
   elseif($G_JGALL['show_info'] == 'gall')
   {
      $C_JGALL['lang_gall_detail'] = str_replace('{FIRST}',$G_JGALL['temp_start']+1,$C_JGALL['lang_gall_detail']);
      $C_JGALL['lang_gall_detail'] = str_replace('{LAST}',$G_JGALL['Max'],$C_JGALL['lang_gall_detail']);
      $C_JGALL['lang_gall_detail'] = str_replace('{TOTAL}',$G_JGALL['gall_items']['total'],$C_JGALL['lang_gall_detail']);
      $C_JGALL['lang_gall_detail'] = str_replace('{FOLDERS}',$G_JGALL['gall_items']['directorys'],$C_JGALL['lang_gall_detail']);
      $C_JGALL['lang_gall_detail'] = str_replace('{IMAGES}',$G_JGALL['gall_items']['images'],$C_JGALL['lang_gall_detail']);
      $C_JGALL['lang_gall_detail'] = str_replace('{DIR}',$C_JGALL['lang_mainfolder'] . '/' . $G_JGALL['get']['dir'],$C_JGALL['lang_gall_detail']);
      echo JGALL_TBS(3) . $C_JGALL['lang_gall_detail'];
   }
   echo JGALL_TBS(2) . '</font></td>' . JGALL_TBS(1) . '</tr>';
}



// -----------------------------------------------------------------------------------
// Output items at the bottom of image-viewer
// -----------------------------------------------------------------------------------
echo $G_JGALL['nav_output_bottom'];



// -----------------------------------------------------------------------------------
// End output
// -----------------------------------------------------------------------------------
echo "\n" . '</table>' . "\n";
echo '</div>' . "\n\n";
if($G_JGALL['filepath'] ==  $G_JGALL['rootpath']) 
{
   echo'</body>' . "\n" . '</html>';
}



// --> End
?>