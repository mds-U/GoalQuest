<?php

require_once('../db_con.php');

$id = $_REQUEST['id'];

// Validate the ID
if (!isset($id) || !is_numeric($id)) {
    header('Location: ../index.php');
    exit();
}

$query = "INSERT INTO archive_list (id, user_id, name, status, start_date, end_date, description, created_at)
          SELECT id, user_id, name, status, start_date, end_date, description, created_at FROM project_list WHERE id=?";

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        
        $query = "DELETE FROM project_list WHERE id=?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header('Location: ../archive_tbl.php?page=home&success=1');
            } else {
                header('Location: ../project_list.php?page=home&error=1');
            }
            
            $stmt->close();
        } else {
            header('Location: ../project_list.php?page=home&error=1');
        }
    } else {
        header('Location: ../project_list.php?page=home&error=1');
    }
} else {
    header('Location: ../project_list.php?page=home&error=1');
}

$conn->close();
?>