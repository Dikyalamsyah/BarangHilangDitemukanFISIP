-- CREATE DATABASE lf_gedung_e CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE lf_gedung_e;
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('lost','found') NOT NULL,
  title VARCHAR(120) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(50) NOT NULL,
  location VARCHAR(120) NOT NULL,
  reporter_name VARCHAR(80) NOT NULL,
  reporter_contact VARCHAR(120) NULL,
  image_path VARCHAR(255) NULL,
  status ENUM('pending','published','claimed','archived') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_category (category),
  INDEX idx_title (title),
  INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin default (bcrypt 'admin123')
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$3/pWmBzNLkCHSx1xw5xY5u2w27cS0mRr9y3n9mLxB6oY9a3r6H9z2');

CREATE TABLE IF NOT EXISTS claim_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_id INT NOT NULL,
  admin_id INT NOT NULL,
  claimant_name VARCHAR(120) NOT NULL,
  note TEXT NULL,
  evidence_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
