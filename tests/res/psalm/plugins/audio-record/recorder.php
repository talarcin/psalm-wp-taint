<?php
/**
 * @package ThikShare
 * @version 1.0
 */
/*
Plugin Name: Audio Record
Plugin URI: http://thikshare.com/audio-record-plugin/
Description: Record audio from user microphone. Use shortcode [record_audio] to show record bar. Support https domain only!
Author: Dang Ngoc Binh
Version: 1.0
Author URI: http://thikshare.com
*/


add_action( 'wp_enqueue_scripts', 'ar_enqueue_js_record' );


function ar_enqueue_js_record($hook) {
	wp_enqueue_script( 'record-js', plugins_url( 'js/MediaStreamRecorder.js', __FILE__ ), array('jquery') );	
	wp_localize_script( 'record-js', 'ajax_record', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),  ) );
}

add_shortcode('record_audio', 'ar_func_record_audio' );

function ar_func_record_audio(){
	ob_start();
	?>
	<div id="recorder" class="record-audio">
	
	<div class="r-left">
		<div class="title-record">Mic Record: </div>
		<div class="time-record" style="display: none;">00:00:00</div>
	</div>
	<div class="r-right">
		<div class="rcontrol start" title="Bắt Đầu Ghi Âm"></div>
		<div class="rcontrol pause" title="Tạm Ngưng Ghi Âm"> </div>
		<div class="rcontrol stop" title="Lưu Đoạn Ghi Âm"></div>
	</div>
	<div class="notice" style="display: none">
	</div>

	</div>
	<style>
	.record-audio {
        padding: 10px 10px;
        background: #F75E11;
        margin: 10px 0px;
        color: #fff;
        width: 100%;
        float: left;
        border-radius: 8px 8px;
        border: 1px solid #CCDEDD;
        max-width: 300px;
    }
    
    .title-record{
	    font-size: 16px;
	    line-height: 30px;
	    text-decoration: underline;
    }
    
	.r-left{
		width: 40%;
		float:left;
	}	
    
    .r-right {
        width: 60%;
        float: left;
        position: relative;
        top: 0px;
    }

	.rcontrol:hover{
		background: yellow;
		border-left-color: yellow !important;
		cursor: pointer;
	}

	.rcontrol{
		width: 30px;
		height: 30px;
		float:left;
		background: #fff;
		margin: 0px 9px;
	}

	.rcontrol.start{
		width: 30px;
		-moz-border-radius: 15px;
		-webkit-border-radius: 15px;
		border-radius: 15px;
	}
	.rcontrol.pause{
		position: relative;
		background: none;
		display: none;
		width: 23px;
	}
	.rcontrol.pause:after{
		width: 10px;
		height: 30px;
		background: #fff;
		content: '';
		display: block;
		position: absolute;
		right: 0;
	}

	.rcontrol.pause:before{
		width: 10px;
		height: 30px;
		background: #fff;
		content: '';
		display: block;
		position: absolute;
		left: 0;
	}
	.notice{
		background: yellow;
		color: black;
		clear: both;
		font-size: 10px;
	    margin-top: 40px;
		padding: 5px 5px;
	}
	.rcontrol.stop{

	}

	.rcontrol.pause:hover:before{
		background: yellow;
	}

	.rcontrol.pause:hover:after{
		background: yellow;
	}

	@keyframes recording {
	    from {background-color: white;}
	    to {background-color: yellow;}
	}

	.playing .start{
		animation-name: recording;
		animation-duration: 1s;
		animation-iteration-count: infinite;
	}

	</style>
	
	<script>
	var mediaConstraints = {
	    audio: true
	};
	jQuery(document).ready(function(){
		jQuery('#recorder .start').click(function(){
			mediaRecorder.start(300000); // 5 phut
			jQuery('#recorder').addClass('playing');
		});

		jQuery('#recorder .pause').click(function(){
			mediaRecorder.pause();
			jQuery('#recorder').removeClass('playing');
		});

		jQuery('#recorder .stop').click(function(){
			mediaRecorder.stop();
			jQuery('#recorder').removeClass('playing');
		});
	});

	

	navigator.getUserMedia(mediaConstraints, onMediaSuccess, onMediaError);
	var mediaRecorder;
	function onMediaSuccess(stream) {
	    mediaRecorder = new MediaStreamRecorder(stream);
	    mediaRecorder.mimeType = 'audio/wav';
	    mediaRecorder.ondataavailable = function (blob) {
	        mediaRecorder.stop();
	        save_my_record(blob);
	        jQuery('#recorder').removeClass('playing');
	        alert('Đoạn ghi âm của bạn đã được gởi!');
	    };
	    
	}

	function onMediaError(e) {
	    console.error('media error', e);
	    jQuery('#recorder .notice').text('Vui lòng kiểm tra Microphone. Chức năng này chạy tốt nhất trên Chrome hoặc Firefox.')
	    jQuery('#recorder .notice').fadeIn('4000');
	}

	function save_my_record(blob){
		var fileType = 'audio'; 
		var fileName = 'testfile.webm'; 
		var course_id = jQuery('#course_id').val();
		var unit_id = jQuery('#unit').attr('data-unit');
		var formData = new FormData();
		formData.append(fileType + '-filename', fileName);
		formData.append(fileType + '-blob', blob);
		formData.append('action', 'save_record');
		formData.append('course_id', course_id);
		formData.append('unit_id', unit_id);

		jQuery.ajax({
		  url: ajax_record.ajax_url,
		  data: formData,
		  processData: false,
		  contentType: false,
		  type: 'POST',
		  success: function(data){
		    //alert(data);
		  }
		});

		
	}

	function xhr(url, data, callback) {
	    var request = new XMLHttpRequest();
	    request.onreadystatechange = function () {
	        if (request.readyState == 4 && request.status == 200) {
	            callback(location.href + request.responseText);
	        }
	    };
	    request.open('POST', url);
	    request.send(data);
	}
	</script>

	<?php
	return ob_get_clean();
}


