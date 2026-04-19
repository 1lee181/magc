-- ============================================================
-- Run this in phpMyAdmin to add/update the MVCC executive team
-- Go to phpMyAdmin > select "venturehub" database > Import tab
-- ============================================================

USE venturehub;

-- Clear old placeholder execs and insert real ones
DELETE FROM executives;

INSERT INTO executives (name, role, bio, display_order) VALUES
('Veer Sarin',        'Co-founder',       '', 1),
('Diya Shah',         'Co-founder',       '', 2),
('Benicio Uhart',     'Co-founder',       '', 3),
('Josh Michell',      'VP Operations',    '', 4),
('Hannah Lewin',      'VP Marketing',     '', 5),
('Hunaid Rajkotwala', 'Co-VP Education',  '', 6),
('Abhay Shenoy',      'Co-VP Education',  '', 7);