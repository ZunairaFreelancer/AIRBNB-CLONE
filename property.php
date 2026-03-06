<?php
include 'db_config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$query = "SELECT * FROM properties WHERE id = ?";
try {
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $prop = $stmt->fetch();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if (!$prop) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($prop['title']); ?> | StayGlow
    </title>
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
            padding-top: 80px;
        }

        /* Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 8%;
            background: rgba(11, 11, 11, 0.95);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--glow-orange);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-orange);
            text-decoration: none;
            text-shadow: 0 0 10px var(--glow-orange);
        }

        /* Layout */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 5%;
        }

        .property-header {
            margin-bottom: 25px;
        }

        .property-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .property-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .property-meta a {
            color: var(--text-white);
            text-decoration: underline;
        }

        /* Media Gallery */
        .gallery {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 200px 200px;
            gap: 10px;
            margin-bottom: 30px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glow-orange);
            box-shadow: 0 0 20px rgba(255, 77, 0, 0.2);
        }

        .gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .gallery img:hover {
            transform: scale(1.02);
        }

        .main-img {
            grid-row: span 2;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1.8fr 1.2fr;
            gap: 60px;
        }

        .details h2 {
            font-size: 1.5rem;
            border-bottom: 1px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .host-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .host-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .amenities {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-gray);
        }

        .amenity-item i {
            color: var(--primary-orange);
            width: 20px;
        }

        /* Booking Widget */
        .booking-widget {
            background: var(--card-bg);
            border: 1px solid var(--glow-orange);
            padding: 30px;
            border-radius: 20px;
            position: sticky;
            top: 120px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .booking-widget h3 {
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .booking-widget h3 span {
            color: var(--primary-orange);
        }

        .input-group {
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 10px;
            overflow: hidden;
        }

        .input-row {
            display: flex;
            border-bottom: 1px solid #444;
        }

        .input-item {
            flex: 1;
            padding: 10px;
            border-right: 1px solid #444;
        }

        .input-item:last-child {
            border-right: none;
        }

        .input-item label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-gray);
        }

        .input-item input {
            background: transparent;
            border: none;
            color: white;
            width: 100%;
            font-size: 12px;
            outline: none;
        }

        .book-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: var(--transition);
            box-shadow: 0 0 15px var(--glow-orange);
        }

        .book-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 0 25px var(--primary-orange);
        }

        .pricing-breakdown {
            margin-top: 20px;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .price-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-price {
            border-top: 1px solid #333;
            padding-top: 10px;
            font-weight: 700;
            color: white;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .gallery {
                grid-template-columns: 1fr;
                grid-template-rows: 250px;
            }

            .side-img {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="logo"><i class="fa-brands fa-airbnb"></i> <span>stayglow</span></a>
        <div style="display: flex; align-items: center; gap:20px;">
            <a href="listings.php" style="color:white; text-decoration:none;">Search</a>
            <div style="background: var(--card-bg); padding: 8px 12px; border-radius: 30px; border: 1px solid #333;"><i
                    class="fas fa-bars"></i></div>
        </div>
    </nav>

    <div class="container">
        <div class="property-header">
            <h1>
                <?php echo htmlspecialchars($prop['title']); ?>
            </h1>
            <div class="property-meta">
                <div>
                    <i class="fas fa-star" style="color: var(--primary-orange);"></i>
                    <b>
                        <?php echo $prop['rating']; ?>
                    </b> &middot;
                    <a href="#">
                        <?php echo $prop['reviews_count']; ?> reviews
                    </a> &middot;
                    <i class="fas fa-medal" style="color: var(--primary-orange);"></i> Superhost &middot;
                    <a href="#">
                        <?php echo htmlspecialchars($prop['location']); ?>
                    </a>
                </div>
                <div><a href="#"><i class="fas fa-share-alt"></i> Share</a> &nbsp; <a href="#"><i
                            class="far fa-heart"></i> Save</a></div>
            </div>
        </div>

        <div class="gallery">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80"
                alt="Main" class="main-img">
            <img src="https://images.unsplash.com/photo-1571011234235-ef46d877e62a?auto=format&fit=crop&w=400&q=80"
                alt="Room" class="side-img">
            <img src="https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=400&q=80"
                alt="Bath" class="side-img">
        </div>

        <div class="content-grid">
            <div class="details">
                <div class="host-info">
                    <div style="flex: 1;">
                        <h2>Hosted by Zunaira</h2>
                        <p style="color: var(--text-gray); margin-top: -15px;">6 guests &middot; 3 bedrooms &middot; 4
                            beds &middot; 2.5 baths</p>
                    </div>
                    <div class="host-avatar">Z</div>
                </div>

                <div style="border-top: 1px solid #333; padding: 30px 0;">
                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <i class="fas fa-key" style="font-size: 1.5rem; color: var(--primary-orange);"></i>
                        <div>
                            <b>Self check-in</b>
                            <p style="font-size: 0.9rem; color: var(--text-gray);">Check yourself in with the smartlock.
                            </p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <i class="fas fa-user-shield" style="font-size: 1.5rem; color: var(--primary-orange);"></i>
                        <div>
                            <b>Elite Protection</b>
                            <p style="font-size: 0.9rem; color: var(--text-gray);">Every booking includes free
                                protection from Host cancellations.</p>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid #333; padding: 30px 0;">
                    <p>
                        <?php echo nl2br(htmlspecialchars($prop['description'])); ?>
                    </p>
                </div>

                <div style="border-top: 1px solid #333; padding: 30px 0;">
                    <h3>What this place offers</h3>
                    <div class="amenities">
                        <?php
                        $amenities = explode(',', $prop['amenities']);
                        foreach ($amenities as $item): ?>
                            <div class="amenity-item"><i class="fas fa-check-circle"></i>
                                <?php echo trim($item); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="glow-border"
                        style="margin-top: 30px; background: transparent; color: white; padding: 10px 20px; border-radius: 10px; cursor: pointer;">Show
                        all amenities</button>
                </div>
            </div>

            <div class="sidebar">
                <form id="bookingForm" class="booking-widget">
                    <h3><span>$
                            <?php echo number_format($prop['price'], 0); ?>
                        </span> / night</h3>

                    <div class="input-group">
                        <div class="input-row">
                            <div class="input-item">
                                <label>Check-in</label>
                                <input type="date" id="checkin" required onchange="calculateTotal()">
                            </div>
                            <div class="input-item">
                                <label>Checkout</label>
                                <input type="date" id="checkout" required onchange="calculateTotal()">
                            </div>
                        </div>
                        <div class="input-item" style="border-bottom: none;">
                            <label>Guests</label>
                            <input type="number" id="guests" value="1" min="1" max="10">
                        </div>
                    </div>

                    <p style="font-size: 0.8rem; color: var(--text-gray); text-align: center;">You won't be charged yet
                    </p>

                    <div id="pricingDisplay" class="pricing-breakdown" style="display: none;">
                        <div class="price-line">
                            <span id="nightRateText">$
                                <?php echo $prop['price']; ?> x 0 nights
                            </span>
                            <span id="staySubtotal">$0</span>
                        </div>
                        <div class="price-line">
                            <span>Service fee</span>
                            <span>$45</span>
                        </div>
                        <div class="total-price price-line">
                            <span>Total</span>
                            <span id="finalTotal">$0</span>
                        </div>
                    </div>

                    <button type="submit" class="book-btn">Reserve Stay</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Booking Confirmation Modal (Hidden) -->
    <div id="confirmModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:2000; align-items:center; justify-content:center;">
        <div class="booking-widget" style="width: 400px; text-align: center;">
            <i class="fas fa-check-circle"
                style="font-size: 4rem; color: var(--primary-orange); margin-bottom: 20px;"></i>
            <h2>Confirm Booking</h2>
            <p style="margin: 20px 0; color: var(--text-gray);">Are you sure you want to book <b style="color:white;">
                    <?php echo htmlspecialchars($prop['title']); ?>
                </b>?</p>
            <div style="display: flex; gap: 10px;">
                <button onclick="closeModal()"
                    style="flex:1; padding: 12px; border-radius:10px; border:1px solid #444; background:transparent; color:white; cursor:pointer;">Cancel</button>
                <button onclick="submitBooking()"
                    style="flex:1; padding: 12px; border-radius:10px; border:none; background:var(--primary-orange); color:white; cursor:pointer; font-weight:600;">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        const pricePerNight = <?php echo $prop['price']; ?>;

        function calculateTotal() {
            const cin = new Date(document.getElementById('checkin').value);
            const cout = new Date(document.getElementById('checkout').value);

            if (cin && cout && cout > cin) {
                const diffTime = Math.abs(cout - cin);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                const subtotal = diffDays * pricePerNight;
                const total = subtotal + 45;

                document.getElementById('staySubtotal').innerText = '$' + subtotal.toLocaleString();
                document.getElementById('finalTotal').innerText = '$' + total.toLocaleString();
                document.getElementById('nightRateText').innerText = `$${pricePerNight} x ${diffDays} nights`;
                document.getElementById('pricingDisplay').style.display = 'block';
            } else {
                document.getElementById('pricingDisplay').style.display = 'none';
            }
        }

        document.getElementById('bookingForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const cin = document.getElementById('checkin').value;
            const cout = document.getElementById('checkout').value;
            if (!cin || !cout) return alert("Please select dates");
            document.getElementById('confirmModal').style.display = 'flex';
        });

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        function submitBooking() {
            const cin = document.getElementById('checkin').value;
            const cout = document.getElementById('checkout').value;
            const propertyId = <?php echo $id; ?>;
            const guests = document.getElementById('guests').value;

            // In a real app, we'd send this to a PHP processing file via AJAX or Form
            // Using JS redirection as requested
            window.location.href = `book_process.php?id=${propertyId}&checkin=${cin}&checkout=${cout}&guests=${guests}`;
        }
    </script>
</body>

</html>
