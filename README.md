# Chattr - Secure End-to-End Encrypted Messaging

Chattr is a real-time, browser-based chat application that implements robust **End-to-End Encryption (E2EE)** using the browser's native Web Crypto API.

With Chattr, the server never sees your plaintext messages. All messages are mathematically encrypted on your device using 2048-bit RSA keys before they are transmitted, ensuring only the intended recipient can read them.

## 🌟 Features

* **Military-Grade E2EE**: Utilizes `window.crypto.subtle` for 2048-bit RSA-OAEP encryption, making it mathematically secure against interception.
* **Double-Encryption Architecture**: Sent messages are encrypted twice (once with the recipient's public key, once with the sender's public key) so you can securely read your own chat history without compromising the recipient's security.
* **Real-Time Presence Tracking**: A background heartbeat loop (`ping.php`) continuously monitors user connections. Users who disconnect or close their tab automatically vanish from the online list within 15 seconds.
* **Beautiful, Modern UI**: Features a sleek dark mode, gradient typography, floating cards, and smooth CSS animations.
* **Zero Dependencies**: Pure Vanilla HTML/JS/CSS frontend and lightweight PHP backend. No `npm install` or massive frameworks required.

## 🛠️ Tech Stack

* **Frontend**: Vanilla JavaScript (Web Crypto API), HTML5, CSS3
* **Backend**: PHP 8+
* **Database**: MySQL / MariaDB

## 🚀 Getting Started (Local Setup)

This project is built to run easily on a local server environment like [XAMPP](https://www.apachefriends.org/index.html) or MAMP.

### 1. Clone the Repository
Clone this project directly into your `htdocs` (or equivalent `www`) directory:
```bash
cd htdocs
git clone https://github.com/your-username/rsa_chat.git
```

### 2. Database Setup
Create a new MySQL database named `rsa_chat` (e.g., using phpMyAdmin at `http://localhost/phpmyadmin`).

Run the following SQL commands to build the necessary tables:

```sql
-- Create the users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `public_key` text NOT NULL,
  `last_seen` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the messages table
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `encrypted_msg` text NOT NULL,
  `sender_pub_key` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `encrypted_msg_self` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. Configuration
If your MySQL setup uses a different username/password than the default XAMPP credentials (`root` with no password), update the `config.php` file:

```php
<?php
$conn = new mysqli('localhost', 'root', 'your_password_here', 'rsa_chat');
// ...
?>
```

### 4. Run the App!
Navigate to `http://localhost/rsa_chat/index.html` in your browser. Open a second incognito window to simulate chatting between two separate users!

## 🔐 How the Cryptography Works

1. **Registration**: When a user logs in, their browser generates a unique 2048-bit RSA key pair. The private key never leaves the browser's memory. The public key is exported as a JWK (JSON Web Key) and sent to the server.
2. **Sending a Message**: 
   * The sender's browser downloads the recipient's JWK public key.
   * The plaintext message is converted to an ArrayBuffer and encrypted.
   * The resulting ciphertext is encoded into Base64 and sent to the server.
3. **Receiving a Message**:
   * The recipient's browser downloads the Base64 ciphertext.
   * It uses the private key securely stored in memory to decrypt the ciphertext back into readable text.

