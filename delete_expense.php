<?php
include 'db_config.php';

$id = $_GET['id'] ?? 0;

if ($id && is_numeric($id)) {
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();
?>
