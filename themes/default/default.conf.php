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
// Configuration section
// -----------------------------------------------------------------------------------

	/*
	Here you can set in wich order you want the 'location'-menu,
	the 'pagelink'-menu and the 'maintable' (images) to appear.
	Just put one above the other in a different order.
	*/
	$C_JGALL['layout_order'][] = 'location';
	$C_JGALL['layout_order'][] = 'pagelink';
	$C_JGALL['layout_order'][] = 'main';


	/*
	If you want to put some spacing between the menu's or maintable
	you can set spacing (px) below. 'first' appears between the two
	on top, 'second' between the two on bottom.
	If you apply spacing you might need to change border styles to.
	*/
	$C_JGALL['layout_spacing']['first'] = '10';
	$C_JGALL['layout_spacing']['second'] = '0';
	
	
	
// --> END configuration section
// -----------------------------------------------------------------------------------



// -> END
?>