<?php
/**
 * Plugin Name: Personal area
 * Plugin URI: https://server.eshkolot.net
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Miri Angel
 * Author URI: ''
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// define('WP_USE_THEMES', false);
// require_once("../../../wp-load.php");


function personal_area(){

	$user_info = get_userdata(get_current_user_id());
	$user_name = $user_info->first_name;

	$html = '<script src="/wp-content/plugins/Personal area/personal_area.js?num='.rand().'"></script>
	<link rel="stylesheet" href="/wp-content/plugins/Personal area/personal_area.css?num='.rand().'"></link>
    <style>
		#details{
			padding: 5px;
			border: 1px solid black;
			border-radius: 30px;
			width: 80%;
			max-width: 1000px;
		}
		div#details > * {
			display: inline-block;
			padding: 3px 30px;
			border-left: 1px solid black;
		}
		#title_personal{
			font-weight: 600;
			font-size: 36px;
			display: inline-block;
		}
		#all_child{
			max-width: 1000px;
		}
		.title{
			font-weight: 400;
			font-size: 22px;
		}
		#download{
			background: #5956DA;
			border-radius: 30px;
			color: white;
			font-weight: 600;
			font-size: 18px;
			width: 190px;
			padding-left: 45px;
		}
		.expan_child td{
			text-align: start !important;
		}
		.text_status{
			font-weight: 400;
			font-size: 13px;
			background: #2D2828;
			padding: 5px;
			color: white;
		}
		.expan_child .first_td{
			font-weight: 400;
			font-size: 18px;
		}
		.expan_child .chird_td{
			text-align: center !important;
		}
		.expan_child .fourth_td{
			font-weight: 600;
			font-size: 13px;
		}
		#wrap_download{
			display: inline-block;
			position: absolute;
			right: 800px;
		}
		#icon_download{
			position: absolute;
			left: 20px;
			top: 15px;
		}
		#download_user{
			font-weight: 400;
			font-size: 12px;
			background: #D9D9D9;
			display: inline-block;
			color: black;
			border-radius: 0px;
			padding: 7px 10px;
		}
		.to_buy_or_edit{
			font-weight: 400  !important;
			font-size: 15px !important;
			color: #5956DA !important;
			text-align: center;
			cursor: pointer;
		}
		.loader{
			border: 4px solid #f3f3f3; /* Light grey */
			border-top: 4px solid #5956DA; /* Blue */
			border-radius: 50%;
			width: 50px;
			height: 50px;
			animation: spin 1s linear infinite;
			margin: 0 auto;
		}
		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>';
	
	$user_type = get_user_meta(get_current_user_id(), 'user_type')[0];
    $html_all_child_obj = get_html_all_child($user_type);

    $isCourses = $html_all_child_obj["isCourses"];
	$html .= '<div class="title-container">';
	$last_name_label = '';
		$last_name_label = 'משפחת ' . $user_info->last_name;
	if(!empty($user_info->last_name))
	$html .= ($user_type == 'private') ? '<p id="title_personal">אזור אישי: '.$last_name_label.' '.year_to_he().'</p>':'';
	$html .= ($isCourses? '<div id="wrap_download"><input  id="download" type="button" value="להורדת התוכנה">
			<div class="loader" style="display: none;"></div>
			
			</div>
			</div>':'').

			'<p class="title">חשבון ראשי</p>
			<div id="details">
			 	<p>שם פרטי: '.$user_name.'</p>
				<p>שם משפחה: '.$user_info->last_name.'</p>
				<p style="border-left: 0px solid black;">אימייל: '.$user_info->user_email.'</p>
			</div>
			<p class="title">חשבונות '.(($user_type == 'private') ?'ילדים':'תלמידים').'</p>
			<table id="all_child">
			<thead>
			<tr><td>מס.</td>
			<td class="nameForScreens">שם פרטי</td>
			<td>כיתה</td>
			<td>שם משתמש(אוטומטי)</td>
			<td class="passForScreens">סיסמא- תעודת זהות</td>
			<td></td>
			</tr>
			</thead>
			<tbody>';

    $html .= $html_all_child_obj["html"];

	$html .= '</tbody></table>
	<p class="to_buy_or_edit edit courses" style="margin-top: 60px;">לרכישת קורסים או מסלולים ←</p>
	<p class="to_buy_or_edit edit children">לעריכת / הוספת '.(($user_type == 'private') ?'ילדים':'תלמידים').' ←</p>
	<script>
	jQuery(".edit").on("click", function(e) {
		var index = '.($html_all_child_obj["html"] == '' ? 0:-1).'
		var url = "'.( ($user_type == 'private')?'/%D7%90%D7%A9%D7%9B%D7%95%D7%9C%D7%95%D7%AA-%D7%90%D7%95%D7%A4%D7%9C%D7%99%D7%99%D7%9F/':'/%D7%90%D7%A9%D7%9B%D7%95%D7%9C%D7%95%D7%AA-%D7%90%D7%95%D7%A4%D7%9C%D7%99%D7%99%D7%9F-%D7%9C%D7%9E%D7%95%D7%A1%D7%93%D7%95%D7%AA/').'"
		index = index == -1? jQuery(this).is(jQuery(".courses"))? 3:2:index
    	window.open(url+"?index="+index);
  	});
	</script>';
	
	echo $html;
}

// <svg style="position: relative;left: 48px;top: 6px;" xmlns="http://www.w3.org/2000/svg" width="25" height="21" viewBox="0 0 25 21" fill="none">
// 				<path d="M19.791 15.6006H5.20768C4.63239 15.6006 4.16602 15.9737 4.16602 16.4339C4.16602 16.8942 4.63239 17.2673 5.20768 17.2673H19.791C20.3663 17.2673 20.8327 16.8942 20.8327 16.4339C20.8327 15.9737 20.3663 15.6006 19.791 15.6006Z" fill="white"/>
// 				<path d="M4.16602 14.7666V16.4333C4.16602 16.8935 4.63239 17.2666 5.20768 17.2666C5.78298 17.2666 6.24935 16.8935 6.24935 16.4333V14.7666C6.24935 14.3064 5.78298 13.9333 5.20768 13.9333C4.63239 13.9333 4.16602 14.3064 4.16602 14.7666Z" fill="white"/>
// 				<path d="M18.75 14.7666V16.4333C18.75 16.8935 19.2164 17.2666 19.7917 17.2666C20.367 17.2666 20.8333 16.8935 20.8333 16.4333V14.7666C20.8333 14.3064 20.367 13.9333 19.7917 13.9333C19.2164 13.9333 18.75 14.3064 18.75 14.7666Z" fill="white"/>
// 				<path d="M-nan -nanL12.4989 13.1012C12.2829 13.1026 12.0718 13.0501 11.8947 12.9512L7.72804 10.6012C7.50347 10.4738 7.3511 10.2804 7.30426 10.0633C7.25741 9.84629 7.31989 9.62322 7.47804 9.44291C7.55698 9.35278 7.65746 9.27606 7.77367 9.21716C7.88988 9.15827 8.01952 9.11837 8.1551 9.09978C8.29069 9.08118 8.42954 9.08426 8.56363 9.10882C8.69773 9.13339 8.82441 9.17896 8.93637 9.24291C11.1392 10.4745 13.8332 10.4308 15.995 9.12865L16.0405 9.10124C16.2615 8.96863 16.5394 8.91169 16.8129 8.94295C17.0863 8.97421 17.3331 9.0911 17.4989 9.26791C17.6646 9.44472 17.7358 9.66697 17.6967 9.88576C17.6577 10.1046 17.5116 10.302 17.2905 10.4346L13.1239 12.9346C12.9436 13.0428 12.7243 13.1012 12.4989 13.1012L-nan -nanZ" fill="white"/>
// 				<path d="M12.4987 11.4339C12.2224 11.4339 11.9575 11.3461 11.7621 11.1898C11.5668 11.0336 11.457 10.8216 11.457 10.6006V3.93392C11.457 3.71291 11.5668 3.50094 11.7621 3.34466C11.9575 3.18838 12.2224 3.10059 12.4987 3.10059C12.775 3.10059 13.0399 3.18838 13.2353 3.34466C13.4306 3.50094 13.5404 3.71291 13.5404 3.93392V10.6006C13.5404 10.8216 13.4306 11.0336 13.2353 11.1898C13.0399 11.3461 12.775 11.4339 12.4987 11.4339Z" fill="white"/>
// 			</svg>

