<?php include('header.php'); include('db.php'); ?>

<main class="form-container">
    <h2 class="title">Forgot Password</h2>
    <form method="POST" class="auth-form">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <button type="submit" name="reset" class="btn-primary full-width">Send Reset Link</button>

        <p class="link-text"><a href="login.php">Back to Login</a></p>
    </form>

    <?php
    if (isset($_POST['reset'])) {
        $email = $_POST['email'];
        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($result->num_rows > 0) {
            echo "<p class='success'>A password reset link has been sent to your email (simulation).</p>";
        } else {
            echo "<p class='error'>Email not found!</p>";
        }
    }
    ?>
</main>

<?php include('footer.php'); ?>
