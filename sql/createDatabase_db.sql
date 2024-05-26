CREATE DATABASE IF NOT EXISTS WorldOfGuns_db;

USE WorldOfGuns_db;

CREATE TABLE IF NOT EXISTS Users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    avatarId INT NOT NULL,
    admin BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS Modification (
    modificationId INT AUTO_INCREMENT PRIMARY KEY,
    modificationName VARCHAR(100) NOT NULL,
    modificationImagePath VARCHAR(100) NULL,
    modificationDescription VARCHAR(999) NULL,
    modificationEstimatedPrice FLOAT NULL
);

CREATE TABLE IF NOT EXISTS Guns (
    gunId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT, 
    gunName VARCHAR(100) NOT NULL,
    gunDescription VARCHAR(999) NULL,
    countryOfOrigin VARCHAR(100) NULL, 
    year INT NULL, 
    gunEstimatedPrice FLOAT NULL,
    type ENUM('Pistol', 'Rifle', 'Shotgun', 'Sniper', 'Submachine Gun', 'Machine Gun', 'Other','Grenade Launcher','Rocket Launcher','Flamethrower','Minigun') NULL,
    gunImagePath VARCHAR(300) NULL,
    soundPath VARCHAR(300) NULL,
    showInGunsPage BOOLEAN NOT NULL,  
    FOREIGN KEY (userId) REFERENCES Users(userId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS QuestionAndAnswer (
    infoId INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(100) NOT NULL,
    answer VARCHAR(999) NOT NULL
);

CREATE TABLE IF NOT EXISTS Favourite (
    favouriteId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    gunId INT,
    FOREIGN KEY (userId) REFERENCES Users(userId) ON DELETE CASCADE,
    FOREIGN KEY (gunId) REFERENCES Guns(gunId) ON DELETE CASCADE
);



