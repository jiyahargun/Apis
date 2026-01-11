
<?php

include('connect.php');

$id = $_POST['id'];

$Sql_Query = "DELETE FROM sellers WHERE id = '$id'";

if (mysqli_query($con, $Sql_Query)) {
    echo 'Record Deleted Successfully';
} else {
    echo 'record delete failed';
}

mysqli_close($con);
?>
