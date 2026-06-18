
CREATE DATABASE IF NOT EXISTS sqlinjection CHARACTER SET utf8 COLLATE utf8_general_ci;
USE sqlinjection;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    img VARCHAR(255) NOT NULL,
    badge VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_cookies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cookie_token VARCHAR(255) NOT NULL,
    user_agent TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, description, category, price, img, badge) VALUES
('Laptop Pro', 'High performance laptop for testing.', 'Laptop', 1299.99, 'laptop.svg', 'HOT'),
('Wireless Headphones', 'Noise cancelling headphones.', 'Audio', 199.99, 'headphones.svg', 'NEW'),
('Smart Watch', 'Track your daily activity.', 'Wearables', 249.99, 'watch.svg', NULL),
('Digital Camera', 'Professional photography camera.', 'Camera', 799.99, 'camera.svg', 'SALE'),
('Bluetooth Speaker', 'Portable speaker with deep bass.', 'Audio', 89.99, 'speaker.svg', NULL),
('Fast Charger', 'USB-C fast charging adapter.', 'Accessories', 29.99, 'charger(1).svg', NULL);

INSERT INTO users (username, password) VALUES
('admin', 'admin123'),
('test', 'test123');
