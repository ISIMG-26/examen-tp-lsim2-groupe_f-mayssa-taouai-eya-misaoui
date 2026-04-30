-- =====================================================
-- DigiMarket - Digital Products Marketplace
-- Database schema + sample data
-- Import via phpMyAdmin or: mysql -u root -p < database.sql
-- =====================================================

DROP DATABASE IF EXISTS digimarket;
CREATE DATABASE digimarket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digimarket;

-- ---------- USERS ----------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ---------- CATEGORIES ----------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(80) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB;

-- ---------- PRODUCTS ----------
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    seller_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'placeholder.png',
    stock INT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id)   REFERENCES users(id)      ON DELETE CASCADE,
    INDEX idx_cat (category_id),
    INDEX idx_title (title)
) ENGINE=InnoDB;

-- ---------- ORDERS ----------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','paid','delivered','cancelled') DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- SAMPLE DATA ----------
-- Default admin password: admin123  | Default user password: user123
INSERT INTO users (username,email,password,role) VALUES
('admin','admin@digimarket.com', '$2y$10$e0NRMq8r5jX2sYwGhM0H1.8K4zE7Yk3qP9oQ0aB1cD2eF3gH4iJ5K', 'admin'),
('john', 'john@example.com',     '$2y$10$e0NRMq8r5jX2sYwGhM0H1.8K4zE7Yk3qP9oQ0aB1cD2eF3gH4iJ5K', 'user'),
('sara', 'sara@example.com',     '$2y$10$e0NRMq8r5jX2sYwGhM0H1.8K4zE7Yk3qP9oQ0aB1cD2eF3gH4iJ5K', 'user');
-- NOTE: passwords above are placeholders. Real hashes are generated on first run via install.php
-- Or simply register new users from the UI. To reset, run: php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"

INSERT INTO categories (name,slug,description) VALUES
('Gaming Accounts','gaming','Video game accounts, skins, items, virtual currencies'),
('Social Media','social','Instagram, TikTok, YouTube and other platform accounts'),
('Development','dev','Web, mobile and software development services'),
('Design & Creative','design','Graphic design, UI/UX, logos and visual identity'),
('Writing & Marketing','marketing','Copywriting, SEO, community management and ads'),
('Consulting','consulting','Coaching and professional services');

INSERT INTO products (category_id,seller_id,title,description,price,stock) VALUES
(1,2,'Valorant Account - Immortal Rank','Full access, 40+ skins, original email included.', 89.99, 1),
(1,3,'Fortnite OG Account','Rare skins from Season 1-3, linked email transferable.', 149.00, 1),
(1,2,'10000 Genshin Primogems','Top-up via official channel, instant delivery.', 124.50, 25),
(2,3,'Instagram 50K Followers Account','Niche: lifestyle. Real growth, engagement 4%.', 320.00, 1),
(2,2,'TikTok 100K Page','Comedy niche, monetization eligible.', 540.00, 1),
(2,3,'YouTube Channel 10K subs','Tech reviews, monetized.', 780.00, 1),
(3,2,'Custom Landing Page','Responsive landing page in HTML/CSS/JS, 3 days delivery.', 120.00, 10),
(3,3,'Full WordPress Site','Complete site with theme, plugins and SEO setup.', 350.00, 5),
(4,2,'Modern Logo Design','3 concepts, unlimited revisions, all formats.', 75.00, 20),
(4,3,'Mobile App UI Kit','Figma file, 30+ screens, dark/light mode.', 95.00, 15),
(5,2,'SEO Article (1000 words)','Optimized, plagiarism-free, with meta tags.', 25.00, 50),
(5,3,'Facebook Ads Campaign Setup','Audience research + 3 creatives + setup.', 180.00, 10),
(6,2,'1h Career Coaching','Online session, CV review included.', 60.00, 30),
(6,3,'Business Plan Consultation','2h session + 10-page report.', 200.00, 10);
