<?php 
	include_once("header.php"); 
		
	$admin_string = "false";
	if($is_admin)
	{
		$admin_string = "true";
	}
?>

<html>
<head>
	<meta charset='utf-8' />
	<title>Schedulr</title>
	<link href='css/fullcalendar.min.css' rel='stylesheet' />
	<link href='css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
	<link href='css/scheduler.min.css' rel='stylesheet' />
	<link href='css/jquery-ui.css' rel='stylesheet' />
	<script src='js/moment.min.js'></script>
	<script src='js/jquery.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	<script src='js/scheduler.min.js'></script>
	<script src='js/jquery-ui.js'></script>
	<script src="js/jquery-ui-timepicker-addon.js"></script>
	<script src="js/main.js"></script>
	<script>
		$(function() { // document ready
			$('#calendar').fullCalendar({
				eventSources: {
					url: 'tools/resources.php'
				},
				schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
				editable: <?= $admin_string ?>, // draggable events
				aspectRatio: 1.8,
				scrollTime: '00:00', // undo default 6am scrollTime
				header: {
					left: 'today prev,next',
					center: 'title',
					right: 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek'
				},
				defaultView: 'month',
				views: {
					timelineThreeDays: {
						type: 'timeline',
						duration: { days: 3 }
					}
				},
				eventRender: function (event, element) {
					element.attr('href', 'javascript:void(0);');
					element.click(function() {
						$("#event-startTime").html(moment(event.start).format('MMM Do h:mm A'));
						$("#event-endTime").html(moment(event.end).format('MMM Do h:mm A'));
						$("#event-guests").html(function() {
							var output = "";
							$.each( event.guests, function( key, value ) {
								output = output + "<div>" + value['first_name'] + " " + value['last_name'] + "</div>";
							});
							return output;
						});
						$("#event-edit").data( "id", event.id );
						var tmp_repeat_id = -1;
						if (event.resourceId > 0) { tmp_repeat_id = event.resourceId }
						$("#event-remove").data( "deleteType", 'single' );
						$("#event-remove").data( "id", event.id );
						$("#event-remove").data( "repeat_id", tmp_repeat_id );
						$("#event-remove-all").data( "deleteType", 'all' );
						$("#event-remove-all").data( "id", event.id );
						$("#event-remove-all").data( "repeat_id", tmp_repeat_id );
						$("#dialog-event_content").dialog({title: event.title});
						$("#dialog-event_content").dialog( "open" );
					});
				}
			});
		});
	</script>
</head>
<body>
	<div id='calendar'></div>
	<div id='dialog-confirm'><span id='confirm_text'></span></div>
	<div id='dialog-users'></div>
	<div id='dialog-event_content'>
		<div class='table'>
			<div class='row'><div class='cell'>Start:</div><div class='cell'><div id='event-startTime'></div></div></div>
			<div class='row'><div class='cell'>End:</div><div class='cell'><div id='event-endTime'></div></div></div>
			<div class='row'><div class='cell'>Guests:</div><div class='cell'><div id='event-guests'></div></div></div>
			<div class='row'><div class='cell'><a id="event-edit" href="" target="_blank">Edit</a></div><div class='cell'><a id="event-remove" href="" target="_blank">Remove</a><a id="event-remove-all" href="" target="_blank">Remove Series</a></div></div>
		</div>
	</div>
	<div id="dialog-form" title="Create New User">
		<p class="validateTips">All form fields are required.</p>
		<form>
			<label class='user_form_ele' for="fname">First Name</label>
			<input type="text" name="fname" id="fname" class="text ui-widget-content ui-corner-all user_form_ele">
			<label class='user_form_ele' for="lname">Last Name</label>
			<input type="text" name="lname" id="lname" class="text ui-widget-content ui-corner-all user_form_ele">
			<label class='user_form_ele' for="email">Email</label>
			<input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all user_form_ele">
			<label class='user_form_ele' for="password">Password</label>
			<input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all user_form_ele">
			<label class='user_form_ele' for="phone">Phone</label>
			<input type="text" name="phone" id="phone" class="text ui-widget-content ui-corner-all user_form_ele">
			<label class='user_form_ele' for="role">Role</label>
			<select name='role' id='role' class="ui-widget-content ui-corner-all user_form_ele">
				<option value='user'>User</option>
				<option value='admin'>Admin</option>
			</select>

			<!-- Allow form submission with keyboard without duplicating the dialog button -->
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</form>
	</div>

	<div id="dialog-event-form" title="Create Event">
		<form>
			<label class='user_form_ele' for="event_title">Title</label>
			<input type="text" name="event_title" id="event_title" class="text ui-widget-content ui-corner-all user_form_ele">
			<label class='user_form_ele' for="event_from"></label>
			<input type="text" name="event_from" id="event_from" class="text ui-widget-content ui-corner-all datepicker">
			<span> to </span>
			<input type="text" name="event_to" id="event_to" class="text ui-widget-content ui-corner-all datepicker">
			<input type="checkbox" id="repeat_tog" name="repeat_tog" value="yes"/>Repeat...
			<div id='repeat_options' style='border: 1px solid lightgrey; padding-left: 5px; padding-bottom: 5px;' hidden>
				<label class='user_form_ele' for="repeat_interval">Repeat</label>
				<select name="repeat_interval" id="repeat_interval" disabled >
					<option value="daily">Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
				</select>
				<label class='user_form_ele' for="repeat_every">Every</label>
				<select name="repeat_every" id="repeat_every" disabled >
					<?php
						for($i=1; $i <= 30; $i++)
						{
							echo "<option value='$i'>$i</option>";
						}
					?>
				</select>
				<span id='repeat_every_quantifier'>Days</span>
				<label class='user_form_ele' for="repeat_to">Repeat End</label>
				<input type="text" name="repeat_to" id="repeat_to" class="text ui-widget-content ui-corner-all datepicker">
			</div>
			<label class='user_form_ele' for="event_guests">Guests</label>
			<input type="text" name="event_guests" id="event_guests" class="text ui-widget-content ui-corner-all user_form_ele">
			<div id='event_guest_list'></div>

			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</form>
	</div>

</body>
</html>

<?php include_once("footer.php"); ?>