<?php

function get_result( $Statement ) {
    $RESULT = array();
    $Statement->store_result();
    for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ( $Field = $Metadata->fetch_field() ) {
            $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
        $Statement->fetch();
    }
    return $RESULT;
}

include_once("../header_code.php");
$conn = new mysqli(constant("global_mysql_server"), constant("global_mysql_user"), constant("global_mysql_password"), constant("global_mysql_database"));

$term = "%" . $conn->real_escape_string($_GET["term"]) . "%";

$statement = $conn->prepare("select schedulr_users_id, email, first_name, last_name, phone from schedulr_users where LOWER(first_name) LIKE LOWER(?) OR LOWER(last_name) LIKE LOWER(?) OR LOWER(email) LIKE LOWER(?)");
$statement->bind_param("sss", $term, $term, $term);
$statement->execute();
$result = get_result( $statement );  // this line and the function above is a workaround for older php versions.  The proper -- $statement->get_result(); -- statement will work if you are using PHP 5.4 or higher

$data = array();
//while ($row = $result->fetch_array())    //same as above
while ($row = array_shift( $result ))
{
	$data[] = $row;
}
$conn->close();

header('content-type: application/json; charset=utf-8');
echo $_GET['callback'] . '('.json_encode($data).')';

?>