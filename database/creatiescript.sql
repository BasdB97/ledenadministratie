CREATE TABLE IF NOT EXISTS bookyear (
  id INT NOT NULL AUTO_INCREMENT,
  year INT UNIQUE NOT NULL,
  description VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS member_type (
  id INT NOT NULL AUTO_INCREMENT,
  description VARCHAR(50) NOT NULL,
  discount_percentage DECIMAL(5,2) NOT NULL,
  minimum_age INT DEFAULT NULL,
  maximum_age INT DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'secretaris', 'penningmeester') NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS family (
  id INT NOT NULL AUTO_INCREMENT,
  last_name VARCHAR(50) NOT NULL,
  street VARCHAR(50) NOT NULL,
  house_number VARCHAR(50) NOT NULL,
  postal_code VARCHAR(50) NOT NULL,
  city VARCHAR(50) NOT NULL,
  country VARCHAR(50) NOT NULL DEFAULT 'Nederland',
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS family_members (
  id INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  date_of_birth DATE NOT NULL,
  age INT NOT NULL,
  family_member_type ENUM('vader', 'moeder', 'zoon', 'dochter', 'oom', 'tante', 'neef', 'nicht', 'anders') NOT NULL,
  member_type_id INT NOT NULL,
  family_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (member_type_id) REFERENCES member_type(id),
  FOREIGN KEY (family_id) REFERENCES family(id)
);

CREATE TABLE IF NOT EXISTS contributions (
  id INT NOT NULL AUTO_INCREMENT,
  member_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  member_type INT NOT NULL,
  bookyear_id INT DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (bookyear_id) REFERENCES bookyear(id),
  FOREIGN KEY (member_id) REFERENCES family_members(id),
  FOREIGN KEY (member_type) REFERENCES member_type(id)
);