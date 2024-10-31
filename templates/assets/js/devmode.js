(function( $ ) {
    "use strict";	
	
	$("body").on("click","#ajax, #tabdisplaying",function(event){
		event.preventDefault();
		dothepost();
		return false;
	});
	
	$("body").on("change","#ajaxstyle",function(event){
		event.preventDefault();
		dothepost();
		return false;
	});
	
	function dothepost() {
		var data = {
			'action': 'bhp_live_preview',
			'stupid': Date(),
			'_wpnonce': $('#_wpnonce').val(),
            'preview_formdata': $('#workHours').serializeArray()
		}
		// var data = $('#workHours').serializeArray();
		 $.post(ajaxurl,data,function(response){
            $('#jqconsole').html(response);
            console.log(data);
		});
	};
	
}(jQuery));


