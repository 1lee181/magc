/*
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: SQL script to seed the executives table with the real 2026 MVCC team data.
 */

-- ============================================================
-- Run this in phpMyAdmin to add/update the MVCC executive team
-- Go to phpMyAdmin > select "venturehub" database > Import tab
-- ============================================================

USE venturehub;

-- Clear old placeholder execs and insert real ones
DELETE FROM executives;

INSERT INTO executives
(name, role, bio, photo_url, linkedin_url, instagram_url, display_order) VALUES
('Veer Sarin', 'Co-founder', 'Leads strategic direction and long-term growth initiatives for MVCC.', '/cs1xd3/venturehub/veer.jpg', 'https://www.linkedin.com/in/veersarin/', NULL, 1),
('Diya Shah', 'Co-founder', 'Helps run club operations and strengthen member experience.', '/cs1xd3/venturehub/diya.jpg', 'https://www.linkedin.com/in/diyashahc/', NULL, 2),
('Benicio Uhart', 'Co-founder', 'Supports startup outreach and founder-focused programming.', '/cs1xd3/venturehub/benicio.jpg', 'https://www.linkedin.com/in/buhart/', NULL, 3),
('Josh Michell', 'VP Operations', 'Coordinates logistics and execution for weekly club activities.', '/cs1xd3/venturehub/josh.jpg', 'https://www.linkedin.com/in/joshua-michell/', NULL, 4),
('Hannah Lewin', 'VP Marketing', 'Leads branding, communications, and campaign planning.', '/cs1xd3/venturehub/hannah.jpg', 'https://www.linkedin.com/in/hannahlewin22/', NULL, 5),
('Hunaid Rajkotwala', 'Co-VP Education', 'Designs educational sessions on venture capital fundamentals.', '/cs1xd3/venturehub/hunnaid.jpg', 'https://www.linkedin.com/in/hunaid-rajkotwala/', NULL, 6),
('Abhay Shenoy', 'Co-VP Education', 'Co-leads analyst development and mentors new members.', '/cs1xd3/venturehub/abhay.jpg', 'https://www.linkedin.com/in/abhay-shenoy-170b26339/', NULL, 7);