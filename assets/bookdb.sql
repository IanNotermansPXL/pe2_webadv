
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL
);
    
CREATE TABLE Books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    author VARCHAR(255),
    publication_year INT,
    publisher VARCHAR(255),
    genre VARCHAR(255)
);

CREATE TABLE Loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    book_id INT,
    loan_date DATE,
    return_date DATE,
    FOREIGN KEY (userId) REFERENCES Users(id),
    FOREIGN KEY (book_id) REFERENCES Books(id)
); 
