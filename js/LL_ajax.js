function like(button) {
	var id = button.id.replace( /^\D+/g, '');
	var text =  button.value;

    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : ajaxElement.ajaxurl,
        data : {action: "like_button_clicked",id:id,state:text},
        success: function(response) {
            button.value = response.text ;
         }
    });
}
