<?php
include 'config.php';
if (isset($_POST['username']) && isset($_POST['public_key'])) {
    $username = $_POST['username'];
    $public_key = $_POST['public_key'];
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // User exists, update public key since we generated a new one
        $user_id = $row['id'];
        $update = $conn->prepare("UPDATE users SET public_key = ? WHERE id = ?");
        $update->bind_param("si", $public_key, $user_id);
        $update->execute();
        
        echo json_encode(['success' => true, 'user_id' => $user_id]);
    } else {
        // Register new user
        $insert = $conn->prepare("INSERT INTO users (username, public_key) VALUES (?, ?)");
        $insert->bind_param("ss", $username, $public_key);
        if ($insert->execute()) {
            echo json_encode(['success' => true, 'user_id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $insert->error]);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
}
?>