CREATE DATABASE IF NOT EXISTS rsoa_rsoa285_34;
USE rsoa_rsoa285_34;

CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    type VARCHAR(50),
    amenities TEXT,
    rating DECIMAL(2, 1) DEFAULT 4.5,
    reviews_count INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT,
    guest_name VARCHAR(100),
    guest_email VARCHAR(100),
    check_in DATE,
    check_out DATE,
    total_price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id)
);

-- Sample Data
INSERT INTO properties (title, description, location, price, image_url, type, amenities, rating, reviews_count) VALUES
('Luxury Beachfront Villa', 'Stunning villa with direct beach access and private pool.', 'Malibu, CA', 450.00, 'property1.jpg', 'Villa', 'WiFi, Pool, Kitchen, Parking', 4.9, 128),
('Modern City Loft', 'Chic loft in the heart of downtown, close to all attractions.', 'New York, NY', 180.00, 'property2.jpg', 'Apartment', 'WiFi, AC, Gym, Workspace', 4.7, 85),
('Cosy Mountain Cabin', 'Escape to the woods in this charming wooden cabin.', 'Aspen, CO', 220.00, 'property3.jpg', 'Cabin', 'Fireplace, WiFi, Kitchen, Hiking', 4.8, 56),
('Sunset View Penthouse', 'Breathtaking city views from the top floor.', 'Chicago, IL', 350.00, 'property4.jpg', 'Penthouse', 'WiFi, TV, Balcony, Elevator', 4.6, 92),
('Quiet Garden Cottage', 'Peaceful stay in a beautiful garden setting.', 'London, UK', 120.00, 'property5.jpg', 'Cottage', 'WiFi, Garden, Breakfast, Pet-friendly', 4.5, 43),
('Tropical Paradise Resort', 'Live your best life in this tropical island getaway.', 'Bali, Indonesia', 300.00, 'property6.jpg', 'Resort', 'Pool, Spa, Breakfast, AC', 4.9, 210);