function get_html_all_child($user_type) {
    
    $html = '';

	$group_name = ($user_type == 'organization') ? get_group_name() : '';
	$all_child = ($user_type == 'private') ? get_children_for_parent(get_current_user_id()) : get_students_ids($group_name);

	$i = 0;
	$users_object = get_users_object($all_child);


	
    $isCourses = false;
	foreach($users_object as $child){
			
		$child_id = $child['id'];
		$child_object = get_userdata($child_id);
		$ID = $child['tz'];
		$html .= '<tr data-id="'.$child_id.'"><td>'.($i+1).'.</td><td>'.$child_object->first_name.'</td><td>'.$child_object->last_name.'</td><td>'.$child_object->user_email.'</td><td>'.$ID.'</td><td class="openD">+</td></tr>
		<tr class="expan_child" style="display: none;"><td colspan="6"><table><thead><tr><td>קורסים או מסלולים</td><td>סטטוס</td><td>תעודה</td><td>דמי פיקדון</td></tr></thead><tbody>';

		$courses_paths_product_ids = get_id_prod_for_all_courses_or_paths($child_id, 'user_id');

		foreach ($child['UserCourse'] as $course) {

            $isCourses = true;

			if(!$course['is_single_course'])
				continue;

			$course_id = $course['courseId'];

			$status_html = ($course["status"] == "הושלם")? ('<p class="text_status">'.$course["status"].'</p>'):(($course["status"] == "לא התחיל")?'<p class="text_status" style="background-color: #F4F4F3;color:black;">לא הותחל</p>':'<p class="text_status" style="background-color: #F4F4F3;color:black;">בלמידה '.get_course_progress_percent_for_user($course_id, $child_id).'%</p>');
			$course_icon_num = get_course_knowledge_num($courses_paths_product_ids[$course_id], $course_id);
			$is_diploma = learndash_get_course_certificate_link($course_id,$child_id);

			$course_icon_num = $course["icon_num"];
			$html .= get_html_tr_course_or_path($course_id, get_the_title($course_id), $course_icon_num, false, $is_diploma, $child_id, $status_html);
		}

		foreach ($child['pathIds'] as $path_id) {

			$path_object = get_learn_path_object(array($path_id))[$path_id];

			$html .= get_html_tr_course_or_path($path_id, get_the_title($path_id), '', true, '', $child_id, '');
			
			foreach ($path_object['courses'] as $course_id) {

				$course = get_userCourse($course_id, $child['UserCourse']);

				$status_html = ($course["status"] == "הושלם")? ('<p class="text_status">'.$course["status"].'</p>'):(($course["status"] == "לא התחיל")?'<p class="text_status" style="background-color: #F4F4F3;color:black;">לא הותחל</p>':'<p class="text_status" style="background-color: #F4F4F3;color:black;">בלמידה '.get_course_progress_percent_for_user($course_id, $child_id).'%</p>');
				$course_icon_num = get_course_knowledge_num($courses_paths_product_ids[$course_id], $course_id);
				$is_diploma = learndash_get_course_certificate_link($course_id,$child_id);

				$html .= '<tr class="course_path_'.$path_id.'" style="display:none;"><td class="first_td" style="position: relative;">'.get_the_title($course_id).'<div style="position: absolute;left: 0px;top: 8px;">'.$course_icon_num.'</div></td>
				<td class="second_td">'.$status_html.'</td>
				<td class="chird_td">'.(($is_diploma != "") ? ('<a target="_blank" href="'.$is_diploma.'" style="font-weight: 400;font-size: 13px;">להורדת התעודה</a>'.get_svg_downlod_icon().''):'').'</td>
				<td class="fourth_td"></td>
				</tr>';

			}
		}

		$html .= '</tbody></table>'
        .
		($isCourses ? '<div style="text-align: end;">
			<p style="font-weight: 400;font-size: 12px;color: #6E7072;display:inline-block;">להורדת הקורסים של '.$child_object->first_name.' לדיסקונקי נפרד:</p>
			<button id="download_user">להורדת התוכנה עם הקורסים של '.$child_object->first_name.' '.get_svg_downlod_icon().'</button>
		</div>
		</td></tr>':'');
		$all_course = array();
		$i++;
	}

    return array("html"=>$html,"isCourses"=>$isCourses);
}

function get_html_tr_course_or_path($course_id, $title, $course_icon_num, $is_path, $is_diploma, $child_id, $status_html){

	$courses_paths_product_ids = get_id_prod_for_all_courses_or_paths($child_id, 'user_id');

	$prod_id = $courses_paths_product_ids[$course_id];
	$price = get_post_meta($prod_id, '_price', true);
	$is_refund_made = get_is_refund_made($prod_id, $products);

	return '<tr class="course_or_path_'.$course_id.'"><td class="first_td" style="position: relative;">'.$title.'<div style="position: absolute;left: 0px;top: 8px;">'.$course_icon_num.'</div>'.($is_path?arrow():'').'</td>
			<td class="second_td">'.($is_path?'':$status_html).'</td>
			<td class="chird_td">'.(($is_diploma != "") ? ('<a target="_blank" href="'.$is_diploma.'" style="font-weight: 400;font-size: 13px;">להורדת התעודה</a>'.get_svg_downlod_icon().''):'').'</td>
			<td class="fourth_td">'.$price.' '.(($is_refund_made == 1) ? 'בוצע החזר מלא':'').'</td>
			</tr>';
}

function get_userCourse($course_id, $UserCourse){

	foreach ($UserCourse as $subArray) {
		if ($subArray['courseId'] == $course_id) {
			return $subArray;
		}
	}

	return null;
}

function get_group_name(){
	// Get the current page's URL
	$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	// Get the path part of the URL
	$path = parse_url($currentURL, PHP_URL_PATH);

	// Get the end of the URL (slug)
	$endOfURL = basename($path);

	// Output the end of the URL
	echo $endOfURL;
}

function get_is_refund_made($id, $products) {
	foreach ($products as $product) {
		if($product['id'] == $id)
			return $product['is_refund_made'];
	}
	return NULL;
}

add_shortcode('personal_area', 'personal_area');

function get_children_for_parent($id_parent){
	$all_child = array();
	$children = get_user_meta($id_parent, 'children')[0];
	foreach ($children as $child) {
		array_push($all_child, $child['child']);
	}
	return $all_child;
}

function get_students_ids($group_name){
	$user_id = get_current_user_id();
	$data = get_user_meta($user_id, 'groups', true);
	$groups = unserialize($data[0]);
	return $groups[$group_name]['students_ids'];
}

function year_to_he(){
	$year = jdtojewish(gregoriantojd( date('m'), date('d'), date('Y')), true);
	$year = iconv('WINDOWS-1255', 'UTF-8', $year);
	$year = explode(" ", $year)[2];
	$year = iconv_substr($year, 1, 5, 'utf-8');
	//$year = substr_replace($year, '\"', 1, 0);
	return $year;
}

function get_svg_downlod_icon(){
	return '<svg style="position: relative;top: 4px;right: 2px;" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
	<path d="M11.875 11.25H3.125C2.77982 11.25 2.5 11.5298 2.5 11.875C2.5 12.2202 2.77982 12.5 3.125 12.5H11.875C12.2202 12.5 12.5 12.2202 12.5 11.875C12.5 11.5298 12.2202 11.25 11.875 11.25Z" fill="#2D2828"/>
	<path d="M2.5 10.625V11.875C2.5 12.2202 2.77982 12.5 3.125 12.5C3.47018 12.5 3.75 12.2202 3.75 11.875V10.625C3.75 10.2798 3.47018 10 3.125 10C2.77982 10 2.5 10.2798 2.5 10.625Z" fill="#2D2828"/>
	<path d="M11.25 10.625V11.875C11.25 12.2202 11.5298 12.5 11.875 12.5C12.2202 12.5 12.5 12.2202 12.5 11.875V10.625C12.5 10.2798 12.2202 10 11.875 10C11.5298 10 11.25 10.2798 11.25 10.625Z" fill="#2D2828"/>
	<path d="M7.49893 9.375C7.36936 9.37599 7.24268 9.33667 7.13643 9.2625L4.63643 7.5C4.50169 7.40442 4.41027 7.25937 4.38216 7.09658C4.35405 6.93379 4.39154 6.76649 4.48643 6.63125C4.5338 6.56366 4.59408 6.50611 4.66381 6.46194C4.73354 6.41777 4.81132 6.38785 4.89267 6.37391C4.97402 6.35996 5.05733 6.36227 5.13779 6.38069C5.21825 6.39911 5.29426 6.43329 5.36143 6.48125L7.49893 7.975L9.62393 6.375C9.75654 6.27555 9.92323 6.23284 10.0873 6.25628C10.2514 6.27973 10.3995 6.36739 10.4989 6.5C10.5984 6.63261 10.6411 6.7993 10.6176 6.96339C10.5942 7.12749 10.5065 7.27555 10.3739 7.375L7.87393 9.25C7.76575 9.33114 7.63416 9.375 7.49893 9.375Z" fill="#2D2828"/>
	<path d="M7.5 8.125C7.33424 8.125 7.17527 8.05915 7.05806 7.94194C6.94085 7.82473 6.875 7.66576 6.875 7.5V2.5C6.875 2.33424 6.94085 2.17527 7.05806 2.05806C7.17527 1.94085 7.33424 1.875 7.5 1.875C7.66576 1.875 7.82473 1.94085 7.94194 2.05806C8.05915 2.17527 8.125 2.33424 8.125 2.5V7.5C8.125 7.66576 8.05915 7.82473 7.94194 7.94194C7.82473 8.05915 7.66576 8.125 7.5 8.125Z" fill="#2D2828"/>
	</svg>';
}

function arrow(){
	return '<div class="display_courses_path" style="
			width: 0;
			height: 0;
			border-left: 10px solid transparent;
			border-right: 10px solid transparent;
			border-top: 10px solid black;
			display: inline-block;
			position: absolute;
			left: 19px;
			top: 17px;
		"></div>';
}

add_action( 'wp_ajax_download_software', 'download_software' );
add_action( 'wp_ajax_nopriv_download_software', 'download_software' );

function get_course_data_user($id_user, $id_course, $user_courses_data, $is_single_course){

	if(empty($user_courses_data))
		$user_courses_data = array();

	$status = learndash_course_status($id_course,$id_user);
	$is_diploma = learndash_get_course_certificate_link($id_course,$id_user);
	$progress_percent = get_course_progress_percent_for_user($id_course, $id_user);
	array_push($user_courses_data, array('courseId'=> $id_course, 'status'=> $status, "progress_percent" => $progress_percent, 'diplomaPath'=>$is_diploma, "is_single_course"=>$is_single_course));

	return $user_courses_data;
}

function update_is_completed($id_user, $array_completed, $id_completed){
	$progress_lesson = learndash_get_course_progress( $id_user, $id_completed);
	if ( !empty( $progress_lesson['this']->completed ) && !in_array($id_completed, $array_completed)) {
		array_push($array_completed, $id_completed);
	}
	return $array_completed;
}

function update_is_completed_quiz($array_completed, $quiz) {
	if ( $quiz['status'] == "completed") {
		array_push($array_completed, $quiz['post']->ID);
	}
	return $array_completed;
}

function collapse_to_zip($folderPath, $zipFilePath){
    unlink($zipFilePath);
    // Create a new ZipArchive object
    $zip = new ZipArchive();

    // Open the zip file for writing
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

        // Add files and subdirectories recursively to the zip file
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = basename($filePath); // Use basename to get only the filename
                $zip->addFile($filePath, $relativePath);
            }
		}
		// Close the zip file
        $zip->close();
        // echo 'Folder compressed successfully to: ' . $zipFilePath;
    } else {
        echo 'Failed to create the zip file.';
    }
}

