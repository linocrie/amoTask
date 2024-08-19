CREATE DATABASE page_visits;
USE page_visits;

CREATE TABLE visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    city VARCHAR(255),
    device TEXT,
    visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
