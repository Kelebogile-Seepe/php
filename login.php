<?php
// Enable error reporting for debugging (remove on production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Include header and database connection
include('header.php');
include('db.php');  // Make sure this path is correct

?>

<main class="form-container">
    <h2 class="title">Login</h2>
    <form method="POST" class="auth-form">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login" class="btn-primary full-width">Login</button>

        <p class="link-text"><a href="forgot_password.php">Forgot Password?</a></p>
        <p class="link-text">Don't have an account? <a href="register.php">Register</a></p>
    </form>

    <?php
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        try {
            // Prepare statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify password hash
                if (password_verify($password, $user['password'])) {
                    // Store user data in session and redirect
                    $_SESSION['user'] = $user['name'];
                    header("Location: report_disaster.php");
                    exit();
                } else {
                    echo "<p class='error'>Incorrect password!</p>";
                }
            } else {
                echo "<p class='error'>Email not found!</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    ?>
</main>

<?php include('footer.php'); ?>
