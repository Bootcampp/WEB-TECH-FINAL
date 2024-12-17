-- Drop and recreate database


-- Roles Table
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name ENUM('user', 'designer') NOT NULL UNIQUE
);

-- Users Table with Role Reference
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    UNIQUE KEY unique_email (email)
);

-- Designers Table
CREATE TABLE designers (
    designer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    profile_bio TEXT NULL,
    portfolio_url VARCHAR(255) NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Dress Styles Lookup Table
CREATE TABLE dress_styles (
    style_id INT AUTO_INCREMENT PRIMARY KEY,
    style_name VARCHAR(50) NOT NULL UNIQUE
);

-- Dresses Table
CREATE TABLE dresses (
    dress_id INT AUTO_INCREMENT PRIMARY KEY,
    designer_id INT NOT NULL,
    style_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    
    FOREIGN KEY (designer_id) REFERENCES designers(designer_id) ON DELETE CASCADE,
    FOREIGN KEY (style_id) REFERENCES dress_styles(style_id)
);

-- Cart Table
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dress_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (dress_id) REFERENCES dresses(dress_id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'processed', 'shipped', 'delivered') DEFAULT 'pending',
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    dress_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10, 2) NOT NULL,
    
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (dress_id) REFERENCES dresses(dress_id) ON DELETE CASCADE
);

-- Initial Role Insertions
INSERT INTO roles (role_name) VALUES 
('user'), 
('designer');