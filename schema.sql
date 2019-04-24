CREATE DATABASE yaticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yaticave;

CREATE TABLE categories(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(30) UNIQUE,
  code CHAR(30) UNIQUE
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name CHAR(30),
  description TEXT,
  image CHAR(128),
  price INT(10),
  date_finish TIMESTAMP NULL,
  step_price INT(10),
  id_user INT,
  id_winner INT,
  id_category INT
);

CREATE TABLE rate(
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_rate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  amount INT(10),
  id_user INT,
  id_lot INT
);

CREATE TABLE user(
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(30) UNIQUE,
  name CHAR(30) UNIQUE,
  password CHAR(30),
  avatar CHAR(128),
  contact CHAR(255)
);
