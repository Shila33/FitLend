CREATE DATABASE fitness_lending;

USE fitness_lending;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50),
    available_count INT DEFAULT 0,
    daily_fee DECIMAL(10, 2) DEFAULT 0,
    image VARCHAR(255),
    available BOOLEAN DEFAULT TRUE
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    start_date DATE,
    end_date DATE,
    number_of_equipment INT,
    total_amount DECIMAL(10, 2) DEFAULT 0,
    status ENUM('pending', 'approved', 'returned') DEFAULT 'pending',
    payment_method ENUM('Mpesa', 'Card'),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

CREATE TABLE late_fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    late_days INT DEFAULT 0,
    total_fee DECIMAL(10, 2) DEFAULT 0,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
);


CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscription_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Admin Table
INSERT INTO admins (username, password) VALUES 
('sheila', 'sheila@admin');

-- Users Table 
INSERT INTO users (username, email, phone, password) VALUES 
('Fayizah', 'fayizah@gmail.com', '+25445673452', 'fayizah123'),
('Michelle', 'michelle@gmail.com', '+25473670233', 'michelle123'),
('Adan', 'adan@gmail.com', '+25445387938', 'adan123'),
('Julius', 'julius@gmail.com', '+25456457356', 'julius123');

-- Equipment Table
INSERT INTO equipment (name, description, category, available_count, daily_fee, image) VALUES 
('Treadmill', 'High-quality treadmill for cardio workouts', 'Cardio', 5, 350.00, 'treadmill.jpg'),
('Dumbbells', 'Set of adjustable dumbbells for strength training', 'Strength', 10, 40.00, 'dumbbells.jpg'),
('Yoga Mat', 'Non-slip yoga mat for various exercises', 'Flexibility', 15, 20.00, 'yoga_mat.jpg'),
('Stationary Bike', 'Indoor stationary bike for low-impact cardio', 'Cardio', 4, 500.00, 'stationary_bike.jpg'),
('Kettlebells', 'Set of kettlebells ranging from 5 to 50 lbs for strength training', 'Strength', 12, 30.00, 'kettlebells.jpg'),
('Resistance Bands', 'Set of resistance bands with varying tension levels for resistance training', 'Strength', 20, 15.00, 'resistance_bands.jpg'),
('Rowing Machine', 'High-end rowing machine for full-body cardio workouts', 'Cardio', 3, 450.00, 'rowing_machine.jpg'),
('Pull-Up Bar', 'Durable pull-up bar for upper body workouts', 'Strength', 8, 25.00, 'pull_up_bar.jpg'),
('Medicine Ball', 'Set of medicine balls for core training and rehabilitation exercises', 'Strength', 10, 20.00, 'medicine_ball.jpg'),
('Elliptical Trainer', 'Low-impact elliptical trainer for full-body cardio', 'Cardio', 4, 400.00, 'elliptical_trainer.jpg'),
('Foam Roller', 'High-density foam roller for muscle recovery and flexibility', 'Flexibility', 18, 10.00, 'foam_roller.jpg'),
('Punching Bag', 'Heavy-duty punching bag for boxing and martial arts training', 'Strength', 5, 60.00, 'punching_bag.jpg');

--Reservations Table
INSERT INTO reservations (user_id, equipment_id, start_date, end_date, number_of_equipment, total_amount, payment_method, status) VALUES 
(1, 1, '2024-06-21', '2024-06-28', 2, 5600, 'Card', 'Pending'),
(2, 2, '2024-06-22', '2024-06-29', 1, 320, 'Mpesa', 'Pending'),
(3, 3, '2024-06-23', '2024-06-30', 3, 480, 'Mpesa', 'Approved'),
(4, 4, '2024-06-24', '2024-07-01', 1, 4000, 'Card', 'Pending');

-- Reviews Table
INSERT INTO reviews (equipment_id, rating, comment, user_id) VALUES 
(1, 5, 'Excellent treadmill, very smooth!', 1),
(2, 4, 'Dumbbells are solid, good for home workouts.', 2),
(3, 5, 'Yoga mat has great grip, love it!', 3),
(4, 3, 'Stationary bike is okay, could be more comfortable.', 4);