function downloadZip($saveFolderPath, $zipName){
	// $saveFolderPath = '../wp-content/plugins/Personal area/download softwer/lessons/';
	$zipFileName = plugin_dir_path( __FILE__ ).'courses_videos/'.$zipName; // Replace with your actual ZIP file URL
	if (file_exists($zipFileName)) {
		$saveFilePath = $saveFolderPath . $zipName; 
		$result = copy($zipFileName, $saveFilePath);
	}
}

function replace_problematic_characters($str) {

 // Match the part of the URL that includes 'gif.latex?' and then the file name part
 return preg_replace_callback('/(gif\.latex\?)([^"\s]+)/', function($matches) {
	// Replace problematic characters in the file name with underscore
	$file_name = preg_replace('/[<>:"\/|?*{}]/', '_', $matches[2]);
	
	// Add .png extension at the end of the file name
	return $matches[1] . $file_name . '.png';
}, $str);

}



// function replace_problematic_characters($str) {

// 	echo $str;
//     // Replace problematic characters with underscore
//     $str = preg_replace('/[<>:"\/|?*]/', '_', $str);

//     // Use regex to match and replace file names (any sequence without spaces and special characters)
//     // It finds sequences that don't contain spaces, quotes, or other delimiters
//     $str = preg_replace_callback('/(\w+)(?=[\'"\s])/', function($matches) {
//         return $matches[1] . '.png'; // Add the '.png' extension to each match
//     }, $str);

// 	echo 'llllllllll';

// 	echo $str;

// 	return $str;
// }


function download_file($fileUrl, $pathFolder) {
	// Ensure $pathFolder has a trailing slash
    if (substr($pathFolder, -1) !== '/') {
        $pathFolder .= '/';
    }
    $filename = basename($fileUrl);
	$filename = replace_problematic_characters($filename);
	
    $savePath = $pathFolder . $filename;
	if (!file_exists($savePath)){

		// Initialize cURL session
		$ch = curl_init($fileUrl);
		// Set options for cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// Execute cURL session and get the file content
		$fileContents = curl_exec($ch);
		// Close cURL session
		curl_close($ch);
		// Save the file to the specified path
		$result = file_put_contents($savePath, $fileContents);
		// Check if the file was saved successfully
		if ($result !== false) {
			// echo 'File downloaded and saved successfully.';
		} else {
			echo 'Failed to download and save the file.';
		}
	}
	else{
		error_log('the file is exist: '.$savePath );
	}
    return $filename;
}

function extractNumbers($inputString) {
    $pattern = '/d:(\d+)/';
    preg_match_all($pattern, $inputString, $matches);
    $result = [];
    foreach ($matches[1] as $match) {
        if (!empty($match)) {
            $result[] = (int)$match;
        }
    }
    return $result;
}

function getGreaded($pro_id){
	global $wpdb;
	$table_name = $wpdb->prefix . 'learndash_pro_quiz_master';
	$result_text_column = 'result_text';
	// Prepare and execute the SQL query
	$query = $wpdb->prepare("SELECT $result_text_column FROM $table_name WHERE id = %d", $pro_id);
	$result_text = $wpdb->get_var($query);
	// Output the result_text
	return extractNumbers($result_text);
}

function img_online_to_offline($str, $folder_path){
	download_img_to_folder(get_array_urls($str, 'src="'), $folder_path);
	download_img_to_folder(get_array_urls($str, 'mp3="'), $folder_path);
	download_img_to_folder(get_array_urls_from_gallery($str), $folder_path);
}

function get_array_urls($string, $pre_text){
	$parts = explode($pre_text, $string);
	$imageUrls = array();
	for ($i = 1; $i < count($parts); $i++) {
		// Extract the image URL between the current part and the next occurrence of double quotes
		if(!str_contains($parts[$i],'https://player.vimeo.com/video')){
			$imageUrl = substr($parts[$i], 0, strpos($parts[$i], '"'));
			$imageUrls[] = $imageUrl;
		}		
	}
	return $imageUrls;
}

