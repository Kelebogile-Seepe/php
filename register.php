<?php include('header.php'); include('db.php'); ?>

<main class="form-container">
    <h2 class="title">Create an Account</h2>
    <form method="POST" class="auth-form">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="register" class="btn-primary full-width">Register</button>

        <p class="link-text">Already have an account? <a href="login.php">Login</a></p>
    </form>

    <?php
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            echo "<p class='error'>Email already registered!</p>";
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($sql)) {
                echo "<p class='success'>Registration successful! <a href='login.php'>Login here</a>.</p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
    }
    ?>
</main>

<?php include('footer.php'); ?>