add_action( 'wp_ajax_save_record', 'save_record_callback' );
add_action( 'wp_ajax_nopriv_save_record', 'save_record_callback' );

function save_record_callback() {
	
	foreach(array('audio') as $type) {
	    if (isset($_FILES["${type}-blob"])) {

	        $fileName = uniqid() . '_' .$_POST["${type}-filename"] ;
	        $path_array  = wp_upload_dir();
	        $path = str_replace('\\', '/', $path_array['path']);
	        $uploadDirectory = $path . "/$fileName";
	        if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
	        	echo 000;
	            wp_die("problem moving uploaded file");
	        }

	        
	        $my_post = array(
			  'post_title'    => $fileName,
			  'post_content'  => '',
			  'post_status'   => 'publish',
			  'post_author'   => 1,
			  'post_type' => 'audiorecord',
			);
			 
			// Insert the post into the database
			$post_id = wp_insert_post( $my_post );
			$current_user = wp_get_current_user() ? wp_get_current_user() : 0;
			update_post_meta($post_id, 'upload_url', $path_array['url']);
			update_post_meta($post_id, 'upload_path', $uploadDirectory);
			update_post_meta($post_id, 'course_menber', $current_user->ID);
	        echo 1;
	    }
	}

	wp_die(); 
}

add_action( 'init', 'ar_create_post_type_audio_record' );
function ar_create_post_type_audio_record() {
  register_post_type( 'audiorecord',
    array(
      'labels' => array(
        'name' => __( 'Mic Record' ),
        'singular_name' => __( 'Mic Record' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}

add_action( 'manage_audiorecord_posts_custom_column' , 'ar_custom_columns_record_audio', 10, 2 );

function ar_custom_columns_record_audio( $column, $post_id ) {
	switch ( $column ) {
		case 'preview':
			$post_title = get_the_title($post_id ); 
			$upload_url = get_post_meta($post_id, 'upload_url', true );
			
			?>
			<video controls style="height: 56px">
			  <source src="<?php echo $upload_url . '/' . $post_title; ?>" type="video/webm">
			  Browser not supported
			</video>
			<?php
			break;		
		case 'member':

			$member_id = get_post_meta($post_id, 'course_menber', true );
			if($member_id){
				$user_data = get_userdata($member_id);
				$user_record = 	$user_data->user_nicename;
				$user_email = $user_data->user_email;
			}else{
				$user_record = 	"Guest";
				$user_email = '';
			}
			

			echo '<a href="mailto:'. $user_email .'">'. $user_record .'</a>';
			break;
	}
}

add_filter( 'manage_audiorecord_posts_columns', 'ar_set_custom_edit_audiorecord_columns' );
function ar_set_custom_edit_audiorecord_columns($columns) {
    $columns['preview'] = __( 'Preview', 'your_text_domain' );
    $columns['member'] = __( 'Member', 'your_text_domain' );
    return $columns;
}

add_action( 'admin_init', 'ar_codex_admin_init' );
function ar_codex_admin_init() {
    add_action( 'before_delete_post', 'ar_detele_post_delete_audio_also', 10 );
}


function ar_detele_post_delete_audio_also( $pid ) {
	$path = get_post_meta($pid, 'upload_path', true );
	var_dump($path);
	unlink($path);
}

?>