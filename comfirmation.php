<?php
include 'db_config.php';

$booking_id = isset($_GET['booking_id']) ? (int) $_GET['booking_id'] : 0;

if ($booking_id <= 0) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$query = "SELECT b.*, p.title, p.location, p.image_url 
          FROM bookings b 
          JOIN properties p ON b.property_id = p.id 
          WHERE b.id = ?";
try {
    $stmt = $conn->prepare($query);
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();
} catch (Exception $e) {
    $booking = null;
}

if (!$booking) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed | StayGlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-orange: #ff4d00;
            --secondary-orange: #ff8c00;
            --glow-orange: rgba(255, 77, 0, 0.6);
            --bg-black: #0b0b0b;
            --card-bg: #1a1a1a;
            --text-white: #ffffff;
            --text-gray: #a0a0a0;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-black);
            color: var(--text-white);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .confirm-card {
            background: var(--card-bg);
            border: 2px solid var(--primary-orange);
            border-radius: 30px;
            padding: 50px;
            text-align: center;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 40px var(--glow-orange);
            transform: scale(1);
            animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .check-icon {
            font-size: 5rem;
            color: var(--primary-orange);
            text-shadow: 0 0 20px var(--glow-orange);
            margin-bottom: 30px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        p {
            color: var(--text-gray);
            margin-bottom: 30px;
        }

        .booking-details {
            background: #000;
            border: 1px solid #333;
            border-radius: 20px;
            padding: 25px;
            text-align: left;
            margin-bottom: 40px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #222;
            padding-bottom: 10px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .detail-value {
            font-weight: 600;
            color: var(--primary-orange);
        }

        .home-btn {
            display: inline-block;
            padding: 15px 40px;
            background: var(--primary-orange);
            color: white;
            text-decoration: none;
            border-radius: 40px;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: 0 0 20px var(--glow-orange);
        }

        .home-btn:hover {
            background: var(--secondary-orange);
            transform: translateY(-3px);
            box-shadow: 0 5px 25px var(--primary-orange);
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--primary-orange);
            animation: fall 3s linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <div class="confirm-card">
        <div class="check-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <h1>Booking Confirmed!</h1>
        <p>Your reservation at <b>
                <?php echo htmlspecialchars($booking['title']); ?>
            </b> is successful. A confirmation email has been sent to your inbox.</p>

        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Reservation ID</span>
                <span class="detail-value">#SG-
                    <?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Location</span>
                <span class="detail-value">
                    <?php echo htmlspecialchars($booking['location']); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-in</span>
                <span class="detail-value">
                    <?php echo date('M d, Y', strtotime($booking['check_in'])); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-out</span>
                <span class="detail-value">
                    <?php echo date('M d, Y', strtotime($booking['check_out'])); ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Amount Paid</span>
                <span class="detail-value" style="font-size: 1.2rem;">$
                    <?php echo number_format($booking['total_price'], 2); ?>
                </span>
            </div>
        </div>

        <a href="index.php" class="home-btn">Return to Explore</a>

        <p style="margin-top: 30px; font-size: 0.8rem;">Need help? <a href="#"
                style="color: var(--primary-orange);">Contact Support</a></p>
    </div>

    <script>
        // Simple confetti effect
        for (let i = 0; i < 30; i++) {
            let confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.backgroundColor = Math.random() > 0.5 ? '#ff4d00' : '#ff8c00';
            document.body.appendChild(confetti);
        }
    </script>
</body>

</html>
