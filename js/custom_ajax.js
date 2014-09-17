function my_js_function()
{
	//alert("hello");
	var parent=jQuery('#parent_cat').val();
	jQuery.ajax({
		url: ajax_script.ajaxurl,
		data: ({action : 'get_my_option',parent_id:parent}),
		success: function(data) {
			
			jQuery("#sub_cat").html(data)
			
		}
	});
}