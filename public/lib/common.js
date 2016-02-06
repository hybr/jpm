function addSubField(addId, templateId, indexId) {
	var totalSubFields = $("#" + addId + " > fieldset").size() + 1;
	var re = new RegExp(indexId, "g");
	var t = $("#" + templateId).html();
	t = t.replace(re, totalSubFields);
	$(t).appendTo('#' + addId);
}

function removeSubFields(targetFieldId) {
	$("#" + targetFieldId).remove();
}

$(function() {
	$.fn.styleTable = function(options) {
		var defaults = {
			css : 'ui-styled-table'
		};
		options = $.extend(defaults, options);

		return this.each(function() {
			$this = $(this);
			$this.addClass(options.css);

			$this.on('mouseover mouseout', 'tbody tr', function(event) {
				$(this).children().toggleClass("ui-state-hover",
						event.type == 'mouseover');
			});

			$this.find("th").addClass("ui-state-default");
			$this.find("th").addClass("jpmHeaderPadding");
			
			$this.find("td").addClass("ui-widget-content");
			$this.find("td").addClass("jpmContentPadding");
			
			$this.find("tr:last-child").addClass("last-child");
		});
	};

	$(".showTable").styleTable();
});

$(function() {
	$("input[type=reset], input[type=submit], a, button").button();
});



var currentTab = 0; 
$(document).ready(function() {
    $( ".tabsImageSlider" ).tabs();
    var numberOfTabs = $('.tabsImageSlider  >ul >li').size();
    setInterval(function() {
        currentTab = (++currentTab < numberOfTabs ? currentTab : 0);
        $( ".tabsImageSlider" ).tabs( "option", "active", currentTab);
    }, 8000);
});


$.widget( "ui.timespinner", $.ui.spinner, {
	options: {
		// seconds
		step: 60,
		// hours
		page: 60
	},
     /* 
	_parse: function( value ) {
		seconds = value;
		if (seconds == '') {
			var d = new Date();
			return d.getSeconds();
		}
		if ( Number( seconds ) == seconds ) {
			return Number( seconds );
		}
		if ( typeof value === "string" ) {
			// already a timestamp
			var a = value.split(' ');
			var b = a[0].split(':');
			var seconds = (+b[0]) * 60 * 60 + (+b[1]) * 60 + (+b[2]); 
			return seconds;
		}
		return Number(seconds);
		var d = new Date(value);
		return d.getSeconds();
		return d.getHours() + ':' + d.getMinutes();

		if ( typeof value === "string" ) {
			// already a timestamp
			if ( Number( value ) == value ) {
				return Number( value );
			}
			//return +Globalize.parseDate( value );
			return value;
		}
		return value;
	},

	_format: function( value ) {
		// return Globalize.format( new Date(value), "t" );
		var d = new Date(value);
		return d.toTimeString();
	}

*/
}); /* "ui.timespinner" */

$(function() {

	$('#jpm_home_page_menu').menu();

	$('#jpm_home_page_tabs').tabs();

	$(document).on('focus', '.jpm_datepicker', function() {
		var element = this;
		$(element).datepicker({
			changeMonth: true,
			changeYear: true,
			showWeek: true,
			dateFormat: "yy-M-dd"
		});
	}); /* .jpm_datepicker */


	$(document).on('focus', '.jpm_timepicker', function() {
		var element = this;
		$(element).timespinner({
			value: element.value()
		});
	}); /* .jpm_timepicker */

	/* url : 'http://api.geonames.org/search', */
	/* * username : 'yogeshgn', name_startsWith : request.term, type : 'JSON', */
	$(document).on( 'focus', '.jpm_geonames_city', function() {
		var element = this;
		$(element).autocomplete({
			source : function(request, response) {
				jQuery.getJSON(
					"http://gd.geobytes.com/AutoCompleteCity?callback=?&q=" + request.term, 
					function(data) { response(data); }
				);
			},
			minLength : 3,
			select : function(event, ui) {
				var selectedObj = ui.item;
				$(element).val(selectedObj.value);
				return false;
			},
			open : function() {
				jQuery(this).removeClass("ui-corner-all")
						.addClass("ui-corner-top");
			},
			close : function() {
				jQuery(this).removeClass("ui-corner-top")
						.addClass("ui-corner-all");
			}
		}); /* $(this) */
		$(element).autocomplete("option", "delay", 100);
	}); /* on */

	$(document).on('focus', '.jpm_foreign_key_input', function() {
		var element = this;
		$(element).autocomplete({

			source : function(request, response) {
				$.ajax({
					url : "/query.php",
					dataType : "json",
					data : {
						p : request.term,
						c : $(element).attr('jpm_foreign_collection'),
						sf : $(element).attr('jpm_foreign_search_fields'),
						tf : $(element).attr('jpm_foreign_title_fields')
					},
					success : function(data) {
						response(data);
					}
				});
			},
			minLength : 0
		}); /* $(this) */
	}); /* on */

});
