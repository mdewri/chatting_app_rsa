<?php
include 'config.php';
if ($_POST['sender_id'] && $_POST['receiver_id'] && $_POST['encrypted_msg'] && $_POST['sender_pub_key']) {
    $msg_self = $_POST['encrypted_msg_self'] ?? '';
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, encrypted_msg, encrypted_msg_self, sender_pub_key) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $_POST['sender_id'], $_POST['receiver_id'], $_POST['encrypted_msg'], $msg_self, $_POST['sender_pub_key']);
    $stmt->execute();
    echo json_encode(['success' => true]);
}
?>