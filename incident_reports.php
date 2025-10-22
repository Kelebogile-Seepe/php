<?php include('header.php'); include('db.php'); ?>

<main class="reports-container">
    <h2 class="title">Incident Reports</h2>
    <input type="text" id="searchInput" class="search-bar" placeholder="Search incidents by location, type, or urgency...">

    <h3 class="subtitle">All Incident Reports</h3>

    <table class="reports-table" id="reportsTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Location</th>
                <th>Urgency</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM disaster_reports ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    echo "<tr>
                        <td>{$row['event_date']}</td>
                        <td>{$row['disaster_type']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['urgency']}</td>
                        <td><button class='btn-view' onclick='openModal($jsonData)'>View</button></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No reports yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<!-- Modal -->
<div id="reportModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2 id="modalTitle">Disaster Report Details</h2>

    <p><strong>Date:</strong> <span id="modalDate"></span></p>
    <p><strong>Time:</strong> <span id="modalTime"></span></p>
    <p><strong>Type:</strong> <span id="modalType"></span></p>
    <p><strong>Location:</strong> <span id="modalLocation"></span></p>
    <p><strong>Urgency:</strong> <span id="modalUrgency"></span></p>
    <p><strong>Description:</strong></p>
    <p id="modalDescription" class="description-box"></p>

    <div id="modalImageContainer" style="margin-top: 15px;">
        <img id="modalImage" src="" alt="Disaster image" style="max-width: 100%; border-radius: 8px; display: none;">
    </div>
  </div>
</div>

<script>
// 🔍 Simple table search
document.getElementById("searchInput").addEventListener("keyup", function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#reportsTable tbody tr");
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

// 🧩 Modal functionality
function openModal(data) {
    document.getElementById("modalTitle").innerText = data.disaster_type + " Report";
    document.getElementById("modalDate").innerText = data.event_date;
    document.getElementById("modalTime").innerText = data.event_time;
    document.getElementById("modalType").innerText = data.disaster_type;
    document.getElementById("modalLocation").innerText = data.location;
    document.getElementById("modalUrgency").innerText = data.urgency;
    document.getElementById("modalDescription").innerText = data.description;

    if (data.image) {
        document.getElementById("modalImage").src = "uploads/" + data.image;
        document.getElementById("modalImage").style.display = "block";
    } else {
        document.getElementById("modalImage").style.display = "none";
    }

    document.getElementById("reportModal").style.display = "block";
}

function closeModal() {
    document.getElementById("reportModal").style.display = "none";
}
window.onclick = function(event) {
    if (event.target == document.getElementById("reportModal")) {
        closeModal();
    }
}
</script>

<style>
/* Table styling */
.reports-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}
.reports-table th, .reports-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
.reports-table th {
    background-color: #0e0077;
    color: white;
}
.btn-view {
    background-color: #0e0077;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.btn-view:hover {
    background-color: #3326b8;
}

/* Modal styles */
.modal {
  display: none;
  position: fixed;
  z-index: 100;
  padding-top: 80px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.6);
}

.modal-content {
  background-color: #fff;
  margin: auto;
  padding: 20px;
  border-radius: 10px;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

.close {
  color: #aaa;
  float: right;
  font-size: 26px;
  font-weight: bold;
  cursor: pointer;
}
.close:hover {
  color: #000;
}

.description-box {
  background: #f8f9fa;
  padding: 10px;
  border-radius: 6px;
  min-height: 60px;
}
</style>

<?php include('footer.php'); ?>
