-- Database schema for Health Fitness Monitoring System

CREATE DATABASE IF NOT EXISTS fitness_db;
USE fitness_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE fitness_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  date DATE NOT NULL,
  weight FLOAT,
  bmi FLOAT,
  steps INT,
  calories FLOAT,
  FOREIGN KEY(user_id) REFERENCES users(id)
);
