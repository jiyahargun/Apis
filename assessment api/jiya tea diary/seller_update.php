
<?php

 include('connect.php');

 $id = $_POST['id'];
 $name = $_POST['seller_name'];
 $contact = $_POST['contact'];
 $address = $_POST['address'];


$Sql_Query = "UPDATE sellers SET seller_name='$name' ,contact='$contact',address='$address' WHERE id = '$id'";

 if(mysqli_query($con,$Sql_Query))
{
 echo 'Record Updated Successfully';
}
else
{
 echo 'record update fail';
 }

 mysqli_close($con);
?>
