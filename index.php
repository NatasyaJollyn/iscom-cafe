<?php
require_once __DIR__ . '/services/config.php';

function rupiah($n) { return 'Rp' . number_format((int)$n, 0, ',', '.'); }

// Summary (SELECT COUNT)
$totalCategories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$totalProducts   = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$totalSales      = $conn->query("SELECT COUNT(*) AS total FROM sales")->fetch_assoc()['total'];

// Query tabel
$resCategories = $conn->query("SELECT id, name, description, icon, status FROM categories ORDER BY id");
$resProducts   = $conn->query(
    "SELECT p.id, p.name, c.name AS category, p.price, p.stock
     FROM products p
     JOIN categories c ON p.category_id = c.id
     ORDER BY p.id"
);
$resSales = $conn->query(
    "SELECT s.id, p.name AS product, s.sale_date, s.quantity, s.total_price
     FROM sales s
     JOIN products p ON s.product_id = p.id
     ORDER BY s.sale_date DESC, s.id DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bake'n Brew | Cafe, Bakery &amp; Sweet Moments</title>
  <meta name="description" content="Bake'n Brew adalah cafe dan bakery dengan coffee, bakery, cakes, desserts, dan merchandise. Data kategori, produk, dan penjualan dari database MySQL.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar" id="navbar">
  <div class="container nav-inner">
    <a href="#home" class="logo">☕ Bake'n <span>Brew</span></a>
    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">☰</button>
    <nav class="nav-links" id="navLinks">
      <a href="#home">Home</a>
      <a href="#categories">Categories</a>
      <a href="#products">Products</a>
      <a href="#sales">Sales</a>
    </nav>
  </div>
</header>

<main>
  <section class="hero" id="home">
    <div class="container hero-inner">
      <div class="hero-text">
        <span class="eyebrow">☕ Freshly Baked, Perfectly Brewed</span>
        <h1>Cafe, Bakery &amp; Sweet Moments</h1>
        <p>Secangkir kopi hangat, roti yang baru keluar dari oven, dan kue manis untuk setiap momen spesialmu.</p>
        <a href="#products" class="btn">Explore Our Menu</a>
      </div>
      <div class="hero-art" aria-hidden="true">
        <span>☕</span><span>🥐</span><span>🍰</span><span>🍪</span>
      </div>
    </div>
  </section>

  <section class="container summary">
    <div class="card stat">
      <div class="stat-icon">🗂️</div>
      <div class="stat-num"><?= $totalCategories ?></div>
      <div class="stat-label">Total Categories</div>
    </div>
    <div class="card stat">
      <div class="stat-icon">🧁</div>
      <div class="stat-num"><?= $totalProducts ?></div>
      <div class="stat-label">Total Products</div>
    </div>
    <div class="card stat">
      <div class="stat-icon">🧾</div>
      <div class="stat-num"><?= $totalSales ?></div>
      <div class="stat-label">Total Sales</div>
    </div>
  </section>

  <section class="section container" id="categories">
    <h2>Categories</h2>
    <p class="section-sub">Semua kategori menu Bake'n Brew.</p>
    <div class="table-wrap card">
      <table>
        <thead><tr><th>ID</th><th>Category</th><th>Description</th><th>Status</th></tr></thead>
        <tbody>
        <?php while ($row = $resCategories->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><strong><?= htmlspecialchars($row['icon'] . ' ' . $row['name']) ?></strong></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>
              <span class="badge <?= $row['status'] === 'Active' ? 'badge-green' : 'badge-gray' ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="section container" id="products">
    <h2>Products</h2>
    <p class="section-sub">Menu favorit lengkap dengan kategori, harga, dan ketersediaan stok.</p>
    <div class="table-wrap card">
      <table>
        <thead><tr><th>ID</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th></tr></thead>
        <tbody>
        <?php while ($row = $resProducts->fetch_assoc()) {
            if ($row['stock'] == 0)      { $cls = 'badge-red';    $txt = 'Sold out'; }
            elseif ($row['stock'] <= 5)  { $cls = 'badge-orange'; $txt = 'Low · ' . $row['stock']; }
            else                         { $cls = 'badge-green';  $txt = $row['stock'] . ' in stock'; }
        ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= rupiah($row['price']) ?></td>
            <td><span class="badge <?= $cls ?>"><?= $txt ?></span></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="section container" id="sales">
    <h2>Sales</h2>
    <p class="section-sub">Riwayat transaksi penjualan terbaru.</p>
    <div class="table-wrap card">
      <table>
        <thead><tr><th>ID</th><th>Product</th><th>Sale Date</th><th>Quantity</th><th>Total Price</th></tr></thead>
        <tbody>
        <?php while ($row = $resSales->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><strong><?= htmlspecialchars($row['product']) ?></strong></td>
            <td><?= date('d M Y', strtotime($row['sale_date'])) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= rupiah($row['total_price']) ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </section>
</main>

<footer class="footer">
  <p>© <?= date('Y') ?> Bake'n Brew · Cafe &amp; Bakery — dibuat dengan PHP Native &amp; MySQL</p>
</footer>

<button class="to-top" id="toTop" aria-label="Back to top">↑</button>

<script>
  const navToggle = document.getElementById('navToggle');
  const navLinks  = document.getElementById('navLinks');
  const toTop     = document.getElementById('toTop');

  navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
  navLinks.querySelectorAll('a').forEach(a =>
    a.addEventListener('click', () => navLinks.classList.remove('open')));

  window.addEventListener('scroll', () => {
    toTop.classList.toggle('show', window.scrollY > 400);
  });
  toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>
</body>
</html>
