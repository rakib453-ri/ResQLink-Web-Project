CREATE DATABASE IF NOT EXISTS resqlink_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE resqlink_db;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS support_activities;
DROP TABLE IF EXISTS volunteer_availability;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS emergency_contacts;
DROP TABLE IF EXISTS family_members;
DROP TABLE IF EXISTS shelter_requests;
DROP TABLE IF EXISTS shelters;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS volunteers;
DROP TABLE IF EXISTS shelter_seekers;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(120) NOT NULL UNIQUE,
 phone VARCHAR(30) DEFAULT NULL,
 password VARCHAR(255) NOT NULL,
 role ENUM('seeker','volunteer','admin') NOT NULL,
 status ENUM('active','pending','blocked') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE shelter_seekers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 address VARCHAR(255) DEFAULT NULL,
 date_of_birth DATE DEFAULT NULL,
 gender VARCHAR(20) DEFAULT NULL,
 CONSTRAINT fk_seeker_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE volunteers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 skills VARCHAR(255) DEFAULT NULL,
 service_area VARCHAR(120) DEFAULT NULL,
 availability_status VARCHAR(30) DEFAULT 'available',
 approved TINYINT(1) NOT NULL DEFAULT 0,
 CONSTRAINT fk_volunteer_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE admins (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 department VARCHAR(100) DEFAULT 'Administration',
 designation VARCHAR(100) DEFAULT 'System Admin',
 CONSTRAINT fk_admin_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE shelters (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 location VARCHAR(255) NOT NULL,
 capacity INT NOT NULL DEFAULT 0,
 available_space INT NOT NULL DEFAULT 0,
 type VARCHAR(60) DEFAULT 'General',
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE shelter_requests (
 id INT AUTO_INCREMENT PRIMARY KEY,
 seeker_user_id INT NOT NULL,
 location VARCHAR(255) NOT NULL,
 people_count INT NOT NULL DEFAULT 1,
 emergency_type VARCHAR(60) NOT NULL,
 special_needs VARCHAR(255) DEFAULT NULL,
 description TEXT DEFAULT NULL,
 status ENUM('pending','approved','rejected','assigned','completed') NOT NULL DEFAULT 'pending',
 assigned_shelter_id INT DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_request_seeker FOREIGN KEY(seeker_user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_request_shelter FOREIGN KEY(assigned_shelter_id) REFERENCES shelters(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE family_members (
 id INT AUTO_INCREMENT PRIMARY KEY,
 seeker_user_id INT NOT NULL,
 name VARCHAR(100) NOT NULL,
 age INT NOT NULL DEFAULT 0,
 relationship VARCHAR(60) NOT NULL,
 gender VARCHAR(20) DEFAULT NULL,
 special_needs VARCHAR(255) DEFAULT NULL,
 CONSTRAINT fk_family_seeker FOREIGN KEY(seeker_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE emergency_contacts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 seeker_user_id INT NOT NULL,
 name VARCHAR(100) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 email VARCHAR(120) DEFAULT NULL,
 relationship VARCHAR(60) NOT NULL,
 CONSTRAINT fk_contact_seeker FOREIGN KEY(seeker_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE tasks (
 id INT AUTO_INCREMENT PRIMARY KEY,
 volunteer_user_id INT NOT NULL,
 title VARCHAR(140) NOT NULL,
 description TEXT DEFAULT NULL,
 status ENUM('pending','accepted','in_progress','completed') NOT NULL DEFAULT 'pending',
 due_date DATE DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_task_volunteer FOREIGN KEY(volunteer_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE volunteer_availability (
 id INT AUTO_INCREMENT PRIMARY KEY,
 volunteer_user_id INT NOT NULL,
 available_date DATE NOT NULL,
 shift_name VARCHAR(30) NOT NULL,
 status ENUM('available','unavailable') NOT NULL DEFAULT 'available',
 notes VARCHAR(255) DEFAULT NULL,
 CONSTRAINT fk_availability_volunteer FOREIGN KEY(volunteer_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE support_activities (
 id INT AUTO_INCREMENT PRIMARY KEY,
 volunteer_user_id INT NOT NULL,
 title VARCHAR(140) NOT NULL,
 description TEXT DEFAULT NULL,
 activity_date DATE NOT NULL,
 status ENUM('planned','ongoing','completed','cancelled') NOT NULL DEFAULT 'planned',
 CONSTRAINT fk_activity_volunteer FOREIGN KEY(volunteer_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Password for seeded demo accounts: password
INSERT INTO users(name,email,phone,password,role,status) VALUES
('System Admin','admin@resqlink.com','01700000000','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','active'),
('Demo Seeker','seeker@resqlink.com','01711111111','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','seeker','active'),
('Demo Volunteer','volunteer@resqlink.com','01722222222','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','volunteer','active');

INSERT INTO admins(user_id) VALUES(1);
INSERT INTO shelter_seekers(user_id,address) VALUES(2,'Dhaka');
INSERT INTO volunteers(user_id,skills,service_area,approved) VALUES(3,'First Aid, Distribution','Dhaka',1);

INSERT INTO shelters(name,location,capacity,available_space,type,status) VALUES
('Uttara Community Shelter','Uttara, Dhaka',200,120,'Community','active'),
('Mirpur Relief Center','Mirpur, Dhaka',150,70,'Relief Center','active');
