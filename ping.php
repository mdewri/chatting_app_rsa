<?php
include 'config.php';
$user_id = $_GET['user_id'] ?? 0;
if ($user_id) {
    $stmt = $conn->prepare("UPDATE users SET last_seen = NOW() WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}
?>
