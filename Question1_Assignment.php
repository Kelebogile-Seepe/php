<?php
/* ====== CONFIG ====== */
$capacity       = 60000;
$prices         = ["VVIP" => 3000, "VIP" => 2000, "General" => 500];

$salesFile      = __DIR__ . "/sales.txt";

/* ====== LOAD CURRENT SALES ====== */
$sales = [];
if (file_exists($salesFile)) {
    $sales = json_decode(file_get_contents($salesFile), true);
} else {
    $sales = []; // empty if first run
}

/* ====== HANDLE FORM SUBMISSION ====== */
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name      = trim($_POST['name']);
    $gender    = $_POST['gender'];
    $age       = (int)$_POST['age'];
    $category  = $_POST['category'];

    // --- 1. Age restriction ---
    if ($age < 16) {
        $message = "❌ Sorry, buyers must be at least 16 years old.";
    }
    // --- 2. Capacity check ---
    elseif (count($sales) >= $capacity) {
        $message = "❌ Tickets are sold out.";
    }
    else {
        // --- 3. Seat assignment ---
        $seatNumber = count($sales) + 1;   // simple sequential seat numbering

        // Save the sale
        $sales[] = [
            "name"     => $name,
            "gender"   => $gender,
            "age"      => $age,
            "category" => $category,
            "seat"     => $seatNumber,
            "price"    => $prices[$category]
        ];
        file_put_contents($salesFile, json_encode($sales));
        $message = "✅ Ticket booked! Seat #{$seatNumber}, Price R{$prices[$category]}";
    }
}

/* ====== BUILD SUMMARY TABLE ====== */
function ageGroup($age) {
    if ($age >= 16 && $age <= 21) return "16-21";
    if ($age >= 22 && $age <= 35) return "22-35";
    return "36+";
}
$summary = [];
foreach ($sales as $s) {
    $key = $s['gender']."|".ageGroup($s['age']);
    $summary[$key] = ($summary[$key] ?? 0) + 1;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Beyoncé Concert Booking – 25 Dec 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-4xl mx-auto bg-white p-6 mt-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-2">Beyoncé Concert Ticket Booking</h1>
    <p class="mb-6 text-gray-700"><strong>Date:</strong> 25 December 2025</p>

    <form method="post" class="space-y-4">
        <div>
            <label class="block font-semibold">Name:</label>
            <input type="text" name="name" required class="border rounded p-2 w-full">
        </div>

        <div>
            <label class="block font-semibold">Gender:</label>
            <select name="gender" required class="border rounded p-2 w-full">
                <option value="">Select</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Age:</label>
            <input type="number" name="age" min="0" required class="border rounded p-2 w-full">
        </div>

        <div>
            <label class="block font-semibold">Ticket Category:</label>
            <select name="category" required class="border rounded p-2 w-full">
                <option value="">Select</option>
                <option value="VVIP">VVIP – R3000</option>
                <option value="VIP">VIP – R2000</option>
                <option value="General">General Admission – R500</option>
            </select>
        </div>

        <button type="submit"
                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            Book Ticket
        </button>
    </form>

    <hr class="my-6">

    <h2 class="text-xl font-semibold mb-4">Sales Summary</h2>
    <table class="w-full border border-gray-300 text-center">
        <tr class="bg-gray-200">
            <th class="border p-2">Gender</th>
            <th class="border p-2">Age Group</th>
            <th class="border p-2">Number of Tickets Sold</th>
        </tr>
        <?php foreach ($summary as $key => $count): 
            list($g,$ag) = explode('|',$key); ?>
            <tr>
                <td class="border p-2"><?php echo $g; ?></td>
                <td class="border p-2"><?php echo $ag; ?></td>
                <td class="border p-2"><?php echo $count; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p class="mt-4 text-gray-700">
        Total Tickets Sold: <?php echo count($sales); ?> / <?php echo $capacity; ?>
    </p>
</div>


<?php if ($message): ?>
<div id="messageModal"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6 text-center">
    <p class="text-gray-800 text-lg mb-6">
        <?php echo htmlspecialchars($message); ?>
    </p>
    <button id="closeModalBtn"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
        OK
    </button>
  </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('messageModal');
    const btn   = document.getElementById('closeModalBtn');
    if (modal && btn) {
        btn.addEventListener('click', () => modal.remove());
        modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
    }
});
</script>
</body>
</html>
