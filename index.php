<?php
include 'db_config.php';

// Fetch properties for the homepage
$query = "SELECT * FROM properties LIMIT 6";
try {
    $stmt = $conn->query($query);
    $featured_properties = $stmt->fetchAll();
} catch (Exception $e) {
    $featured_properties = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbnb Clone | Luxury Stays</title>
    <!-- Google Fonts -->
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
            overflow-x: hidden;
        }

        /* Shining Border Effect */
        .glow-border {
            border: 1px solid var(--primary-orange);
            box-shadow: 0 0 10px var(--glow-orange), inset 0 0 5px var(--glow-orange);
            transition: var(--transition);
        }

        .glow-border:hover {
            box-shadow: 0 0 20px var(--primary-orange), inset 0 0 10px var(--primary-orange);
        }

        /* Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 8%;
            background: rgba(11, 11, 11, 0.95);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--glow-orange);
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-orange);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 0 0 10px var(--glow-orange);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-white);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary-orange);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--card-bg);
            padding: 8px 15px;
            border-radius: 30px;
            border: 1px solid var(--glow-orange);
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            height: 80vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)),
                url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding-top: 100px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        .hero span {
            color: var(--primary-orange);
            text-shadow: 0 0 15px var(--glow-orange);
        }

        /* Search Bar */
        .search-container {
            background: var(--card-bg);
            padding: 10px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            width: 80%;
            max-width: 900px;
            margin-top: 40px;
            border: 1px solid var(--glow-orange);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .search-item {
            flex: 1;
            padding: 10px 25px;
            border-right: 1px solid #333;
            text-align: left;
        }

        .search-item:last-child {
            border-right: none;
        }

        .search-item label {
            display: block;
            font-size: 12px;
            color: var(--text-gray);
            font-weight: 600;
            text-transform: uppercase;
        }

        .search-item input {
            background: transparent;
            border: none;
            color: white;
            padding: 5px 0;
            width: 100%;
            outline: none;
            font-size: 14px;
        }

        .search-btn {
            background: var(--primary-orange);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: 0 0 15px var(--glow-orange);
        }

        .search-btn:hover {
            background: var(--secondary-orange);
            transform: scale(1.05);
        }

        /* Sections */
        .section {
            padding: 80px 8%;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 2rem;
            position: relative;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-orange);
            box-shadow: 0 0 10px var(--primary-orange);
        }

        /* Categories */
        .categories {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 20px;
            scrollbar-width: none;
        }

        .category-item {
            min-width: 120px;
            text-align: center;
            cursor: pointer;
            opacity: 0.7;
            transition: var(--transition);
        }

        .category-item i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .category-item:hover,
        .category-item.active {
            opacity: 1;
            color: var(--primary-orange);
            text-shadow: 0 0 10px var(--glow-orange);
        }

        /* Listing Grid */
        .listing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .listing-card {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid #333;
        }

        .listing-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-orange);
            box-shadow: 0 10px 20px rgba(255, 77, 0, 0.2);
        }

        .listing-img {
            height: 250px;
            width: 100%;
            object-fit: cover;
            border-bottom: 1px solid var(--glow-orange);
        }

        .listing-content {
            padding: 20px;
        }

        .listing-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .listing-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .listing-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--primary-orange);
        }

        .listing-location {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .listing-price {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .listing-price span {
            color: var(--primary-orange);
        }

        /* Footer */
        footer {
            background: #050505;
            padding: 60px 8% 30px;
            border-top: 1px solid var(--glow-orange);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            color: var(--primary-orange);
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 12px;
        }

        .footer-col a {
            color: var(--text-gray);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-col a:hover {
            color: var(--primary-orange);
            padding-left: 5px;
        }

        .copyright {
            padding-top: 30px;
            border-top: 1px solid #222;
            text-align: center;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            nav {
                padding: 15px 5%;
            }

            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .search-container {
                flex-direction: column;
                border-radius: 20px;
                width: 90%;
            }

            .search-item {
                border-right: none;
                border-bottom: 1px solid #333;
                width: 100%;
            }

            .search-btn {
                width: 100%;
                margin-top: 10px;
                justify-content: center;
            }

            .section {
                padding: 50px 5%;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav>
        <a href="index.php" class="logo">
            <i class="fa-brands fa-airbnb"></i>
            <span>stayglow</span>
        </a>
        <ul class="nav-links">
            <li><a href="#">Stays</a></li>
            <li><a href="#">Experiences</a></li>
            <li><a href="#">Online Experiences</a></li>
        </ul>
        <div class="user-menu">
            <i class="fas fa-bars"></i>
            <i class="fas fa-user-circle" style="font-size: 24px; color: var(--primary-orange);"></i>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Find your next <span>luxurious</span> escape</h1>
        <p style="margin-bottom: 30px; opacity: 0.8;">Discover the most exclusive properties around the globe.</p>

        <form id="searchForm" class="search-container glow-border">
            <div class="search-item">
                <label>Where</label>
                <input type="text" name="location" placeholder="Search destinations" id="location">
            </div>
            <div class="search-item">
                <label>Check in</label>
                <input type="date" name="checkin" id="checkin">
            </div>
            <div class="search-item">
                <label>Check out</label>
                <input type="date" name="checkout" id="checkout">
            </div>
            <div class="search-item">
                <label>Who</label>
                <input type="text" placeholder="Add guests">
            </div>
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
                Search
            </button>
        </form>
    </section>

    <!-- Categories -->
    <section class="section" style="padding-bottom: 0;">
        <div class="categories">
            <div class="category-item active">
                <i class="fas fa-gem"></i>
                <span>Luxury</span>
            </div>
            <div class="category-item">
                <i class="fas fa-swimming-pool"></i>
                <span>Amazing Pools</span>
            </div>
            <div class="category-item">
                <i class="fas fa-mountain"></i>
                <span>Views</span>
            </div>
            <div class="category-item">
                <i class="fas fa-umbrella-beach"></i>
                <span>Beachfront</span>
            </div>
            <div class="category-item">
                <i class="fas fa-fire"></i>
                <span>Trending</span>
            </div>
            <div class="category-item">
                <i class="fas fa-castle"></i>
                <span>Castles</span>
            </div>
            <div class="category-item">
                <i class="fas fa-tree"></i>
                <span>Cabins</span>
            </div>
            <div class="category-item">
                <i class="fas fa-city"></i>
                <span>Top Cities</span>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="section">
        <div class="section-header">
            <h2>Featured <span>Listings</span></h2>
            <a href="listings.php" style="color: var(--primary-orange); text-decoration: none; font-weight: 600;">View
                all</a>
        </div>

        <div class="listing-grid">
            <?php if (!empty($featured_properties)): ?>
                <?php foreach ($featured_properties as $prop): ?>
                    <div class="listing-card" onclick="goToProperty(<?php echo $prop['id']; ?>)">
                        <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80"
                            alt="Property" class="listing-img">
                        <div class="listing-content">
                            <div class="listing-info">
                                <p class="listing-title"><?php echo htmlspecialchars($prop['title']); ?></p>
                                <div class="listing-rating">
                                    <i class="fas fa-star"></i>
                                    <span><?php echo $prop['rating']; ?></span>
                                </div>
                            </div>
                            <p class="listing-location"><?php echo htmlspecialchars($prop['location']); ?></p>
                            <p class="listing-price"><span>$<?php echo number_format($prop['price'], 0); ?></span> / night</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No properties found. Please run the database setup.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Centre</a></li>
                    <li><a href="#">AirCover</a></li>
                    <li><a href="#">Anti-discrimination</a></li>
                    <li><a href="#">Disability support</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Hosting</h4>
                <ul>
                    <li><a href="#">StayGlow your home</a></li>
                    <li><a href="#">AirCover for Hosts</a></li>
                    <li><a href="#">Hosting resources</a></li>
                    <li><a href="#">Community forum</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>StayGlow</h4>
                <ul>
                    <li><a href="#">Newsroom</a></li>
                    <li><a href="#">New features</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Investors</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <a href="#" class="logo" style="margin-bottom: 20px;">
                    <i class="fa-brands fa-airbnb"></i>
                    <span>stayglow</span>
                </a>
                <p style="color: var(--text-gray); font-size: 0.9rem;">The world's most luxurious booking platform for
                    elite travelers.</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2026 StayGlow Clone by Zunaira AI Educationwali. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.getElementById('searchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const location = document.getElementById('location').value;
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;

            // Redirect using JavaScript as requested
            window.location.href = `listings.php?location=${encodeURIComponent(location)}&checkin=${checkin}&checkout=${checkout}`;
        });

        function goToProperty(id) {
            window.location.href = `property.php?id=${id}`;
        }
    </script>
</body>

</html>
