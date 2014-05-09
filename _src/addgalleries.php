<?php
db_connect();
@mysql_query( "USE holly_madison" );


// get all published posts
$query = "SELECT ID, post_title, post_name, post_content FROM wp_posts WHERE post_type='post' AND post_status='publish' AND post_parent='0' ORDER BY ID  DESC";

$query_result = @mysql_query($query); 

if (@mysql_num_rows($query_result) != 0){
	// for each post, find image attachments
	while ($row = @mysql_fetch_array($query_result)){
		/*echo "<pre>";
		   print_r($row);
		echo "</pre>";*/
		// [gallery ids="118546,118547,118548,118549,118550"]
		/*$gallery_code = '[gallery ids="';
		// get images from wp_posts table
		$image_results = @mysql_query("SELECT * FROM wp_posts WHERE post_title='".$row["post_title"]."' and post_type='attachment'");
		// if images found, create gallery shortcode
		if (@mysql_num_rows($image_results) != 0){
			$current_row = 0;
			while ($row2 = @mysql_fetch_array($image_results)){
				$gallery_code .= ($current_row == 0 ? '' : ',').$row2["ID"];
				if($current_row == @mysql_num_rows($image_results)-1){
					$gallery_code .= '"]';
				}
				$current_row++;
			}
			echo "POST ".$row["ID"].": ".$gallery_code."<br />";
			
		}*/
		
		
		
		
		// get gallery
		$gallery_post = @mysql_query("SELECT ID FROM wp_posts WHERE post_name='".$row["post_name"]."' AND post_type='gallery'");
		
		// if gallery found, get images
		if (@mysql_num_rows($gallery_post) != 0){
			
			while ($row3 = @mysql_fetch_array($gallery_post)){
				$gallery_images = @mysql_query("SELECT ID FROM wp_posts WHERE post_parent='".$row3["ID"]."' AND post_type='attachment'"); 
					
				if (@mysql_num_rows($gallery_images) != 0){
					$current_row2 = 0;
					$gallery_code2 = '[gallery ids="';
					while ($row4 = @mysql_fetch_array($gallery_images)){
						$gallery_code2 .= ($current_row2 == 0 ? '' : ',').$row4["ID"];
						if($current_row2 == @mysql_num_rows($gallery_images)-1){
							$gallery_code2 .= '"]';
						}
						$current_row2++;
					}
					//echo $row["post_title"]." ".$row["ID"].": ".$gallery_code2."<br />";
					
					// insert gallery shortcode into post
					$new_content = $gallery_code2." ".$row["post_content"];	
					$update_query = "UPDATE wp_posts SET post_content='".$new_content."' WHERE ID='".$row["ID"]."'";
					@mysql_query($update_query);
				}
			}
		}

	}
}


function db_connect(){
	$db_connection = @mysql_connect('localhost', 'holly_madison', 'holly_madison') or die (mysql_error());
	$db_select = @mysql_select_db('holly_madison') or die (mysql_error());
} 
?>