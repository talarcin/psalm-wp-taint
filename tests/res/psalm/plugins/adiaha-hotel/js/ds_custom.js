jQuery(function() {
	jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
	_renderMenu: function( ul, items ) {
		var that = this,
		currentCategory = "";
		var my_Category_ID = 1;
		jQuery.each( items, function( index, item ) {
		if ( item.category != currentCategory ) {
			//ul.append( "<li class='ui-autocomplete-category jquery_UI_"+item.class_Name+"'>" + item.category + "</li>" );
			my_Category_ID++;
			currentCategory = item.category;
		}
			that._renderItemData( ul, item );
		});
	}
  });
	jQuery("#ds_hotelname" ).catcomplete({
		source:function(request, response) {
			var Search_Cri = jQuery("#ds_hotelname").val();
			jQuery.ajax({
				url: "https://www.adivaha.com/demo/plugins/api.php",
				dataType: "json",
				data: {
					term: Search_Cri,
					action: "autoSuggetionLookup"
				},
				success: function(data) { 
					response(data);
				}
			});
		},
		minLength:2,
		select: function(event, ui) { 
			jQuery("#ds_hotelname").val(ui.item.label);
			jQuery("#ds_hotelid").val( ui.item.regionid);
		}
	});
});


function Update_Video(){
	document.getElementById("video_gal").innerHTML = '<iframe width="560" id="vidgallery" height="315" src="https://www.youtube.com/embed/NBMbxJ9n98c?rel=0&autoplay=1";" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>';
}




