<?php include('header.php'); include('db.php'); ?>

<main class="form-container">
    <h2 class="title">Support Our Cause: Make a Donation</h2>
    <p class="subtitle">Your contribution directly helps communities affected by disasters.</p>

    <form class="donation-form" method="POST">
        <div class="donation-options">
            <button type="button" onclick="setAmount(25)" class="btn-active">$25</button>
            <button type="button" onclick="setAmount(50)" class="btn-active">$50</button>
            <button type="button" onclick="setAmount(100)" class="btn-active">$100</button>
            <input type="text" id="amount" name="amount" placeholder="Custom Amount" required>
        </div>

        <h3>Your Information (Optional)</h3>
        <input type="text" name="fullname" placeholder="Full Name">
        <input type="email" name="email" placeholder="Email Address">

        <div class="radio-group">
            <label><input type="radio" name="anonymity" value="public" checked> Display my name publicly</label>
            <label><input type="radio" name="anonymity" value="anonymous"> Donate anonymously</label>
        </div>

        <h3>Payment Information</h3>
        <input type="text" name="card_number" placeholder="Card Number" required>
        <div class="row">
            <input type="text" placeholder="MM/YY">
            <input type="text" placeholder="CVC">
        </div>

        <div class="checkbox">
            <input type="checkbox" id="save">
            <label for="save">Save payment details for future donations</label>
        </div>

        <button type="submit" name="donate" class="btn-primary full-width">Donate Now</button>
    </form>

    <?php
    if (isset($_POST['donate'])) {
        $amount = $_POST['amount'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $anonymity = $_POST['anonymity'];
        $card = $_POST['card_number'];
        $card_last4 = substr($card, -4);

        $sql = "INSERT INTO donations (amount, fullname, email, anonymity, card_last4)
                VALUES ('$amount', '$fullname', '$email', '$anonymity', '$card_last4')";
        if ($conn->query($sql)) {
            echo "<p class='success'>Thank you for your donation of $$amount!</p>";
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
    }
    ?>
</main>

<script>
function setAmount(value) {
  document.getElementById("amount").value = value;
}
</script>

<?php include('footer.php'); ?>