function get_array_urls_from_gallery($string){
	$imageUrls = array();
	if(str_contains($string, 'gallery ids="')){
		$parts = explode('gallery ids="', $string);
		for ($i = 1; $i < count($parts); $i++) {
			// Extract the image URL between the current part and the next occurrence of double quotes
			$imageUrl = substr($parts[$i], 0, strpos($parts[$i], '"'));
			$imgeUrlArr = explode(',', $imageUrl);
			for ($j = 0; $j < count($imgeUrlArr); $j++) {
				// $imgeUrlArr[$j] = 'https://dev2.eshkolot.net/?attachment_id='.$imgeUrlArr[$j];
				$image_url = get_attached_file($imgeUrlArr[$j]);
				$image_url = explode('/wp-content/', $image_url)[1];
				$image_url = "https://eshkolot.net/wp-content/" . $image_url;
				$imgeUrlArr[$j] = $image_url;
			}
			$imageUrls = array_merge($imageUrls, $imgeUrlArr);
		}
	}
	return $imageUrls;
}

function download_img_to_folder($arr_urls, $folder_path){
	foreach($arr_urls as $url){
		download_file($url, $folder_path);
	}
}

//get string, return files names only
function edit_question_string($string){
	$pattern = '/(https?:\/\/[^\s]+)/i';
    // Find all URLs in the string
    preg_match_all($pattern, $string, $matches);
    // Iterate through the URLs
    foreach ($matches[0] as $url) {
        // Get the file name from the URL

		$url_components = parse_url($url);
        $path = $url_components['path'];
        $filename = mb_basename($path);
        // $filename = basename($url);

        // Replace the URL in the string with just the file name
        $string = str_replace($url, $filename, $string);
    }
	if (str_contains($string, 'gallery ids')){
		$parts = explode("gallery ids=", $string);
		$s2 = preg_split("/\]/", $parts[1]);
		$ids = explode(',', $s2[0]);
		$replacement = '';
		for ($i = 0; $i < count($ids); $i++) {
	        $id = str_replace('"', '', $ids[$i]);
			$image_url = get_attached_file($id);
			$image_url = end(explode('/', $image_url));
			$replacement .= '<img src="' . $image_url . '" />';
		}
        $string = str_replace('[gallery ids='.$s2[0].']', $replacement, $string);
	}

    $string =  replace_problematic_characters($string);

	return $string;
}


function mb_basename($path) {
    $path = rtrim($path, '/\\');
    $last_slash = max(mb_strrpos($path, '/'), mb_strrpos($path, '\\'));
    if ($last_slash !== false) {
        return mb_substr($path, $last_slash + 1);
    }
    return $path;
}

function replace_urls_with_file_names($input_string) {
    // Define a regular expression pattern to match URLs and capture the file name
    $pattern = '/https?:\/\/[^\'"]+\/([^\/\'"]+\.[^\/\'"]+)/i';
    // Use preg_replace_callback to replace each matched URL with its file name
    $output_string = preg_replace_callback($pattern, function($matches) {
        // The captured file name is in the second match group
        return $matches[1];
    }, $input_string);

	$output_string =  replace_problematic_characters($output_string);

    return $output_string;
}

function get_course_data_api($data){

	$id_course = $data['id_course'];
	$all_ids_by_course = get_all_ids_by_course($id_course);
	$questionnaire =  get_questionnaire_object($all_ids_by_course['questionnaire_all_ids']);
	$knowledges =  get_knowledges_object($all_ids_by_course['knowledges']);
	$subjects =  get_subjects_object($all_ids_by_course['subject_ids']);
	$lessons =  get_lessons_object($all_ids_by_course['lessons_ids']);

	return array(
			'questionnaire' => $questionnaire,
			'courses' => get_courses_object(array($id_course)),
			'knowledge' => $knowledges,
			'subjects' => $subjects,
			'lessons' => $lessons
		);
}
add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/get_course_data/(?P<id_course>\d+)', array(
      'methods' => 'GET',
      'callback' => 'get_course_data_api',
    ) );
} );

function sent_email_password( $data ) {
	$user = get_userdata($data['id_user']);
	return wp_mail($user->user_email,' תוכנת אשכולות - שחזור סיסמה ',' שלום '.$user->display_name.'
    לבקשתך-
     הסיסמא שלך לתוכנת אשכולות היא '.$user->user_pass.'
    אנחנו מאחלים לך המשך לימוד פורה ומהנה 
    צוות אשכולות');
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/sent_email/(?P<id_user>\d+)', array(
      'methods' => 'GET',
      'callback' => 'sent_email_password',
    ) );
} );

$questionCompleted = array();

function update_user_data( $request ) {
	$idUser = $request->get_param( 'id_user' );
	update_user_completed($idUser, $request->get_param( 'coursesCompleted' ), $request->get_param( 'subjectCompleted' ), $request->get_param( 'questionCompleted' ), $request->get_param( 'lessonCompleted' ), $request->get_param('percentages'));
	return get_users_object(array($idUser))[0];
}

function update_user_completed($idUser, $coursesCompleted, $subjectCompleted, $questionCompleted, $lessonCompleted, $percentages){
	foreach($coursesCompleted as $c){
	  if(!learndash_course_completed($idUser, $c)){
		update_user_meta(  $idUser, ('course_completed_' . $c), time());
		learndash_process_mark_complete($idUser, $c);
		perform_refund($idUser, $c);
	  }
	}
	foreach($subjectCompleted as $s){
	  learndash_process_mark_complete($idUser, $s);
	}
	foreach($questionCompleted as $q){
	  learndash_process_mark_complete($idUser, $q);
	}

	foreach($lessonCompleted as $l){
		learndash_process_mark_complete($idUser, $l);
	}

	$quiz_meta = get_user_meta($idUser, '_sfwd-quizzes',true);
	foreach($percentages as $percentage){
		mark_quiz_complete_for_user( $idUser, $percentage['quiz_id'], $percentage['percentage'] );
		// foreach($quiz_meta as $key => $value){
			// foreach($user_quiz_array as $quiz){
	// 			if($value['quiz'] == $percentage['quiz_id']){
	// 				$quiz_meta[$key]['percentage'] = $percentage['percentage'];
	// 			}
	// 		// }
	// 	}
	}
	// update_user_meta($idUser, '_sfwd-quizzes', $quiz_meta);
}

function mark_quiz_complete_for_user( $user_id, $quiz_id, $score ) {
    if ( get_userdata( $user_id ) && get_post( $quiz_id ) ) {    
		learndash_update_user_activity(
            array(
                'course_id'          => 0,
                'post_id'            => $quiz_id,
                'user_id'            => $user_id,
                'activity_type'      => 'quiz',
                'activity_status'    => 1,
                'activity_started'   => time(),
                'activity_completed' => time(),
                'activity_meta'      => array(
                    'score'  => $score //percentage??
                )
            )
        );
    }
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/update_user_data', array(
      'methods' => 'POST',
      'callback' => 'update_user_data',
      'permission_callback' => '__return_true',
    ) );
} );

function set_questionCompleted($id_post,$id_user){
  	global $questionCompleted;
  	$quizs = learndash_get_lesson_quiz_list($id_post);
	if(!empty($quizs)){
		foreach($quizs as $quiz){
			$quiz_id = $quiz["post"]->ID;
          	$progress_lesson = learndash_get_course_progress( $id_user,$quiz_id);
			if ( !empty( $progress_lesson['this']->completed ) ) {
				array_push($questionCompleted,$quiz_id);
			}
        }
    }
}

// function create_installation_zip($tozip, $zipfile) {
// 	$zip = new ZipArchive();
// 	if ($zip->open($zipfile, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true) {
// 		function zip_all($folder, $base, $ziparchive) {
// 		$options = ["remove_all_path" => true];
// 		if ($folder != $base) { 
// 		  $options["add_path"] = substr($folder, strlen($base));
// 		}
// 		$ziparchive->addGlob($folder."*.*", GLOB_BRACE, $options);
// 		$folders = glob($folder . "*", GLOB_ONLYDIR);
// 		if (count($folders)!=0){ 
// 			foreach ($folders as $f) {
// 		  		zip_all($f."/", $base, $ziparchive);
// 			}
// 		}
// 	  }
// 	  zip_all($tozip, $tozip, $zip);
// 	  $zip->close();
// 	}
// }


