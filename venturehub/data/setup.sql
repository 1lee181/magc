/*
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: Initial database schema creation and seed data insertion for VentureHub.
 */

-- ============================================================
-- VentureHub Database Setup
-- Run this in phpMyAdmin: Import tab > choose this file > Go
-- ============================================================

CREATE DATABASE IF NOT EXISTS venturehub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE venturehub;

-- Members: general interest sign-up submissions (Gurehmat)
CREATE TABLE IF NOT EXISTS members (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    program    VARCHAR(100),
    year       TINYINT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Past Events (Matthew)
CREATE TABLE IF NOT EXISTS past_events (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150) NOT NULL,
    description TEXT,
    event_date  DATE,
    location    VARCHAR(200),
    photo_url   VARCHAR(500),
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Partners / Startups ribbon (Matthew)
CREATE TABLE IF NOT EXISTS partners (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    logo_url      VARCHAR(500),
    website_url   VARCHAR(500),
    description   TEXT,
    display_order INT DEFAULT 0
);

-- Executives (Aleesha)
CREATE TABLE IF NOT EXISTS executives (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    role          VARCHAR(100),
    bio           TEXT,
    photo_url     VARCHAR(500),
    linkedin_url  VARCHAR(500),
    instagram_url VARCHAR(500),
    display_order INT DEFAULT 0
);

-- Admins (Matthew)
CREATE TABLE IF NOT EXISTS admins (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

-- ============================================================
-- Seed Data
-- Default admin: username=admin  password=password
-- Generate a new hash with: password_hash('yourpassword', PASSWORD_DEFAULT)
-- ============================================================
INSERT IGNORE INTO admins (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT IGNORE INTO past_events (id, title, description, event_date, location, photo_url) VALUES
(1, 'Pitch Night 2024',    'Students pitched to a panel of real investors.',     '2024-11-15', 'MDCL 1305', '/cs1xd3/venturehub/images/pitch-night.jpg'),
(2, 'Investor Workshop',   'Deep dive into term sheets and valuations.',          '2025-02-08', 'Online', NULL),
(3, 'Networking Social',   'Annual mixer with local VC professionals.',           '2025-03-20', 'CIBC Hall', NULL);

INSERT IGNORE INTO partners (id, name, website_url, description, display_order) VALUES
(1, 'Partner A',  'https://example.com', 'Our founding partner.',   1),
(2, 'Startup B',  'https://example.com', 'Portfolio startup.',       2),
(3, 'Partner C',  'https://example.com', 'Strategic partner.',       3),
(4, 'Startup D',  'https://example.com', 'Portfolio startup.',       4),
(5, 'Partner E',  'https://example.com', 'Industry sponsor.',        5),
(6, 'Startup F',  'https://example.com', 'Portfolio startup.',       6);

INSERT IGNORE INTO executives
(id, name, role, bio, photo_url, linkedin_url, instagram_url, display_order) VALUES
(1, 'Veer Sarin',        'Co-founder',      '', '', '', '', 1),
(2, 'Diya Shah',         'Co-founder',      '', '', '', '', 2),
(3, 'Benicio Uhart',     'Co-founder',      '', '', '', '', 3),
(4, 'Josh Michell',      'VP Operations',   '', '', '', '', 4),
(5, 'Hannah Lewin',      'VP Marketing',    '', '', '', '', 5),
(6, 'Hunaid Rajkotwala', 'Co-VP Education', '', '', '', '', 6),
(7, 'Abhay Shenoy',      'Co-VP Education', '', '', '', '', 7);