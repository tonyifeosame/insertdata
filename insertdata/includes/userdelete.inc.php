<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $pwd = $_POST['pwd'];

    try {
        require_once 'dbh.inc.php';

        // First, retrieve the user's hashed password from the database
        $query = 'SELECT pwd FROM users WHERE username = :username';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Verify the input password with the hashed password from the database
            if (password_verify($pwd, $result['pwd'])) {
                // Password is correct, proceed to delete the user
                $deleteQuery = 'DELETE FROM users WHERE username = :username';
                $deleteStmt = $pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(':username', $username);
                $deleteStmt->execute();

                // Redirect the user after successful deletion
                header("Location: ../index.php");
                exit();
            } else {
                die('Invalid password.');
            }
        } else {
            die('User not found.');
        }

    } catch (PDOException $e) {
        die('Query failed: ' . $e->getMessage());
    }

} else {
    header("Location: ../index.php");
    exit();
}
