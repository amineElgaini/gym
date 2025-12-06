-- Disable foreign key checks to avoid dependency errors
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if they exist (reverse order of dependencies)
DROP TABLE IF EXISTS equipments;
DROP TABLE IF EXISTS equipment_types;
DROP TABLE IF EXISTS equipment_status;

DROP TABLE IF EXISTS cour_category;
DROP TABLE IF EXISTS cours;
DROP TABLE IF EXISTS cour_time;

DROP TABLE IF EXISTS cour_equipment;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;


-- Re-enable FK checks
SET FOREIGN_KEY_CHECKS = 1;


-- Create roles table
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Create equipment_status table (corrected spelling)
CREATE TABLE equipment_status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Create equipment_types table
CREATE TABLE equipment_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Create equipments table
CREATE TABLE equipments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    status_id INT,
    type_id INT,
    FOREIGN KEY (status_id) REFERENCES equipment_status(id),
    FOREIGN KEY (type_id) REFERENCES equipment_types(id)
);

-- Create course categories
CREATE TABLE cour_category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Create cours table
CREATE TABLE cours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    category_id INT,
    max INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES cour_category(id)
);

-- Create cour_equipment table
CREATE TABLE cour_equipment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    -- name VARCHAR(100) NOT NULL,
    cour_id INT,
    equipment_id INT,
    FOREIGN KEY (cour_id) REFERENCES cours(id) ON DELETE CASCADE,
    FOREIGN KEY (equipment_id) REFERENCES equipments(id)
);

-- Create cour_time table
CREATE TABLE cour_time (
    id INT PRIMARY KEY AUTO_INCREMENT,
    day VARCHAR(20) NOT NULL,
    start_time TIME NOT NULL,
    time_in_minutes INT NOT NULL,
    cour_id INT,
    FOREIGN KEY (cour_id) REFERENCES cours(id) ON DELETE CASCADE
);



-- Insert data

INSERT INTO equipment_status (name) VALUES
('bon etat'),
('bon'),
('medium');

INSERT INTO cour_category (name) VALUES
('Yoga'),
('Cardio'),
('Strength Training');

INSERT INTO equipment_types (name) VALUES
('Cardio Machine'),
('Weight Equipment'),
('Accessory');

INSERT INTO equipments (name, quantity, status_id, type_id) VALUES
('Treadmill', 5, 1, 1),
('Dumbbells Set', 10, 1, 2),
('Yoga Mat', 20, 2, 3);

INSERT INTO roles (name) VALUES
('Admin'),
('Member');

INSERT INTO users (userName, password_hash, role_id) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('member', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2);

INSERT INTO cours (name, category_id, max) VALUES
('Morning Yoga Flow', 1, 15),
('HIIT Cardio Blast', 2, 20),
('Power Lifting 101', 3, 10),
('Evening Yoga Stretch', 1, 15),
('Spin Class', 2, 25),
('CrossFit Training', 3, 12);

INSERT INTO cour_time (day, start_time, time_in_minutes, cour_id) VALUES
('Monday', '08:00:00', 60, 1),
('Monday', '18:00:00', 45, 2),
('Tuesday', '07:00:00', 90, 3),
('Wednesday', '19:00:00', 60, 4),
('Thursday', '17:30:00', 45, 5),
('Friday', '06:00:00', 75, 6),
('Saturday', '09:00:00', 60, 1),
('Saturday', '11:00:00', 45, 2);

INSERT INTO cour_equipment (cour_id, equipment_id) VALUES
(1, 3),
(2, 1),
(3, 2),
(4, 3),
(5, 1),
(6, 2);
