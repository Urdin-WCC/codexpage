CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    name VARCHAR(100) NOT NULL,
    civil_name VARCHAR(100) NULL,
    location VARCHAR(100) NULL,
    description TEXT NULL,
    role VARCHAR(100) NULL,
    level INT NOT NULL DEFAULT 1,
    notes TEXT NULL,
    status VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4;

INSERT INTO users (email, password, name, level) VALUES
 ('lv33@example.com', '$2y$10$9rihK4c1C9AJD5NIE.e1..dolQ2ovwvcS6/fGWGzbm.AKnr8FV3Eq', 'Usuario33', 33),
 ('lv66@example.com', '$2y$10$9rihK4c1C9AJD5NIE.e1..dolQ2ovwvcS6/fGWGzbm.AKnr8FV3Eq', 'Usuario66', 66),
 ('lv99@example.com', '$2y$10$9rihK4c1C9AJD5NIE.e1..dolQ2ovwvcS6/fGWGzbm.AKnr8FV3Eq', 'Usuario99', 99),
 ('lv100@example.com', '$2y$10$9rihK4c1C9AJD5NIE.e1..dolQ2ovwvcS6/fGWGzbm.AKnr8FV3Eq', 'Usuario100', 100);
