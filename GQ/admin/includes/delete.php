<?php

require_once('../db_con.php');

$id = $_REQUEST['id'];

$query = "DELETE FROM archive_list WHERE id = ?";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully.');</script>";
} else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
}


$stmt->close();
$conn->close();

?>