function zip_all($folder, $base, $ziparchive) {
    $options = ["remove_all_path" => true];
    if ($folder != $base) { 
        $options["add_path"] = substr($folder, strlen($base));
    }
    $ziparchive->addGlob($folder."*.*", GLOB_BRACE, $options);
    $folders = glob($folder . "*", GLOB_ONLYDIR);
    if (count($folders) != 0) { 
        foreach ($folders as $f) {
            zip_all($f."/", $base, $ziparchive);
        }
    }
}

function create_installation_zip($tozip, $zipfile) {
	try {
    	$zip = new ZipArchive();
		if ($zip->open($zipfile, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true) {
			zip_all($tozip, $tozip, $zip);
			if ($zip->close()) {
				error_log(date('d-m-Y H:i:s ', time()). " zip file closed successfully");
			} else {
				error_log(date('d-m-Y H:i:s ', time()). " failed to close zip file");
			}
		}
		else {
			error_log(date('d-m-Y H:i:s ', time()). " failed to open zip file: $zipfile");
		}
		error_log(date('d-m-Y H:i:s ', time()). " finish create_installation_zip");
	}
	catch (Exception $e) {
		error_log(date('d-m-Y H:i:s ', time()). " error create_installation_zip ". $e->getMessage());
	}
}

function get_or_create_folder($folder_path){
	if (!file_exists($folder_path)) {
		mkdir($folder_path, 0777, true);
	}
	return $folder_path;
}

function copy_directory($source, $destination){
	if (!is_dir($destination)) {
		mkdir($destination, 0755, true);
	}
	$files = scandir($source);
	foreach ($files as $file) {
		if ($file !== '.' && $file !== '..') {
			$sourceFile = $source . '/' . $file;
			$destinationFile = $destination . '/' . $file;
			// if (is_dir($sourceFile)) {
			// 	copy_directory($sourceFile, $destinationFile);
			// } 
			// else {
				copy($sourceFile, $destinationFile);
			// }
		}
	}
}

function create_quizs_folder($quizzes_path, $quizzes_path_destination, $questionnaire_ids){
	foreach ($questionnaire_ids as $id) {
		$quiz_file = $id . '.zip';
		copy($quizzes_path . $quiz_file, $quizzes_path_destination . $quiz_file);
	}
}

function delete_folder($dir){
    foreach(glob($dir . '*') as $file) {
        if(is_dir($file))
			delete_folder($file . '/');
        else
            unlink($file);
    }
    $result = rmdir($dir);
}

function get_vimeo_id($course_id){
	$content_post = get_post($course_id);
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = explode('vimeo.com/', $content)[1];
	$content = explode('"', $content)[0];
	return $content;
}

function flat_array($input_array){
	$flattened_array = [];
	array_walk_recursive($input_array, function($a) use (&$flattened_array) {
		$flattened_array[] = $a;
	});
	return $flattened_array;
}

//get string' return urls
function get_urls_from_str($str, $isAA = false){

	$str =  replace_problematic_characters($str);

	preg_match_all('/https?:\/\/[^\'"]+/i', $str, $question_urls);

	$question_urls = array_merge($question_urls, get_array_urls_from_gallery($str));
	$question_urls = array_filter($question_urls, fn($value) => !empty($value));

	return $question_urls;
}


add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/get_courses_ids_for_learn_path/(?P<id_path>\d+)', array(
      'methods' => 'GET',
      'callback' => 'get_courses_ids_for_learn_path_api',
    ) );
} );

function get_courses_ids_for_learn_path_api($data) {

	$id_path = $data['id_path'];
	// $courses_ids =  learndash_get_groups_courses_ids($id_path);
	$courses_ids = learndash_get_group_courses_list( $id_path );
	$group = get_post($id_path);
	$knowledge_color = get_term_meta($id_path, 'color-knowledge', true);
	$knowledge_color = $knowledge_color == false ? '': $knowledge_color;
	
	return json_encode(array(
		"courses" => $courses_ids,
		"title" => $group->post_title,
		"color"=> $knowledge_color,
	));

}

function download_software($users_ids){
	$users_download = get_users_object($users_ids);
	$object_ids_for_all_users = get_all_ids_by_users($users_ids);
	$knowledge_object = get_knowledges_object($object_ids_for_all_users['knowledges']);
	$learn_path_object = get_learn_path_object($object_ids_for_all_users['path_ids']);
    $courses_object = get_courses_object($object_ids_for_all_users['courses_ids']);
    $subjects_object = get_subjects_object($object_ids_for_all_users['subject_ids']);
    $lessons_object = get_lessons_object($object_ids_for_all_users['lessons_ids']);
    $questionnaire_object = get_questionnaire_object($object_ids_for_all_users['questionnaire_all_ids']);
	$learn_path_object = empty($learn_path_object) ? (object) array() : $learn_path_object;
	$main_json = array(
		"users" => $users_download,
		"knowledge" => $knowledge_object,
		"learnPath" => $learn_path_object,
        "courses" => $courses_object,
      	"subjects" => $subjects_object,
      	"lessons" => $lessons_object,
      	"questionnaire" => $questionnaire_object,
	);
	// sanitize_filenames($main_json);
	$user_mail = get_userdata(get_current_user_id())->user_email;
    $subject = 'התקנת אשכולות אופליין';
    $data_link = create_folders_for_download_software($object_ids_for_all_users['courses_ids'], $object_ids_for_all_users['questionnaire_all_ids'], $main_json);
    $install_link = 'https://eshkolot.net/wp-content/plugins/Personal%20area/install.bat';

    $message = '<h1 style="font-weight: bold;">תוכנת אשכולות אופליין</h1>' .
           'כדי להתקין את התוכנה, לחצי על 2 הקישורים הבאים ככה שירדו אליך למחשב 2 קבצים.<br>' .
           'לחצי לחיצה כפולה על הקובץ installation.bat<br>' .
           'בהצלחה!<br><br>' .
           $data_link . '<br><br>' . 
           $install_link. '<br>';

    $message = '<div style="direction: rtl;">' . $message . '</div>';
	//delete:
	$user_mail = 'gittygimi2@gmail.com'; // delete!!!
    $result = wp_mail($user_mail, $subject, $message, array('Content-Type: text/html; charset=UTF-8'));
	$user_mail = 'rivki.cholak@gmail.com'; // delete!!!
    $result = wp_mail($user_mail, $subject, $message, array('Content-Type: text/html; charset=UTF-8'));
	return $result;
  	// die();
}

function sanitize_filenames(&$data) {
    foreach ($data as $key => &$value) {
        if (is_array($value)) {
            sanitize_filenames($value); // רקורסיה לעבוד על תתי האובייקטים
        } else{
            // החלפת התווים הבעייתיים בשם הקובץ
            $value = preg_replace('/[<>:"\/|?*]/', '_', $value);

            // שינוי סיומת הקובץ מ- latex ל- png אם נמצא
            $value = preg_replace('/\.latex$/', '.png', $value);
        }
    }
}

