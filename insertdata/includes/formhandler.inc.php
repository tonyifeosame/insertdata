<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $pwd = $_POST['pwd'];
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address');
    }

    try {
        require_once 'dbh.inc.php';

        // Hash the password before storing it
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        // Prepare the query
        $query = 'INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email);';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':pwd', $pwd);
        $stmt->bindParam(':email', $email);
        $stmt->execute(); // No parameters passed to execute() because they were bound
        


        

        // Redirect the user after a successful insertion
        header("Location: ../index.php");
        exit();

    } catch (PDOException $e) {
        die('Query failed: ' . $e->getMessage());
    }

} else {
    header("Location: ../index.php");
    exit();
}
