-- ============================================
-- LaptopStock Database (system1)
-- ============================================

CREATE TABLE categories(
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    description varchar(255),
    created_at DATE DEFAULT (CURRENT_DATE)
);

CREATE TABLE brands(
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    description varchar(255),
    image varchar(255),
    created_at DATE DEFAULT (CURRENT_DATE)
);

CREATE TABLE suppliers(
    id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    company varchar(255),
    email varchar(255),
    phone varchar(255),
    notes varchar(255),
    status BOOLEAN DEFAULT TRUE,
    image varchar(255),
    created_at DATE DEFAULT (CURRENT_DATE)
);

-- ============================================
-- NEW: products table
-- (matches the products PHP code, renamed
-- partner_id -> supplier_id to match your
-- suppliers table above)
-- ============================================
CREATE TABLE products(
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    name varchar(255),
    description text,
    price decimal(10,2),
    quantity int(11),
    category_id int(11),
    brand_id int(11),
    supplier_id int(11),
    image varchar(255),
    created_at DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id) REFERENCES brands(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

-- ============================================
-- NEW: users table (simple login, no
-- password hashing / session auth)
-- ============================================
CREATE TABLE users(
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) UNIQUE,
    password varchar(255),
    created_at DATE DEFAULT (CURRENT_DATE)
);
