
$(function() { // document ready

	var emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
	tips = $( ".validateTips" ),
	fname = $( "#fname" ),
	lname = $( "#lname" ),
	phone = $( "#phone" ),
	role = $( "#role" ),
	email = $( "#email" ),
	password = $( "#password" ),
	allFields = $( [] ).add( fname ).add( lname ).add( phone ).add( role ).add( email ).add( password )
	event_guests = [],
	event_title = $( "#event_title" ),
	event_from = $( "#event_from" ),
	event_to = $( "#event_to" ),
	event_repeat_interval = $( "#repeat_interval" ),
	event_repeat_every = $( "#repeat_every" ),
	event_repeat_to = $( "#repeat_to" );
		
	var m = moment(); 
	var roundUp = m.minute() || m.second() || m.millisecond() ? m.add(1, 'hour').startOf('hour') : m.startOf('hour');
	var init_datetime = roundUp.format("YYYY/MM/DD hh:mm:ss");
	$('#event_from').val(init_datetime);
	$('#event_to').val(init_datetime);
	$('#repeat_to').val(init_datetime);
		
	function update_tips( t ) 
	{
		tips.text( t ).addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	function event_repeat()
	{
		if ($('#repeat_tog').is(':checked'))
		{
			$('#repeat_options').removeAttr('hidden');
			$('#repeat_interval').removeAttr('disabled');
			$('#repeat_every').removeAttr('disabled');
			$('#repeat_to').removeAttr('disabled');
		}
		else
		{
			$('#repeat_options').attr('hidden','hidden');
			$('#repeat_interval').attr('disabled','disabled');
			$('#repeat_every').attr('disabled','disabled');
			$('#repeat_to').attr('disabled','disabled');
		}
	}
	
	function checkRegexp( o, regexp, n ) 
	{
		if ( !( regexp.test( o.val() ) ) ) 
		{
			o.addClass( "ui-state-error" );
			update_tips( n );
			return false;
		} 
		else 
		{
			return true;
		}
	}
	
	function set_ajax_content(data_url, data_params, callback)
	{
		$.ajax({
			type : 'POST',
			url : data_url,
			data: data_params,
			success: function(data)
			{
				callback();
			}
		});
	}
	
	function create_event()
	{
		data_url = "tools/modify_event.php";
		if ($('#repeat_tog').is(':checked'))
		{
			data_params = "type=create&id=-1&guests=" + JSON.stringify(event_guests) + "&title=" + event_title.val() + "&from=" + event_from.val() + "&to=" + event_to.val() + "&repeat_interval=" + event_repeat_interval.val() + "&repeat_every=" + event_repeat_every.val() + "&event_repeat_to=" + event_repeat_to.val();
		}
		else
		{
			data_params = "type=create&id=-1&guests=" + JSON.stringify(event_guests) + "&title=" + event_title.val() + "&from=" + event_from.val() + "&to=" + event_to.val();
		}
		set_ajax_content(data_url, data_params, function() { $( "#dialog-event-form" ).dialog( "close" ); })
		window.setTimeout(function() { $('#calendar').fullCalendar( 'refetchEvents' ); },1000);
	}
	
	window.edit_event = function edit_event(id)
	{
		alert('Edit not implemented yet.');
	}
	
	window.remove_event = function remove_event(id, repeat_id, delete_type)
	{
		data_url = "tools/modify_event.php";
		data_params = "type=delete&id=" + id + "&repeat_id=" + repeat_id + '&delete_type=' + delete_type;
		set_ajax_content(data_url, data_params, function() { $( "#dialog-event_content" ).dialog( "close" ); })
		window.setTimeout(function() { $('#calendar').fullCalendar( 'refetchEvents' ); },1000);
	}
	
	window.create_user = function create_user()
	{
		var valid = true;
		allFields.removeClass( "ui-state-error" );
		
		valid = valid && checkRegexp( email, emailRegex, "eg. test@test.com" );

		if ( valid ) 
		{
			data_url = "tools/modify_user.php";
			data_params = "type=create&id=-1&fname=" + fname.val() + "&lname=" + lname.val() + "&phone=" + phone.val() + "&role=" + role.val() + "&password=" + password.val() + "&email=" + email.val();
			set_ajax_content(data_url, data_params, function() { $( "#dialog-form" ).dialog( "close" ); })
		}
		return valid;
	}
	
	window.delete_user = function delete_user(user_id)
	{
		data_url = "tools/modify_user.php";
		data_params = "type=delete&id=" + user_id;
		set_ajax_content(data_url, data_params, function() { $('#dialog-confirm').dialog( 'close' ); })
		return true;
	}
	
	window.edit_user = function edit_user(user_id)
	{
		var valid = true;
		allFields.removeClass( "ui-state-error" );
		
		valid = valid && checkRegexp( email, emailRegex, "eg. test@test.com" );

		if ( valid ) 
		{
			data_url = "tools/modify_user.php";
			data_params = "type=edit&id=" + user_id + "&fname=" + fname.val() + "&lname=" + lname.val() + "&phone=" + phone.val() + "&role=" + role.val() + "&password=" + password.val() + "&email=" + email.val();
			set_ajax_content(data_url, data_params, function() { $( "#dialog-form" ).dialog( "close" ); })
		}
		return valid;
	}
	
	function list_users()
	{
		$('#dialog-users').dialog({
			width: 550,
			modal: true,
			open: function(event, ui) {
				$(this).load("tools/display_users.php");
			}
		});
	}
	
	function logout()
	{
		set_ajax_content("logout.php", "", function() { location.reload(); });
	}	
	
	dialog = $( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 450,
		width: 350,
		modal: true,
		close: function() {
			dialog.find( "form" )[0].reset();
			allFields.removeClass( "ui-state-error" );
			update_tips( "All form fields are required." );
		}
	});
	
	dialog_event = $( "#dialog-event-form" ).dialog({
		autoOpen: false,
		height: 500,
		width: 415,
		modal: true,
		buttons: {
			"Add Event": function() {
				create_event();
			},
			Cancel: function() {
				dialog_event.dialog( "close" );
			}
		},
		close: function() {
			dialog_event.find( "form" )[0].reset();
			$('#repeat_options').attr('hidden','hidden');
			$('#repeat_interval').attr('disabled','disabled');
			$('#repeat_every').attr('disabled','disabled');
			$('#repeat_to').attr('disabled','disabled');
			$( "#repeat_every_quantifier").html("Days");
			event_guests = [];
			$( "#event_guest_list" ).html('');
			$('#event_from').val(init_datetime);
			$('#event_to').val(init_datetime);
			$('#repeat_to').val(init_datetime);
		}
	});
	
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		height: "auto",
		width: 400,
		modal: true
	});
		 
	$( "#list_users" ).on( "click", function() {
		list_users();
	});
			
	$( "#create_event" ).on( "click", function() {
		$('#dialog-event-form').dialog( 'open' );
	});
	
	$( "#logout" ).on( "click", function() {
		logout();
	});

	$( "#repeat_tog" ).on( "click", function() {
		event_repeat();
	});
	
	$( "#repeat_interval" ).on( "change", function() {
		var interval_val = $( "#repeat_interval" ).val();
		var string = 'Days';
		if (interval_val == 'weekly') {
			string = 'Weeks';
		}	
		else if (interval_val == 'monthly') {
			string = 'Months';
		}
		else if (interval_val == 'yearly') {
			string = 'Years';
		}
		$( "#repeat_every_quantifier").html(string);
	});
	
	$('.datepicker').datetimepicker({
		timeFormat: 'hh:mm:ss',
		dateFormat: 'yy/mm/dd'
	});
	
	$( "#event_guests" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "tools/user_list.php",
				dataType: "jsonp",
				data: {
					term: request.term
				},
				success: function( data ) {
					searchRequest = null;
					response($.map(data, function(item) {
						return {
							id: item.schedulr_users_id,
							value: item.first_name + " " + item.last_name,
							label: item.first_name + " " + item.last_name
						};
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			if ($.inArray(ui.item.id, event_guests) == -1)
			{
				event_guests.push(ui.item.id);
			}
			$( "#event_guests" ).val('');
			$( "#event_guest_list" ).append("<div>" + ui.item.value + "</div>");
		}
	});
	
	$("#event-edit").button().on( 'click', function() {
		event.preventDefault();
		window.edit_event($(this).data('id'));
	});
	
	$("#event-remove").button().on( 'click', function() {
		event.preventDefault();
		window.remove_event($(this).data('id'), -1, $(this).data('deleteType'));
	});
	
	$("#event-remove-all").button().on( 'click', function() {
		event.preventDefault();
		window.remove_event($(this).data('id'), $(this).data('repeat_id'), $(this).data('deleteType'));
	});
	
});