function get_users_object($users_ids) {

	$users_download = array();

	foreach($users_ids as $id_user){

		$knowledgeIds_user = array();
		$path_ids_user = array();
		$courses_ids = array();
		$user_courses_data = array();

		$subjectCompleted = array();
		$questionCompleted = array();
		$lessonCompleted = array();

		$user = get_userdata($id_user);

		$products = get_user_meta($id_user, 'product_offline')[0];		

		foreach ($products as $product) {

			$id_prod = $product['id'];
			$details_prod = get_courses_and_paths_product($id_prod);

			$courses_ids = array_merge_unique($courses_ids, $details_prod["courses_ids"]);
			$path_ids_user = array_merge_unique($path_ids, $details_prod["path_ids"]);
			$path_ids = array_merge_unique($path_ids, $details_prod["path_ids"]);
			$single_courses_ids = array_merge_unique($single_courses_ids, $details_prod["single_courses_ids"]);
		}
		foreach($courses_ids as $course_id){

			$knowledge_id = get_knowledge_by_course($course_id)->term_id;
			$knowledgeIds_user = insertIfNotExists($knowledgeIds_user, $knowledge_id);

			$is_single_course = in_array($course_id,  $single_courses_ids);
			$user_courses_data = get_course_data_user($id_user, $course_id, $user_courses_data, $is_single_course);

			// $course_quizzes = learndash_get_global_quiz_list($course_id);
			$course_quizzes = learndash_get_lesson_quiz_list($course_id, $id_user);
			
			foreach($course_quizzes as $quiz) {
				$questionCompleted = update_is_completed_quiz($questionCompleted, $quiz);
			}
			$course_subjects = learndash_get_lesson_list($course_id);
			foreach($course_subjects as $course_subject){
				$subjectCompleted = update_is_completed($id_user, $subjectCompleted, $course_subject->ID);
				$subject_quizzes = learndash_get_lesson_quiz_list($course_subject->ID, $id_user, $course_id);
				foreach($subject_quizzes as $quiz){
					$questionCompleted = update_is_completed_quiz($questionCompleted, $quiz);
				}
				$lesson_topics = learndash_get_topic_list($course_subject->ID, $course_id);
				foreach($lesson_topics as $lesson_topic){
					$lessonCompleted = update_is_completed($id_user, $lessonCompleted, $lesson_topic->ID);
					$topic_quizzes = learndash_get_lesson_quiz_list($lesson_topic->ID, $id_user, $course_id);
					foreach($topic_quizzes as $quiz){
						$questionCompleted = update_is_completed_quiz($questionCompleted, $quiz);
					}
				}
			}


		}

		$path_ids_user = $path_ids_user != NULL ? $path_ids_user : array();

		array_push($users_download, array(
			"id"=> (int) $id_user,
			"name"=> $user->first_name,
			"tz"=>get_ID_user($id_user),
			"knowledgeIds" => $knowledgeIds_user,
			"pathIds" => $path_ids_user,
			"UserCourse"=> $user_courses_data,
          	"subjectCompleted"=> $subjectCompleted,
          	"questionCompleted"=>$questionCompleted,
          	"lessonCompleted"=>$lessonCompleted,
		));
	}

	return $users_download;
	
}

function get_all_ids_by_users($users_ids) {

	$courses_ids = array();

	foreach($users_ids as $id_user){

		$products = get_user_meta($id_user, 'product_offline')[0];

		foreach ($products as $product) {

			$id_prod = $product['id'];

			// create_quizs_folder($quizzes_path, $quiz_path, $id_prod);	

			$details_prod = get_courses_and_paths_product($id_prod);

			$courses_ids = array_merge_unique($courses_ids, $details_prod["courses_ids"]);
		
			$path_ids = array_merge_unique($path_ids, $details_prod["path_ids"]);

		}
		
	
	}

	foreach($courses_ids as $course_id){

		$all_ids_by_course = get_all_ids_by_course($course_id);
		$knowledges =  object_merge_unique($knowledges, $all_ids_by_course['knowledges']);
		$subject_ids = array_merge_unique($subject_ids, $all_ids_by_course['subject_ids']);
		$lessons_ids = object_merge_unique($lessons_ids, $all_ids_by_course['lessons_ids']);
		$questionnaire_all_ids = array_merge_unique($questionnaire_all_ids, $all_ids_by_course['questionnaire_all_ids']);


	}

	$ids = array();
	$ids["knowledges"] = $knowledges;
	$ids["path_ids"] = $path_ids;
	$ids["courses_ids"] = $courses_ids;
	$ids["subject_ids"] = $subject_ids;
	$ids['lessons_ids'] = $lessons_ids;
	$ids['questionnaire_all_ids'] = $questionnaire_all_ids;


	return $ids;
}

function get_all_ids_by_course($course_id){

	$knowledge = get_knowledge_by_course($course_id);
	$knowledges[$knowledge->term_id] = $knowledge->name;

	$lesson_topics_ids = array();

	$course_quizzes = learndash_get_global_quiz_list($course_id);
	foreach($course_quizzes as $quizze_id){
		$questionnaire_all_ids = insertIfNotExists($questionnaire_all_ids, $quizze_id->ID);
		$questionnaire_course_ids = insertIfNotExists($questionnaire_course_ids, $quizze_id->ID);
	}

	$subject_ids = array_merge_unique($subject_ids, learndash_course_get_steps_by_type($course_id, 'sfwd-lessons'));
	$course_subjects = learndash_get_lesson_list($course_id);
	foreach($course_subjects as $course_subject){

		$subject_quizzes = learndash_get_lesson_quiz_list($course_subject->ID, null, $course_id);
		foreach($subject_quizzes as $quiz)
			$questionnaire_all_ids  = insertIfNotExists($questionnaire_all_ids, $quiz["post"]->ID);

		$lesson_topics = learndash_get_topic_list($course_subject->ID, $course_id);
		foreach($lesson_topics as $lesson_topic){

			$lesson_topics_ids[$lesson_topic->ID] = $course_subject->ID;

			$topic_quizzes = learndash_get_lesson_quiz_list($lesson_topic->ID, null, $course_id);
			foreach($topic_quizzes as $quiz)
				$questionnaire_all_ids  = insertIfNotExists($questionnaire_all_ids, $quiz["post"]->ID);

		}
	}

	$ids = array();
	$ids["knowledges"] = $knowledges;
	$ids["subject_ids"] = $subject_ids;
	$ids['lessons_ids'] = $lesson_topics_ids;
	$ids['questionnaire_course_ids'] = $questionnaire_course_ids;
	$ids['questionnaire_all_ids'] = $questionnaire_all_ids;

	return $ids;

}

function get_users_ids() {

	$all_users = array();

	$software_path = get_or_create_folder("../wp-content/plugins/Personal area/download software/");

	if (empty($_POST['user'])){
		$all_users = get_children_for_parent(get_current_user_id());
	}
	else {
		$all_users = $_POST['user'];
	}

	return $all_users;

}

function create_folders_for_download_software($courses_ids, $questionnaire_ids, $main_json){
	$software_path = get_or_create_folder("../wp-content/plugins/Personal area/download software/");
	$installation_path = get_or_create_folder("../wp-content/plugins/Personal area/installation-zip/");
	$quizzes_path = get_or_create_folder("../wp-content/plugins/Personal area/quizzes-zip/");
	$icons_path = get_or_create_folder(dirname(__FILE__)."/icons/");

	if (empty($_POST['user'])){
		$folder_path = get_or_create_folder($software_path . get_current_user_id() . '_' . time() . "/");
		$file_name = get_current_user_id() . '_' . time() . '.zip';
	}
	else {
		$folder_path = get_or_create_folder($software_path . $_POST['user'][0] . '_' . time() . "/");
		$file_name = $_POST['user'][0] . '_' . time() . '.zip';
	}

	$lesson_path = get_or_create_folder($folder_path . 'lessons/');
	$quiz_path = get_or_create_folder($folder_path . 'quiz/');
	$data_path = get_or_create_folder($folder_path . 'data/');

	downloda_zip_courses($courses_ids);
	copy_lessons_course_folder($courses_ids, $lesson_path);
	create_quizs_folder($quizzes_path, $quiz_path, $questionnaire_ids);
	file_put_contents($data_path . "download_software.json", json_encode($main_json));
	copy('../wp-content/plugins/Personal area/eshkolot_setup.exe', $folder_path .'eshkolot_setup.exe');
	copy_directory($icons_path, $folder_path . 'icons');
	create_installation_zip($folder_path, $installation_path . $file_name);
	error_log(date('d-m-Y H:i:s ', time()). " after create_installation_zip");
	$file_renamed = str_replace(".zip", ".eshkolot", $file_name);
	rename($installation_path . $file_name, $installation_path . $file_renamed);
	$link = str_replace(" ", "%20", str_replace("../wp-content", "https://eshkolot.net/wp-content", $installation_path)) . $file_renamed;

	return $link;
}

function downloda_zip_courses($courses_ids){
	foreach ($courses_ids as $id) {
		downloadZip('../wp-content/plugins/Personal area/download softwer/lessons/', $id.'.zip');
	}
}

