<?php
include 'db_config.php';

$property_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';
$guests = isset($_GET['guests']) ? (int) $_GET['guests'] : 1;

if ($property_id <= 0 || empty($checkin) || empty($checkout)) {
    echo "<script>alert('Invalid booking details'); window.location.href='index.php';</script>";
    exit;
}

// Fetch property price
$stmt = $conn->prepare("SELECT price, title FROM properties WHERE id = ?");
$stmt->execute([$property_id]);
$prop = $stmt->fetch();

if (!$prop) {
    echo "<script>alert('Property not found'); window.location.href='index.php';</script>";
    exit;
}

// Calculate price
$cin = new DateTime($checkin);
$cout = new DateTime($checkout);
$interval = $cin->diff($cout);
$nights = $interval->days;
if ($nights <= 0)
    $nights = 1;

$total_price = ($nights * $prop['price']) + 45; // 45 is fee

// Insert into DB
try {
    $sql = "INSERT INTO bookings (property_id, guest_name, guest_email, check_in, check_out, total_price) 
            VALUES (?, 'Guest User', 'guest@example.com', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$property_id, $checkin, $checkout, $total_price]);
    $booking_id = $conn->lastInsertId();

    // Redirect using JavaScript as requested
    echo "
    <html>
    <body style='background:#0b0b0b; color:white; font-family:sans-serif; display:flex; align-items:center; justify-content:center; height:100vh;'>
        <div style='text-align:center;'>
            <h2 style='color:#ff4d00;'>Processing your booking...</h2>
            <p>Please wait while we confirm your luxurious stay at " . htmlspecialchars($prop['title']) . "</p>
            <script>
                setTimeout(function() {
                    window.location.href = 'confirmation.php?booking_id=$booking_id';
                }, 2000);
            </script>
        </div>
    </body>
    </html>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
