<?php
/*
		jGallery 1.3
		------------------------------------------------------------------

		WARNING
		
		Changing some of these values may couse errors
		Read carefully what settings you are about to change
		
		
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/






// -----------------------------------------------------------------------------------
// General configuration section
// -----------------------------------------------------------------------------------


	// Set the theme for jGallery (must be in 'themes/'):
	$C_JGALL['gall_theme'] = 'default';


	// Set the dir which contains your photoalbums:
	$C_JGALL['gall_dir'] = 'albums/';


	// Set weither you want to link to original images (must be 'y' for yes):
	$C_JGALL['gall_link2source'] = 'y';


	// Set you like to sort images in alphabetic order (must be 'y' for yes):
	// If not 'y' the default is in order of last uploaded.
	$C_JGALL['sort_alphabetic'] = 'y';
	
	
// --> END General configuration section
// -----------------------------------------------------------------------------------







// -----------------------------------------------------------------------------------
// Layout configuration section
// -----------------------------------------------------------------------------------
	
	
	// Set the number of thumbnails on a row (must be numeric):
	$C_JGALL['gall_cols'] = '4';
	
	
	// Set the number of rows on a page (must be numeric):
	$C_JGALL['gall_rows'] = '3';
	
	
	// Set the max-size (width or height) for thumbnails (must be numeric):
	$C_JGALL['gall_thumb_size'] = '120';
	
	
	// Set the spacing between the thumbs (must be numeric, can be 0):
	$C_JGALL['gall_spacing'] = '10';
	
	
	// Set max size for vieuwing images (must be numeric or empty)
	// By default images are sized to fill the table
	$C_JGALL['gall_max_view_img_size'] = '0';
	
	
	// Set 'y' if you want to show filenames under thumbs:
	$C_JGALL['gall_show_filenames'] = 'y';
	
	
	// Set 'y' if you want to show info on bottom of gallery:
	$C_JGALL['gall_show_info'] = 'y';


// --> END Layout configuration section
// -----------------------------------------------------------------------------------


// --> END
?>