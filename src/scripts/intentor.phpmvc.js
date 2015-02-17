$(document).ready(function() {
    $(this).ajaxStart(function () { $("#loading").show(); });
    $(this).ajaxStop(function () { $("#loading").hide(); });

	prepare_defaults();
	prepare_forms();
});

/**
 * Prepares fields for default values.
 */
function prepare_defaults() {
	$(document).find("*[custom\\:default_label]").each(function(index) {
		$(this).val($(this).attr("custom:default_label"));
		$(this).css("color", $(this).attr("custom:default_color"));
		
		$(this).focusin(function(event) {
			if ($(this).attr("custom:default_label") == $(this).val()) {
				$(this).val("");
				$(this).css("color", "");
			}
		});
		
		$(this).focusout(function(event) {
			if ($(this).val() == ''){
				$(this).val($(this).attr("custom:default_label"));
				$(this).css("color", $(this).attr("custom:default_color"));
			}
		});

		$(this).keydown(function(event){
			$(this).css("color", "");
		});
	});
}

/**
 * Prepares forms.
 */
function prepare_forms() {
	//If there's any form, subscribe for submit event.
	$("form").submit(function(e) {
		//Blocks any submit button.
		$("input[type=submit]").attr("disabled", true);
		
		//If there's any error, shows up the message.
		if (!validate_form($(this))) {
			//Unblocks any submit buttons.
			$("input[type=submit]").removeAttr("disabled");
			
			e.preventDefault();
			return false;
		}
	});

	//Removes any disabled attribute from submits.
	$("input[type=submit]").removeAttr("disabled");
}
	
/**
 * Validates a form according to its controls and annotations.
 * @param $form jQuery object of the form.
 * @return Boolean value indicating if the form is validated.
 */
function validate_form($form) {
	var validated = true;
	
	//Checks for any elements with a "custom:required" attribute inside the form.
	$form.find("*[custom\\:required]").each(function(index) {
		var def_label = $(this).attr("custom:default_label");
		
		//Checks if the required element has value.
		if ($(this).val() == '' || (typeof def_label !== 'undefined' && def_label !== false && $(this).val() == def_label)) {
			display_validation_error($(this), $(this).attr("custom:required"));				
			validated = false;
		}
	});
	
	//Only checks the next option if it's validated.
	if (validated) {
		//Checks for any elements with a "custom:compare_to" attribute inside the form.
		$form.find("*[custom\\:compare_to]").each(function(index) {			
			//Compares the values of the current element with the other on "custom:compare_to".	
			if ($(this).val() != $("#" + $(this).attr("custom:compare_to")).val()) {
				display_validation_error($(this), $(this).attr("custom:compare_message"));
				validated = false;
			}
		});
	}
	
	//Only checks the next option if it's validated.
	if (validated) {
		//Checks for any elements with a "custom:regex" attribute inside the form.
		$form.find("*[custom\\:regex]").each(function(index) {
			//Executes the regex validation.
			var exp = new RegExp($(this).attr("custom:regex"));
			
			if (!$(this).val().match(exp)) {
				display_validation_error($(this), $(this).attr("custom:regex_message"));
				validated = false;
			}
		});
	}

	//Only checks the next option if it's validated.
	if (validated) {
		//Checks for any elements with a "custom:function" attribute inside the form.
		$form.find("*[custom\\:function]").each(function(index) {
			//Executes the custom validation function.
			if (!window[$(this).attr("custom:function")]()) {
				display_validation_error($(this), $(this).attr("custom:function_message"));
				validated = false;
			}
		});
	}
	
	return validated;
}

/**
 * Shows a message box.
 * @param title Message box title.
 * @param message Message box message.
 * @param css Additional CSS class to be included.
 */
function message_box(title, message, css) {
    var $d = $("#dialog-modal");

    if ($d.length == 0) {
        $("body").append('<div id="dialog-modal"><span' + (css != "" && css != undefined ? ' class="' + css + '"' : '') + '>' + message + '</span></div>');
        $d = $("#dialog-modal");
    } else {
        $d.html('<span' + (css != "" && css != undefined ? ' class="' + css + '"' : '') + '>' + message + '</span>');
    }

    $d.dialog({
    	title: title,
        width: 400,
        height: 180,
        modal: true,
        resizable: false,
        buttons: {
            "Ok": function () {
                $(this).dialog("close");
            }
        },
    });
}

/**
 * Redirects to a certain URL.
 * @param url URL to redirect.
 */
function redirect(url) {
	window.location.href = url;
}

/*==SUPPORT==*/

/**
 * Displays validation error on a certain element.
 * @param $element Element to be prepared.
 * @param $message Validation message to be shown.
 */
function display_validation_error($element, $message) {
	//Adds the validation_error CSS class.
	$element.addClass("validation_error");
	//Fills and shows the validation message, if there's any.
	$("#val_" + $element.attr("name")).show().text($message);
	//Attaches blur event.
	$element.blur(function() {
		if ($(this).val() != '') {
			$(this).removeClass("validation_error");
			$("#val_" + $(this).attr("name")).hide();
		}
	});
}