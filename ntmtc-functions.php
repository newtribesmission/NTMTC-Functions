<?php
/*
Plugin Name: NTM Tech Center Functions
Description: Adds various simple functions specifically for this site. Includes the [ntmtc-thisyear] shortcode, and custom image sizes. Also includes function to facilitate "Search FAQ" option.
Author: New Tribes Mission (Stephen Narwold)
Author URI: http://blogs.ntm.org/stephen-narwold
Version: 1.0

    Copyright (C) 2014  New Tribes Mission

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

/*
This file contains all the logic required for the plugin

INCLUDES:
	- [ntmtc-thisyear] Shortcode
		Displays current year (ie 2013)
		Primary use: Copyright footer
	- Adds custom Image Sizes
	- Adds 
	
 */

//BEGIN [ntmtc-thisyear] shortcode
function ntmtc_thisyear( $arg = NULL ) {
	return date("Y");
}
add_shortcode( 'ntmtc-thisyear', 'ntmtc_thisyear' );
//END [ntmtc-thisyear] shortcode


//BEGIN CUSTOM IMAGE SIZES
function ntmtc_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'landscape-thumbnail' => __('Landscape Thumbnail (Cropped)'),
		'homepage-large-ls' => __('Home Large (Landscape)'),
		'homepage-large-pt' => __('Home Large (Portrait)'),
		'one-third-full' => __('One Third Full'),
    ) );
}
add_filter( 'image_size_names_choose', 'ntmtc_custom_sizes' );
add_image_size( 'landscape-thumbnail', 160, 106, true ); //Adds Landscape Thumbnail image size (160px wide, 106 px tall)
add_image_size( 'homepage-large-ls', 560, 372, true );
add_image_size( 'homepage-large-pt', 278, 372, true );
add_image_size( 'one-third-full', 293, 180, true );
//END CUSTOM IMAGE SIZES


//Used to search FAQ's
function filter_search_by_ancestor($query) {
    if ($query->is_search) {
        if (isset($_GET['post_ancestor']) && ($_GET['post_ancestor'] != 0)) {
			
			$all_wp_pages = get_pages();
			
			$descendant_array = get_page_children( $_GET['post_ancestor'], $all_wp_pages );
			$descendants = array();
			foreach($descendant_array as $descendant) {
				$descendants[] = $descendant->ID;

			}
            $query->set( 'post__in', $descendants );
            $query->set('post_type', 'page');
        }
		
    }

    return $query;
}
add_filter('pre_get_posts','filter_search_by_ancestor');
?>
