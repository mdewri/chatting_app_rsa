<?php
include 'config.php';
$result = $conn->query("SELECT id, username, public_key FROM users WHERE last_seen >= NOW() - INTERVAL 15 SECOND");
$users = [];
while ($row = $result->fetch_assoc()) $users[] = $row;
echo json_encode($users);
?>