function copy_lessons_course_folder($courses_ids, $lesson_path) {
    foreach ($courses_ids as $id) {
		$source = plugin_dir_path(__FILE__) . "courses_videos/" . $id . '.zip';
		$destination = $lesson_path . $id . '.zip';
		if (file_exists($source)) {
			$result = copy($source, $destination);
		}
    }
}

function get_ID_user($id_user){
	$ID = get_user_meta($id_user,"user-id")[0];
	if(!$ID){
		$ID = "";
	}
	return $ID;
}

function get_knowledges_object($knowledges){

	$knowledges_object = array();

	foreach ($knowledges as $id=>$name) {

		// $id = $knowledge['id'];
		$knowledge_icon = get_term_meta($id, 'icon-knowledge', true);
		$knowledge_color = get_term_meta($id, 'color-knowledge', true);
		$text_color = get_term_meta($id, 'text-color', true);
		$icons_path = get_or_create_folder(dirname(__FILE__)."/icons/");
		$name_icon = download_file(wp_get_attachment_image_src($knowledge_icon)[0], $icons_path);

		$knowledges_object[$id] = array(
			"title" => $name,
			"icon"=> array('name_icon'=> $name_icon,
				'color'=>$knowledge_color,
				'text_color'=>$text_color,
				),
		);
	}

	return $knowledges_object;
}

function get_learn_path_object($learn_path_ids){
	
	$learn_paths = array();

	foreach ($learn_path_ids as $id) {

		$learnPath = get_post($id);

		$term_id = wp_get_post_terms( $id, 'knowledge' )[0]->term_id;
		$knowledge_color = get_term_meta($term_id, 'color-knowledge', true);
		$knowledge_color = $knowledge_color == false ? '': $knowledge_color;

		$learn_paths[$id] = array(
			"courses" => learndash_get_group_courses_list( $id ),
			"title" => $learnPath->post_title,
			"color"=> $knowledge_color,
		);
	}

	return $learn_paths;

}

function get_courses_object($courses_ids){

	$courses_object = array();
	
	// $courses_paths_product_ids = get_id_prod_for_all_courses_or_paths($courses_ids, 'courses_ids');

	foreach ($courses_ids as $id) {

		$all_ids_by_course = get_all_ids_by_course($id);

		$course = get_post($id);

		$term_id = wp_get_post_terms( $id, 'knowledge' )[0]->term_id;

		// $prod_id = $courses_paths_product_ids[$id];

		// print_r($courses_paths_product_ids);
		$vimeoID = get_post_meta($val,'vimeo-id', true);
		$vimeoID = $vimeoID == false? '': $vimeoID;

		$questionnaire = $all_ids_by_course['questionnaire_course_ids'] != NULL ? $all_ids_by_course['questionnaire_course_ids'] : array();
		$courses_object[$id] = array(
			"title"=>$course->post_title,
			"subjects"=>$all_ids_by_course['subject_ids'],
			"knowledge"=>$term_id,
			"questionnaire"=> $questionnaire,
			"countHours"=> get_post_meta($id, 'course-hours')[0],
			"countLesson"=> get_post_meta($id, 'lesson')[0],
			"countQuiz"=> get_post_meta($id, 'quiz')[0],
			"countEndQuiz"=> get_post_meta($id, 'end-quiz')[0],
			"knowledge_num"=> get_post_meta($id,'num-knowledge',true),
			"courseInformationVideo"=> get_vimeo_id(get_post_meta($id,'course-information-video', true)),
			"vimeoId"=> $vimeoID,
			"brief_information"=> get_post_meta($id,'brief-information', true),
			);
	}
	return $courses_object;
}

function get_subjects_object($subject_ids) {

	$subjects_object = array();

	foreach ($subject_ids as $id) {

		$course_id = learndash_get_course_id($id);

		$lesson_topics = learndash_get_topic_list($id, $course_id);

		$lesson_topics_ids = array();

		foreach($lesson_topics as $topic)
			$lesson_topics_ids  = insertIfNotExists($lesson_topics_ids, $topic->ID);

		$quizs_ids = get_quizs_ids($id);

		$subjects_object[$id] = array(
			"name"=> get_the_title($id),
			"course"=> array($course_id),
			"lessons"=> $lesson_topics_ids,
			"questionnaire"=> $quizs_ids,
			"time"=> get_post_meta($id, '_lds_duration')[0]
		);
	}

	return $subjects_object;
	
}

function get_lessons_object($lessons_ids){
	
	$lessons = array();

	foreach ($lessons_ids as $id=>$id_subject) {

		$lesson = get_post($id);

		$quizs_ids = get_quizs_ids($id);

		preg_match('/\d+/', $lesson->post_content, $matches);

		$lessons[$id] = array(
			"name"=> get_the_title($id),
			"idSubject"=>array($id_subject),
			"questionnaire"=> $quizs_ids,
			"vimoe"=>$matches[0],
			"time"=> get_post_meta($id, '_lds_duration')[0]
		);

	}

	return $lessons;
}

function get_quizs_ids($id){

	$quizs = learndash_get_lesson_quiz_list($id);

	if($quizs == NULL)
		$quizs_ids = array();

	foreach($quizs as $quiz)
		$quizs_ids  = insertIfNotExists($quizs_ids, $quiz["post"]->ID);

	return $quizs_ids;
}

function get_questionnaire_object($questionnaire_ids){
	
	$questionnaire = array();

	foreach ($questionnaire_ids as $quiz_id) {

			$quiz_urls = array();
			$pro_id = get_post_meta($quiz_id, 'quiz_pro_id')[0];
			$questionnaire_post = learndash_get_quiz_questions($quiz_id);
			$questionnaire[$quiz_id] = array();
			$questionnaire[$quiz_id]["title"] = html_entity_decode(get_the_title($quiz_id), ENT_QUOTES | ENT_HTML401, 'UTF-8');
			$questionnaire[$quiz_id]["questionList"] = array();
			$questionnaire[$quiz_id]["time"] = get_post_meta($quiz_id, '_lds_duration')[0];
			$quiz_materials = replace_urls_with_file_names(get_post_meta($quiz_id, '_sfwd-quiz', true)['sfwd-quiz_quiz_materials']);

			array_push(get_urls_from_str(get_post_meta($quiz_id, '_sfwd-quiz', true)['sfwd-quiz_quiz_materials']), $quiz_urls);
			// preg_match_all('/https?:\/\/[^\'"]+/i', get_post_meta($quiz_id, '_sfwd-quiz', true)['sfwd-quiz_quiz_materials'], $quiz_urls);

			$questionnaire[$quiz_id]["quiz_materials"] = $quiz_materials;
			$graded = getGreaded($pro_id);
			$questionnaire[$quiz_id]["grade1"] = $graded[0];
			$questionnaire[$quiz_id]["grade2"] = $graded[1];
			foreach($questionnaire_post as $question => $pro){
				$q = get_post($question);
				$fields_pro = leandash_get_question_pro_fields($pro,['answer_data','answer_type','question','points']);
				if(!empty($fields_pro["answer_data"])){
                  $ans = array();
				  $answer_urls = array();
                  foreach($fields_pro["answer_data"] as $a){
					$answer = $a->getAnswer();
					if(str_contains($answer, 'http')){

						array_push(get_urls_from_str($answer), $answer_urls);
						// preg_match_all('/https?:\/\/[^\'"]+/i', $answer, $answer_urls);

						$answer_urls = array_filter($answer_urls, fn($value) => !empty($value));
						array_push($quiz_urls, $answer_urls);
						$answer = replace_urls_with_file_names($answer);
					}
					if($fields_pro["answer_type"] == "free_answer"){
						$answer = str_replace("\n", "$", $answer);
						$answer = str_replace("\r", "$", $answer);

					}

					$sortString = $a->getSortString();
					$sortString_urls = get_urls_from_str($sortString);
					array_push($quiz_urls, $sortString_urls);

					array_push($ans, array(
						"isCurrect"=>$a->isCorrect(),
						"points"=> $a->getPoints(),
						'answer'             => $answer,
						'html'               => $a->isHtml(),
						'sortString'         => replace_urls_with_file_names($sortString),
						'sortStringHtml'     => $a->isSortStringHtml(),
						'graded'             => $a->isGraded(),
						'gradingProgression' => $a->getGradingProgression(),
						'gradedType'         => $a->getGradedType(),
					));
					//$ans = $fields_pro["answer_data"][0]->getAnswer();
                  }
				}
				else{
					$ans = "";
				}
                if(!empty($fields_pro["answer_type"])){
                  $type = $fields_pro["answer_type"];
                }
            	else{
                  $type = "";
                }
				$more_data = [];
				if($type == 'custom_editor'){

					$more_data = get_post_meta($question, 'custom_quiz_questions', true);

					foreach($more_data['custom_quiz_questions_fields'] as &$fields){

						$default_value_url = $fields['default_value'];

						$fields['default_value'] = replace_problematic_characters($default_value_url);

	
						$question_urls = get_urls_from_str($default_value_url, true);

						array_push($quiz_urls, $question_urls);

						// $more_data['custom_quiz_questions_fields']['default_value'] = 	edit_question_string($default_value_url);
					}

					if($question == 72001){
						$temp = $more_data['custom_quiz_questions_fields'][4];
						$more_data['custom_quiz_questions_fields'][4] = $more_data['custom_quiz_questions_fields'][5];
						$more_data['custom_quiz_questions_fields'][5] = $temp;
					}
				}
				$quiz_urls = array_filter($quiz_urls);
				$question_urls = get_urls_from_str($q->post_content);
				array_push($quiz_urls, $question_urls);

				$question_text = edit_question_string($q->post_content);
				
				array_push($questionnaire[$quiz_id]["questionList"], array(
					"id_ques" => $question,
					"question"=> $question_text,
					"ans"=>$ans,
					"type"=>$type,
                  	"pro"=>$pro,
					"more_data"=>$more_data,
                    "points"=>$fields_pro['points']
				));
			}
			$quiz_urls = flat_array($quiz_urls);
			$quiz_urls = array_values(array_unique($quiz_urls));
			$quiz_urls = fix_quiz_urls($quiz_urls);

			$questionnaire[$quiz_id]["quiz_urls"] = $quiz_urls;
			downloadZip('../wp-content/plugins/Personal area/quizzes_videos/', $quiz_id.'.zip');
			downloadZip('../wp-content/plugins/Personal area/download softwer/Quiz zip files/', $quiz_id.'.zip');
	}

	return $questionnaire;
}

