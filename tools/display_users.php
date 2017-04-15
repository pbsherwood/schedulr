<?php

include_once("../header_code.php");
$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));

$result = $conn->query("select * from schedulr_users");

echo "<div style='display: table;'>";

	if ($is_admin)
	{
	echo "
		<div style='display: table-header-group; font-weight: bold;'>
			<div style='display: table-cell; padding:3px;'>
				<span id='create_user' >Add</span>
			</div>
		</div>";
	}
	echo "
		<div style='display: table-header-group; font-weight: bold;'>
			<div style='display: table-cell; padding:3px;'>
				Name
			</div>
			<div style='display: table-cell; padding:3px;'>
				Email
			</div>
			<div style='display: table-cell; padding:3px;'>
				Phone
			</div>
			<div style='display: table-cell; padding:3px;'>
				Role
			</div>
			<div style='display: table-cell; padding:3px;'>
				Functions
			</div>
		</div>";
		
while($row = $result->fetch_assoc()) 
{
	echo "<div style='display: table-row'>
		<div style='display: table-cell; padding:3px;'>
			" . $row["first_name"]. " " . $row["last_name"] . "
		</div>
		<div style='display: table-cell; padding:3px;'>
			" . $row["email"] . "
		</div>
		<div style='display: table-cell; padding:3px;'>
			" . $row["phone"] . "
		</div>
		<div style='display: table-cell; padding:3px;'>
			" . $row["user_roles"] . "
		</div>";
	if ($is_admin || (trim($row["schedulr_users_id"]) == trim($_SESSION['user_prefs']['id'])))
	{
	echo "
		<div style='display: table-cell; padding:3px;'>
			<span class='edit_user' data-user_id='" . $row["schedulr_users_id"] . "' data-email='" . $row["email"] . "' data-first_name='" . $row["first_name"] . "' data-last_name='" . $row["last_name"] . "' data-phone='" . $row["phone"] . "' data-user_roles='" . $row["user_roles"] . "'>Edit</span>
		</div>";
	}
	if ($is_admin)
	{
	echo "
		<div style='display: table-cell; padding:3px;'>
			<span class='delete_user' data-user_id='" . $row["schedulr_users_id"] . "'>Delete</span>
		</div>";
	}
	echo "</div>";
}

echo "</div>

	<script>
		$( '#create_user' ).button().on( 'click', function() {
			form = $('#dialog-form').find( 'form' ).on( 'submit', function( event ) {
				event.preventDefault();
				create_user();
			});
			
			$('#dialog-form').dialog('option', 'title', 'Add User');
			$('#dialog-form').dialog({
				buttons: {
					'Add User': function() {
						window.create_user();
					},
					Cancel: function() {
						dialog.dialog( 'close' );
					}
				}
			});
			
			$( '#email' ).prop('disabled', false);
			$( '#password' ).prop('disabled', false);
			
			$('#dialog-users').dialog( 'close' );
			$('#dialog-form').dialog( 'open' );
		});
		
		
		$( '.delete_user' ).button().on( 'click', function() {
			delete_button = $(this);
			$('#confirm_text').html('Are you sure you want to delete this user?');
			$('#dialog-confirm').dialog('option', 'title', 'Delete User');
			$('#dialog-confirm').dialog({
				buttons: {
					'Delete User': function() {
						window.delete_user(delete_button.data('user_id'));
					},
					Cancel: function() {
						$('#dialog-confirm').dialog( 'close' );
					}
				}
			});
			
			$('#dialog-users').dialog( 'close' );
			$('#dialog-confirm').dialog( 'open' );
		});
	
		$( '.edit_user' ).button().on( 'click', function() {
			edit_button = $(this);
			form = $('#dialog-form').find( 'form' ).on( 'submit', function( event ) {
				event.preventDefault();
				edit_user(edit_button.data('user_id'));
			});
			
			$('#dialog-form').dialog('option', 'title', 'Edit User');
			$('#dialog-form').dialog({
				
				buttons: {
					'Edit User': function() {
						window.edit_user(edit_button.data('user_id'));
					},
					Cancel: function() {
						dialog.dialog( 'close' );
					}
				}
			});
			
			var temp_fname = edit_button.data('first_name'),
			temp_lname = edit_button.data('last_name'),
			temp_phone = edit_button.data('phone'),
			temp_role = edit_button.data('user_roles'),
			temp_email = edit_button.data('email'),
			temp_password = '0000000000';
			
			$( '#fname' ).val( temp_fname );
			$( '#lname' ).val( temp_lname );
			$( '#email' ).val( temp_email );
			$( '#password' ).val( temp_password );
			$( '#phone' ).val( temp_phone );
			$( '#role' ).val( temp_role );

			$( '#email' ).prop('disabled', true);
			$( '#password' ).prop('disabled', true);

			$('#dialog-users').dialog( 'close' );
			$('#dialog-form').dialog( 'open' );
		});
	</script>
";

$conn->close();
?>