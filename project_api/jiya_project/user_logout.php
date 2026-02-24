<?php
session_start();

// session destroy
session_unset();
session_destroy();

echo json_encode([
    "status" => 'success',
    "message" => "Logout Successful"
]);
?>
