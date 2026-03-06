<?php
include 'db_config.php';

$location = isset($_GET['location']) ? $_GET['location'] : '';
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Build Query
$query = "SELECT * FROM properties WHERE 1=1";
$params = [];

if (!empty($location)) {
    $query .= " AND (location LIKE ? OR title LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}

if (!empty($type)) {
    $query .= " AND type = ?";
    $params[] = $type;
}

// Sorting
if ($sort == 'price_low') {
    $query .= " ORDER BY price ASC";
} elseif ($sort == 'price_high') {
    $query .= " ORDER BY price DESC";
} elseif ($sort == 'rating') {
    $query .= " ORDER BY rating DESC";
}

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $properties = $stmt->fetchAll();
} catch (Exception $e) {
    $properties = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | StayGlow</title>
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
            padding-top: 100px;
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

        /* Filter & Sort Bar */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 8%;
            border-bottom: 1px solid #222;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filter-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        select,
        input[type="text"] {
            background: var(--card-bg);
            color: white;
            border: 1px solid #333;
            padding: 8px 15px;
            border-radius: 20px;
            outline: none;
            transition: var(--transition);
        }

        select:focus,
        input[type="text"]:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 10px var(--glow-orange);
        }

        /* Content Area */
        .main-container {
            display: flex;
            padding: 40px 8%;
            gap: 40px;
        }

        .results-section {
            flex: 1;
        }

        .results-header {
            margin-bottom: 30px;
        }

        .results-header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .results-header span {
            color: var(--primary-orange);
        }

        /* Listing Grid */
        .listing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .listing-card {
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid #333;
        }

        .listing-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-orange);
            box-shadow: 0 8px 15px rgba(255, 77, 0, 0.2);
        }

        .listing-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .listing-content {
            padding: 15px;
        }

        .listing-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .listing-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .listing-rating {
            color: var(--primary-orange);
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.9rem;
        }

        .listing-location {
            color: var(--text-gray);
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .listing-price {
            font-weight: 700;
        }

        .listing-price span {
            color: var(--primary-orange);
        }

        /* Map Placeholder */
        .map-section {
            width: 400px;
            height: calc(100vh - 200px);
            position: sticky;
            top: 150px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glow-orange);
            box-shadow: 0 0 15px var(--glow-orange);
        }

        .map-section iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        @media (max-width: 1024px) {
            .map-section {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav>
        <a href="index.php" class="logo">
            <i class="fa-brands fa-airbnb"></i>
            <span>stayglow</span>
        </a>
        <div
            style="background: var(--card-bg); border: 1px solid #333; padding: 5px 20px; border-radius: 30px; display: flex; align-items: center; gap: 15px;">
            <span style="font-size: 0.85rem; font-weight: 600;">
                <?php echo !empty($location) ? htmlspecialchars($location) : 'Anywhere'; ?>
            </span>
            <span style="color: #444;">|</span>
            <span style="font-size: 0.85rem; font-weight: 600;">Any week</span>
            <span style="color: #444;">|</span>
            <span style="font-size: 0.85rem; color: var(--text-gray);">Add guests</span>
            <div
                style="background: var(--primary-orange); padding: 6px; border-radius: 50%; color: white; margin-left: 10px;">
                <i class="fas fa-search" style="font-size: 10px;"></i>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 20px;">
            <span style="font-size: 0.9rem;">Become a Host</span>
            <i class="fas fa-globe"></i>
            <div style="background: var(--card-bg); padding: 8px 12px; border-radius: 30px; border: 1px solid #333;">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <div class="filter-bar">
        <div class="filter-group">
            <select id="typeFilter" onchange="applyFilters()">
                <option value="">All Types</option>
                <option value="Villa" <?php echo $type == 'Villa' ? 'selected' : ''; ?>>Villa</option>
                <option value="Apartment" <?php echo $type == 'Apartment' ? 'selected' : ''; ?>>Apartment</option>
                <option value="Cabin" <?php echo $type == 'Cabin' ? 'selected' : ''; ?>>Cabin</option>
                <option value="Penthouse" <?php echo $type == 'Penthouse' ? 'selected' : ''; ?>>Penthouse</option>
            </select>
            <input type="text" placeholder="Price Range: $0 - $1000" disabled>
        </div>
        <div class="filter-group">
            <span style="font-size: 0.9rem; color: var(--text-gray);">Sort by:</span>
            <select id="sortFilter" onchange="applyFilters()">
                <option value="default" <?php echo $sort == 'default' ? 'selected' : ''; ?>>Recommended</option>
                <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High
                </option>
                <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low
                </option>
                <option value="rating" <?php echo $sort == 'rating' ? 'selected' : ''; ?>>Top Rated</option>
            </select>
        </div>
    </div>

    <div class="main-container">
        <section class="results-section">
            <div class="results-header">
                <h1>Stays in <span>
                        <?php echo !empty($location) ? htmlspecialchars($location) : 'the World'; ?>
                    </span></h1>
                <p style="color: var(--text-gray); font-size: 0.9rem;">
                    <?php echo count($properties); ?>+ luxury properties available
                </p>
            </div>

            <div class="listing-grid">
                <?php if (!empty($properties)): ?>
                    <?php foreach ($properties as $prop): ?>
                        <div class="listing-card" onclick="goToProperty(<?php echo $prop['id']; ?>)">
                            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=500&q=80"
                                alt="Listing" class="listing-img">
                            <div class="listing-content">
                                <div class="listing-info">
                                    <p class="listing-title">
                                        <?php echo htmlspecialchars($prop['title']); ?>
                                    </p>
                                    <div class="listing-rating">
                                        <i class="fas fa-star"></i>
                                        <span>
                                            <?php echo $prop['rating']; ?>
                                        </span>
                                    </div>
                                </div>
                                <p class="listing-location">
                                    <?php echo htmlspecialchars($prop['location']); ?>
                                </p>
                                <p class="listing-price"><span>$
                                        <?php echo number_format($prop['price'], 0); ?>
                                    </span> / night</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 50px;">
                        <i class="fas fa-search-minus" style="font-size: 4rem; color: #333; margin-bottom: 20px;"></i>
                        <h2>No properties found</h2>
                        <p style="color: var(--text-gray);">Try adjusting your search filters or location.</p>
                        <button onclick="window.location.href='index.php'" class="btn"
                            style="margin-top: 20px; background: var(--primary-orange); border: none; padding: 10px 20px; color: white; border-radius: 20px; cursor: pointer;">Go
                            Back</button>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="map-section">
            <!-- Simulated Map -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d115814.47957303!2d55.2708!3d25.2048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sae!4v1700000000000!5m2!1sen!2sae"
                allowfullscreen="" loading="lazy"
                style="filter: invert(90%) hue-rotate(180deg) brightness(0.8) contrast(1.2);">
            </iframe>
        </section>
    </div>

    <script>
        function applyFilters() {
            const sort = document.getElementById('sortFilter').value;
            const type = document.getElementById('typeFilter').value;
            const urlParams = new URLSearchParams(window.location.search);

            if (sort) urlParams.set('sort', sort);
            if (type) urlParams.set('type', type); else urlParams.delete('type');

            window.location.href = 'listings.php?' + urlParams.toString();
        }

        function goToProperty(id) {
            window.location.href = `property.php?id=${id}`;
        }
    </script>
</body>

</html>
