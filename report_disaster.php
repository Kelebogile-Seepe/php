<?php include('header.php'); include('db.php'); ?>

<main class="form-container">
    <h2 class="title">Report a Disaster Event</h2>
    <p class="subtitle">Please fill out the form below with details about the disaster.</p>

    <form class="report-form" method="POST" enctype="multipart/form-data">
        <label>Disaster Type</label>
        <select name="disaster_type" required>
            <option value="">Select a disaster type</option>
            <option>Flood</option>
            <option>Fire</option>
            <option>Earthquake</option>
            <option>Tornado</option>
        </select>

        <label>Location</label>
        <input type="text" name="location" required>

        <div class="row">
            <div>
                <label>Date of Event</label>
                <input type="date" name="event_date" required>
            </div>
            <div>
                <label>Time of Event</label>
                <input type="time" name="event_time" required>
            </div>
        </div>

        <label>Detailed Description</label>
        <textarea name="description" required></textarea>

        <label>Urgency Level</label>
        <select name="urgency" required>
            <option value="">Select urgency level</option>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
        </select>

        <label>Upload Supporting Image (Optional)</label>
        <input type="file" name="image">

        <div class="checkbox">
            <input type="checkbox" name="confirm" required>
            <label>I confirm that the information provided is accurate.</label>
        </div>

        <button type="submit" name="submit" class="btn-primary full-width">Submit Report</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $disaster_type = $_POST['disaster_type'];
        $location = $_POST['location'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $description = $_POST['description'];
        $urgency = $_POST['urgency'];
        
        $imageName = "";
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . "_" . basename($_FILES['image']['name']);
            $target = "uploads/" . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }

        $sql = "INSERT INTO disaster_reports (disaster_type, location, event_date, event_time, description, urgency, image)
                VALUES ('$disaster_type', '$location', '$event_date', '$event_time', '$description', '$urgency', '$imageName')";

        if ($conn->query($sql)) {
            echo "<p class='success'>Disaster report submitted successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $conn->error . "</p>";
        }
    }
    ?>
</main>

<?php include('footer.php'); ?>
