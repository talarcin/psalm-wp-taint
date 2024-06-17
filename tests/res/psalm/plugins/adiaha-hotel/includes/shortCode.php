<?php
function adivaha_ds_HotelDescription ($atts, $content = null) {
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );
 $propertyDescription =trim($response['HotelInformationResponse']['HotelDetails']['propertyDescription']); 
 $html ='<div>
           <p>'.$response['HotelInformationResponse']['HotelDetails']['propertyInformation'].'</p>
           '.htmlspecialchars_decode($propertyDescription).'
        </div>';
  return $html;		
}
add_shortcode('adivaha_ds_HotelDescription','adivaha_ds_HotelDescription');
function adivaha_ds_HotelRating ($atts, $content = null) {
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );	
 $hotelRating =$response['HotelInformationResponse']['HotelSummary']['hotelRating'];
 return $hotelRating;
}
add_shortcode('adivaha_ds_HotelRating','adivaha_ds_HotelRating');
function adivaha_ds_HotelReview ($atts, $content = null) {
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );	
 $tripAdvisorRatingUrl =$response['HotelInformationResponse']['HotelSummary']['tripAdvisorRatingUrl'];
 return '<img src="'.$tripAdvisorRatingUrl.'">';
}
add_shortcode('adivaha_ds_HotelReview','adivaha_ds_HotelReview');
function adivaha_ds_HotelPointofInterest ($atts, $content = null) {
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );	
 $areaInforamtion =$response['HotelInformationResponse']['HotelDetails']['areaInformation'];
 return '<p>'.htmlspecialchars_decode($areaInforamtion).'</p>';
}
add_shortcode('adivaha_ds_HotelPointofInterest','adivaha_ds_HotelPointofInterest');
function adivaha_ds_HotelCheckInInstructions ($atts, $content = null) {
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );	
 $checkInInstructions =$response['HotelInformationResponse']['HotelDetails']['checkInInstructions'];
 return '<p>'.htmlspecialchars_decode($checkInInstructions).'</p>';
}
add_shortcode('adivaha_ds_HotelCheckInInstructions','adivaha_ds_HotelCheckInInstructions');
function adivaha_ds_HotelAmeneties(){
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );
 $PropertyAmenities =$response['HotelInformationResponse']['PropertyAmenities'];
 if($PropertyAmenities['size']>1){
   $HotelAmenities =$PropertyAmenities['PropertyAmenity'];	 
 }else{
   $HotelAmenities[] =$PropertyAmenities['PropertyAmenity'];	  
 }
 $html='<ul>';
 for($i=0;$i<count($HotelAmenities);$i++){
  $html.='<li>'.$HotelAmenities[$i]['amenity'].'</li>';	 
 }
 $html.='</ul>';
 return $html;
}
add_shortcode('adivaha_ds_HotelAmeneties','adivaha_ds_HotelAmeneties');
function adivaha_ds_HotelImages(){
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );
 $HotelImages =$response['HotelInformationResponse']['HotelImages'];
 if($HotelImages['size']>1){
   $Images =$HotelImages['HotelImage'];	 
 }else{
   $Images[] =$HotelImages['HotelImage'];	  
 }
 $html='<div class="ds_slider">';
 for($i=0;$i<count($Images);$i++){
   if($i==0){$activeGall ='ds_curry';}	
   else{$activeGall='';}   
   $html.='<div class="ds_img '.$activeGall.'"><img src="'.str_replace("_b","_y",$Images[$i]['url']).'" /></div>';
 }
  $html.='<div class="ds_nav"><div class="ds_prev"><a href="javascript:void(0);">prev</a></div><div class="ds_next"><a href="javascript:void(0);">next</a></div></div>';
 $html.='</div>';
 $html.="<script>jQuery(document).ready(function() {
  jQuery('.ds_next').click(function() {
    var currentImage = jQuery('.ds_img.ds_curry');
    var currentImageIndex = jQuery('.ds_img.ds_curry').index();
    var nextImageIndex = currentImageIndex + 1;
    var nextImage = jQuery('.ds_img').eq(nextImageIndex);
    currentImage.fadeOut(1000);
    currentImage.removeClass('ds_curry');
    if (nextImageIndex == (jQuery('.ds_img:last').index() + 1)) {
      jQuery('.ds_img').eq(0).fadeIn(1000);
      jQuery('.ds_img').eq(0).addClass('ds_curry');
    } else {
      nextImage.fadeIn(1000);
      nextImage.addClass('ds_curry');
    }
  });
  jQuery('.ds_prev').click(function() {
    var currentImage = jQuery('.ds_img.ds_curry');
    var currentImageIndex = jQuery('.ds_img.ds_curry').index();
    var prevImageIndex = currentImageIndex - 1;
    var prevImage = jQuery('.ds_img').eq(prevImageIndex);

    currentImage.fadeOut(1000);
    currentImage.removeClass('ds_curry');
    prevImage.fadeIn(1000);
    prevImage.addClass('ds_curry');
  });

})</script>";

 return $html;
}
add_shortcode('adivaha_ds_HotelImages','adivaha_ds_HotelImages');


function adivaha_ds_HotelMap(){
 $post_ID =get_the_ID();	
 $ds_hotelAPIData = get_post_meta($post_ID, 'ds_hotelAPIData', true );	
 $ds_hotelAPIData = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $ds_hotelAPIData);
 $ds_hotelAPIData = str_replace('&quot;', '"', $ds_hotelAPIData);
 $response =json_decode( stripslashes($ds_hotelAPIData) , true );
 $latitude =$response['HotelInformationResponse']['HotelSummary']['latitude'];
 $longitude =$response['HotelInformationResponse']['HotelSummary']['longitude'];

 return '<div><iframe frameborder="0" width="100%" height="480" src="https://www.adivaha.com/demo/ean-theme-ml/wp-content/themes/adivaha/scripts-libraries/search-result-google-map.php?lat='.$latitude.'&amp;long='.$longitude.'&amp;paths=https://www.adivaha.com/demo/ean-theme-ml/wp-content/themes/adivaha"></iframe></div>';
}
add_shortcode('adivaha_ds_HotelMap','adivaha_ds_HotelMap');

function adivaha_ds_TripAdvisorReview(){
 $post_ID =get_the_ID();
 $ds_hotelid = get_post_meta($post_ID, 'ds_hotelid', true );  
 $URLs_Fetch = "https://www.adivaha.com/demo/plugins/api.php?action=Trip_Advisor_Feedback&hotel_id=".$ds_hotelid; 
 $response = wp_remote_get($URLs_Fetch);
 $Trip_Advisor_Feedback_Long = wp_remote_retrieve_body( $response );
 $Trip_Advisor_Feedback_Long = str_replace("#589442", "#d9e4c4", $Trip_Advisor_Feedback_Long);
 return '<p>'.$Trip_Advisor_Feedback_Long.'</p>';
}
add_shortcode('adivaha_ds_TripAdvisorReview','adivaha_ds_TripAdvisorReview');

?>