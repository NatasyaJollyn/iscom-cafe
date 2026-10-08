# Bake'n Brew

## Project Description
Bake'n Brew adalah website cafe & bakery bernuansa warm dan cozy yang dibangun dengan PHP Native dan database MySQL. Website menampilkan data **kategori**, **produk**, dan **transaksi penjualan** secara dinamis dalam tiga tabel HTML, beserta kartu ringkasan (total categories, products, sales).

## Technologies
- PHP Native (mysqli)
- MySQL
- HTML
- CSS (variables, flexbox, grid, media query)
- JavaScript (mobile navbar & back to top)

## Database Entities
- **categories** – kelompok menu (Coffee, Bakery, Cakes, Desserts, Merchandise).
- **products** – item menu yang dijual, tiap produk milik satu kategori.
- **sales** – catatan transaksi penjualan suatu produk.

## Attributes

**categories**
| Kolom | Fungsi |
|---|---|
| id | Primary key kategori |
| name | Nama kategori |
| description | Deskripsi singkat kategori |
| icon | Emoji ikon kategori |
| status | Status kategori (Active / Inactive) |

**products**
| Kolom | Fungsi |
|---|---|
| id | Primary key produk |
| category_id | Foreign key ke `categories(id)` |
| name | Nama produk |
| price | Harga produk (Rupiah) |
| stock | Jumlah stok tersedia |
| image | Nama file gambar di `images/products/` |

**sales**
| Kolom | Fungsi |
|---|---|
| id | Primary key transaksi |
| product_id | Foreign key ke `products(id)` |
| sale_date | Tanggal transaksi |
| quantity | Jumlah produk terjual |
| total_price | Total harga transaksi (price × quantity) |

## Relationship
- `categories 1 : N products` – satu kategori punya banyak produk (`products.category_id` → `categories.id`).
- `products 1 : N sales` – satu produk dapat muncul di banyak transaksi (`sales.product_id` → `products.id`).

## Cardinality
- categories → products: **one-to-many**. Setiap produk wajib punya tepat 1 kategori; satu kategori boleh punya 0..banyak produk.
- products → sales: **one-to-many**. Setiap transaksi mengacu ke tepat 1 produk; satu produk boleh punya 0..banyak transaksi.

## How to Run
1. Buat database MySQL (otomatis dibuat oleh `schema.sql` dengan nama `bakenbrew`).
2. Import `database/schema.sql` (phpMyAdmin → Import, atau `mysql -u root < database/schema.sql`).
3. Atur koneksi di `services/config.php` (host, username, password, database).
4. Jalankan project melalui localhost (letakkan folder di `htdocs` XAMPP, atau `php -S localhost:8000`).
5. Buka `index.php` (mis. `http://localhost/bakenbrew/index.php`).

## Sample Queries
```sql
-- SELECT
SELECT * FROM categories WHERE status = 'Active';

-- JOIN
SELECT p.id, p.name AS product, c.name AS category, p.price, p.stock
FROM products p
JOIN categories c ON p.category_id = c.id;

SELECT s.id, p.name AS product, s.sale_date, s.quantity, s.total_price
FROM sales s
JOIN products p ON s.product_id = p.id;

-- UPDATE
UPDATE products SET stock = 20 WHERE name = 'Tiramisu Cake';

-- DELETE
DELETE FROM sales WHERE id = 8;
```

## Structure
```text
bake-n-brew/
├── services/config.php
├── database/schema.sql
├── images/products/
├── index.php
├── style.css
└── README.md
```
