<?php
/*
		jGallery 1.3
		------------------------------------------------------------------
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/



// -----------------------------------------------------------------------------------
// Languge configuration section
// -----------------------------------------------------------------------------------
	
	
	// Set text to indicate 'title' in html header.
	$C_JGALL['lang_title'] = 'jGallery - PHP based photo album viewer V1.3';
	
	
	// Set text to indicate 'Location' in top-menu bar.
	$C_JGALL['lang_location'] = 'Location';
	
	
	// Set text to indicate 'main folder' in top menu bar.
	$C_JGALL['lang_mainfolder'] = 'Albums';
	
	
	// Set text to indicate 'home button alt' in top menu bar.
	$C_JGALL['lang_home'] = 'You are in ' . $C_JGALL['lang_mainfolder'] . '/';
	
	
	// Set text to indicate 'go home button alt' in top menu bar.
	$C_JGALL['lang_gohome'] = 'Go back to ' . $C_JGALL['lang_mainfolder'] . '/';
	
	
	// Set text to indicate 'up button alt' in top menu bar.
	$C_JGALL['lang_up'] = 'You are in the top directory';
	
	
	// Set text to indicate 'go up button alt' in top menu bar.
	$C_JGALL['lang_goup'] = 'Go up one directory';
	
	
	// Set text to indicate 'refresh button alt' in top menu bar.
	$C_JGALL['lang_refresh'] = 'Refresh this page';
	
	
	// Set text to indicate 'image alt' when viewing image.
	// only when $C_JGALL['gall_link2source'] is set to 'y'
	$C_JGALL['lang_downloadimage'] = 'Click to download this image';
	
	
	// Set text to indicate 'first' in bottom border (page links).
	$C_JGALL['lang_first'] = '&lt;&lt; first';
	
	
	// Set text to indicate 'previous' in bottom border (page links).
	$C_JGALL['lang_prev'] = '&lt; previous';
	
	
	// Set text to indicate 'image info' in bottom border (page links).
	// Changes {CURRENT} for current image and {TOTAL} for total images
	$C_JGALL['lang_image_info'] = 'image <b>{CURRENT}</b> of <b>{TOTAL}</b>';
	
	
	// Set text to indicate 'next' in bottom border (page links).
	$C_JGALL['lang_next'] = 'next &gt;';
	
	
	// Set text to indicate 'last' in bottom border (page links).
	$C_JGALL['lang_last'] = 'last &gt;&gt;';
	
	
	// Set text to indicate 'empty dir message' when dir is empty.
	$C_JGALL['lang_emptydir'] = 'There are no items to be displayed!';
	
	
	// This line sets gallery info, when $C_JGALL['gall_show_info'] is set to 'y'.
	// Changes {FIRST} for first, and {LAST} for last shown item on page. 
	// {FOLDERS} for folders and {IMAGES} for images in dir. {DIR} for dir-name.
	$C_JGALL['lang_gall_detail'] = '<b>{FOLDERS}</b> sub-directory(s) and <b>{IMAGES}</b> image(s) in "<b>{DIR}</b>".';
	
	
	// This line sets image info, when $C_JGALL['gall_show_info'] is set to 'y'.
	// Changes {MIME} for mimetype, {WIDTH} and {HEIGHT} for image width and height,
	// {FILESIZE} for filesize in kilobytes end {NAME} for image-name.
	$C_JGALL['lang_image_detail'] = 'Filetype: {MIME} - Original size: {WIDTH} x {HEIGHT} px, {FILESIZE} kB';
	
	
// --> END Languge configuration section
// -----------------------------------------------------------------------------------


// --> END
?>