function get_courses_and_paths_product($id_prod) {

	$courses_ids = get_post_meta($id_prod, '_related_course', true);

	$groups = get_post_meta($id_prod, '_related_group', true);

	$single_courses_ids = array();

	foreach($groups as $group_id){
		
		$group = get_post($group_id);
		$group_courses_ids = learndash_get_group_courses_list( $group_id );

		if(count($group_courses_ids) > 1){
			$path_ids = insertIfNotExists($path_ids, $group_id);
		}
		else
			$single_courses_ids = array_merge_unique($single_courses_ids, $group_courses_ids);

		$courses_ids = array_merge_unique($courses_ids, $group_courses_ids);

	}

	$details = array();
	$details["courses_ids"] = $courses_ids;
	$details["single_courses_ids"] = $single_courses_ids;
	$details["path_ids"] = $path_ids;

	return $details;

}

function get_id_prod_for_all_courses_or_paths($id, $by) {

	if($by == 'user_id')
		$products = get_user_meta($id, 'product_offline')[0];
	if($by == 'courses_ids')
		$products = get_products_by_courses_ids($id);

	$courses_paths_product = array();
	foreach ($products as $prod) {
		$courses_ids = get_courses_and_paths_product($prod['id'])["courses_ids"];
		foreach ($courses_ids as $id) {
			$courses_paths_product[$id] = $prod['id'];
		}
	}

	return $courses_paths_product;
}

function get_products_by_courses_ids($ids) {
	
	$products = array(
        'posts_per_page'   => -1,
        'post_type'        => 'product',
		'meta_query' => array(
            '0' => array(
                'key' => '_related_course',
                'value' => $ids,
                'compare' => 'IN',
            ),
            'relation' => 'AND',
        ),
    );
    $products = new WP_Query( $products );

    $products_res = array();
    if ( $products->have_posts() ) :
            while ( $products->have_posts() ) : $products->the_post();
            	$products_res []= get_the_id();
        endwhile;
    endif;

	return $products_res;
}

function array_merge_unique($a1, $a2){

	if($a1 == null || $a2 == null)
		return (empty($a1) || $a1 == NULL) ? $a2: $a1;

	$mergedArray = array_merge($a1, $a2);
	$uniqueArray = array_intersect_key($mergedArray, array_unique($mergedArray));

	return $uniqueArray;
}

function object_merge_unique($a1, $a2){

	if($a1 == null || $a2 == null)
	return (empty($a1) || $a1 == NULL) ? $a2: $a1;

	foreach ($a2 as $key => $value) {
        $a1[$key] = $value;
    }
    return $a1;
}

function get_knowledge_by_course($course_id){
	return wp_get_post_terms( $course_id, 'knowledge' )[0];
}

function insertIfNotExists($array, $value) {

	if(empty($array) || $array == NULL)
		$array = array();

    if (!in_array($value, $array)) {
        $array[] = $value;
    }

	return $array;
}

function fix_quiz_urls($quiz_urls){
	$fixed_urls = array();
	foreach ($quiz_urls as $url) {
			if(str_contains($url, '//eshkolot.netwp-content')){
					$url = str_replace('//eshkolot.netwp-content', '//eshkolot.net/wp-content', $url);
			}
			if(str_contains($url, '//lms.ussl.co.il/wp-content')){
					$url = str_replace('//lms.ussl.co.il/wp-content', '//eshkolot.net/wp-content', $url);
			}
			if(str_contains($url, 'http://https://')){
					$url = str_replace('http://https://', 'https://', $url);
			}
		$endPosition = strcspn($url, " \t\n\r\0\x0B][");
		$url = substr($url, 0, $endPosition);
			$fixed_urls[] = $url;        
	}
	return $fixed_urls;
}

function get_url_to_download(){
	if(empty(get_user_meta(get_current_user_id(), 'children', true))){
        echo '/%D7%90%D7%A9%D7%9B%D7%95%D7%9C%D7%95%D7%AA-%D7%90%D7%95%D7%A4%D7%9C%D7%99%D7%99%D7%9F/';
    }
    else{
        echo '/personal-area/';
    }
}
add_shortcode('get_url_to_download', 'get_url_to_download');

function perform_refund($idUser, $course_id) {
	$price = get_post_meta($course_id, '_price', true);
	$refund = get_user_meta($idUser, 'refund_' . $course_id, true);
	$authnr = get_user_meta($idUser, 'authnr_' . $course_id, true);
	$TranzilaTK = get_user_meta($idUser, 'TranzilaTK_' . $course_id, true);
	$expdate = get_user_meta($idUser, 'expdate_' . $course_id, true);
	$index = get_user_meta($idUser, 'index_' . $course_id, true);
	
	if($refund == false) {
		$tranzila_pay = new Tranzila_Payment();

		$payment_data = array(
		  'sum' => $price,
		  'authnr' => $authnr,
		  'TranzilaTK' => $TranzilaTK,
		  'expdate' => $expdate,
		  'index' => $index
		);
		
		$result = $tranzila_pay->create_credit_transaction($payment_data);
		if ($result['result'] == '1') {
			update_user_meta($idUser, 'refund_' . $course_id, true);
		}
	}
}

add_action('wp_ajax_add_download_action', 'add_download_action');
add_action('wp_ajax_nopriv_add_download_action', 'add_download_action');

function add_download_action() {
	$uids = get_users_ids();
	$uids = implode(", ", $uids);

	$new_post = array(
		'post_type'     => 'download_requests',
        'post_title'    => 'download_action',
        'post_content'  => $uids,
        'post_status'   => 'publish'
    );
    $post_id = wp_insert_post($new_post);
}


?>
