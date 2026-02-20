/** ********************************************** **
	Your Custom Javascript File
	Put here all your custom functions
*************************************************** **/



/** Remove Panel
	Function called by app.js on panel Close (remove)
 ************************************************** **/
	function _closePanel(panel_id) {
		/** 
			EXAMPLE - LOCAL STORAGE PANEL REMOVE|UNREMOVE

			// SET PANEL HIDDEN
			localStorage.setItem(panel_id, 'closed');
			
			// SET PANEL VISIBLE
			localStorage.removeItem(panel_id);
		**/	
	}

function jqueryPost(action, input) {

	"use strict";
	var form;
	form = $('<form />', {
		action: action,
		method: 'post',
		style: 'display: none;'
	});
	if (typeof input !== 'undefined') {

		$.each(input, function (name, value) {

			if (typeof value === 'object') {

				$.each(value, function (objName, objValue) {

					$('<input />', {
						type: 'hidden',
						name: name + '[]',
						value: objValue
					}).appendTo(form);
				});
			} else {

				$('<input />', {
					type: 'hidden',
					name: name,
					value: value
				}).appendTo(form);
			}
		});
	}
	form.appendTo('body').submit();
}