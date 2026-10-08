-- Bake'n Brew - Database Schema
-- DDL + DML + contoh query

CREATE DATABASE IF NOT EXISTS bakenbrew
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE bakenbrew;

DROP TABLE IF EXISTS sales;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

-- ===================== DDL =====================

CREATE TABLE categories (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(50)  NOT NULL,
  description VARCHAR(255) NOT NULL,
  icon        VARCHAR(10)  NOT NULL,
  status      ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB;

CREATE TABLE products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name        VARCHAR(100) NOT NULL,
  price       INT NOT NULL,
  stock       INT NOT NULL DEFAULT 0,
  image       VARCHAR(100) DEFAULT NULL,
  CONSTRAINT fk_products_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE sales (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  product_id  INT NOT NULL,
  sale_date   DATE NOT NULL,
  quantity    INT NOT NULL,
  total_price INT NOT NULL,
  CONSTRAINT fk_sales_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ===================== DML =====================

INSERT INTO categories (name, description, icon, status) VALUES
('Coffee',      'Espresso based drinks and signature brews',   '☕', 'Active'),
('Bakery',      'Fresh baked breads and pastries every morning','🥐', 'Active'),
('Cakes',       'Whole cakes and slices for sweet occasions',  '🍰', 'Active'),
('Desserts',    'Cookies, brownies and little sweet treats',   '🍪', 'Active'),
('Merchandise', 'Tumblers, mugs and tote bags with our logo',  '🛍️', 'Inactive');

INSERT INTO products (category_id, name, price, stock, image) VALUES
(1, 'Iced Latte',          28000, 40, 'iced-latte.jpg'),
(1, 'Cappuccino',          30000, 35, 'cappuccino.jpg'),
(2, 'Butter Croissant',    22000, 18, 'butter-croissant.jpg'),
(2, 'Cinnamon Roll',       25000,  4, 'cinnamon-roll.jpg'),
(3, 'Strawberry Cake',     38000, 12, 'strawberry-cake.jpg'),
(3, 'Tiramisu Cake',       42000,  0, 'tiramisu-cake.jpg'),
(4, 'Chocolate Brownies',  26000, 25, 'chocolate-brownies.jpg'),
(4, 'Choco Chip Cookies',  18000, 30, 'choco-chip-cookies.jpg'),
(5, 'Bake''n Brew Tumbler',125000, 10, 'tumbler.jpg'),
(5, 'Canvas Tote Bag',     65000,  3, 'tote-bag.jpg');

INSERT INTO sales (product_id, sale_date, quantity, total_price) VALUES
(1, '2026-10-01', 3, 84000),
(2, '2026-10-01', 2, 60000),
(3, '2026-10-02', 4, 88000),
(5, '2026-10-03', 1, 38000),
(7, '2026-10-03', 5, 130000),
(1, '2026-10-04', 2, 56000),
(4, '2026-10-05', 3, 75000),
(9, '2026-10-06', 1, 125000);

-- ===================== CONTOH QUERY =====================

-- SELECT
SELECT * FROM categories WHERE status = 'Active';

-- SELECT ... JOIN ...
SELECT p.id, p.name AS product, c.name AS category, p.price, p.stock
FROM products p
JOIN categories c ON p.category_id = c.id
ORDER BY p.id;

SELECT s.id, p.name AS product, s.sale_date, s.quantity, s.total_price
FROM sales s
JOIN products p ON s.product_id = p.id
ORDER BY s.sale_date DESC;

-- UPDATE
UPDATE products SET stock = 20 WHERE name = 'Tiramisu Cake';

-- DELETE (menghapus data penjualan; produk yang punya sales tidak bisa dihapus karena FK)
DELETE FROM sales WHERE id = 8;
