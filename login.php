<?php
include 'config.php';
$username = $_POST['username'];
$stmt = $conn->prepare("SELECT id, public_key FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'user_id' => $row['id'], 'public_key' => $row['public_key']]);
} else {
    echo json_encode(['success' => false]);
}
?>