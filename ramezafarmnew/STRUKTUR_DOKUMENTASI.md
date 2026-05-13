# Struktur Organisasi Rameza Farm

## Pengenalan
Dokumentasi ini menjelaskan struktur terorganisir untuk project **Rameza Farm** yang menggunakan pemisahan CSS, JavaScript, dan PHP untuk maintainability dan reusability.

---

## 📁 Struktur Direktori

```
ramezafarmnew/
│
├── includes/                    # PHP Configuration & Utilities
│   ├── config.php              # Global configuration (database, paths, constants)
│   ├── init.php                # Master initialization file
│   ├── helpers.php             # Helper functions
│   ├── components.php          # Reusable PHP components
│   ├── db-helper.php           # Database utility class
│   ├── database.php            # (existing) Database connection
│   ├── header.php              # (existing) Header include
│   ├── footer.php              # (existing) Footer include
│   ├── navbar.php              # (existing) Navigation bar
│   └── homepage.php            # (existing) Homepage
│
├── assets/
│   │
│   ├── css/
│   │   ├── tailwind-config.css # Global Tailwind & color variables
│   │   ├── tentang.css         # About page specific styles
│   │   ├── beranda.css         # (existing) Homepage styles
│   │   ├── navbar.css          # (existing) Navigation styles
│   │   ├── footer.css          # (existing) Footer styles
│   │   ├── opening.css         # (existing) Opening page styles
│   │   └── detailproduk.css    # (existing) Product detail styles
│   │
│   ├── js/
│   │   ├── rameza-config.js    # Global app configuration & initialization
│   │   ├── animations.js       # Animation & scroll effects
│   │   ├── form-handler.js     # Form validation & submission
│   │   ├── beranda.js          # (existing) Homepage scripts
│   │   ├── navbar.js           # (existing) Navigation scripts
│   │   ├── footer.js           # (existing) Footer scripts
│   │   ├── opening.js          # (existing) Opening scripts
│   │   └── main.js             # (existing) Main scripts
│   │
│   └── img/
│       └── ...                 # Images
│
├── uploads/
│   └── reviews/                # User uploaded files
│
├── tentang.php                 # (existing) About page
├── tentang-baru.php            # NEW: About page using new structure
├── index.php                   # (existing) Homepage
├── beranda.php                 # (existing) Alternative homepage
├── detailproduk.php            # (existing) Product details
├── opening.php                 # (existing) Opening page
├── submit-review.php           # (existing) Review submission
├── database_ulasan.sql         # (existing) Database SQL
└── a.md                        # Original design template
```

---

## 🔧 File Configuration

### `includes/config.php`
**Fungsi:** Menyimpan semua konfigurasi global aplikasi

**Isi:**
- Database credentials
- Site URLs dan paths
- Color scheme constants
- Product categories
- Navigation items
- Social media links
- Company information

**Contoh Penggunaan:**
```php
require_once 'includes/config.php';

echo SITE_NAME;           // "Rameza Farm"
echo SITE_URL;            // "http://localhost/ramezafarmnew"
echo CSS_PATH;            // "http://localhost/ramezafarmnew/assets/css"
```

### `includes/helpers.php`
**Fungsi:** Menyediakan fungsi-fungsi utility yang sering digunakan

**Fungsi Utama:**
- `e()` - Escape HTML (XSS prevention)
- `base_url()` - Generate base URL
- `asset_url()` - Generate asset URL
- `format_currency()` - Format currency
- `format_date()` - Format tanggal Indonesia
- `redirect()` - Redirect halaman
- `is_valid_email()` - Validasi email
- `is_valid_phone()` - Validasi nomor telepon
- `sanitize()` - Sanitize input
- `truncate_text()` - Potong teks
- `log_message()` - Log ke file
- `api_response()` - Generate JSON response
- dan banyak lagi...

**Contoh Penggunaan:**
```php
require_once 'includes/helpers.php';

echo base_url('produk');                    // /produk
echo asset_url('css/style.css');           // /assets/css/style.css
echo format_currency(500000);              // Rp 500.000
echo format_date('2024-01-15');            // 15 Januari 2024
```

### `includes/components.php`
**Fungsi:** Menyediakan reusable PHP components untuk HTML

**Komponen Utama:**
- `render_hero_section()` - Hero section
- `render_value_card()` - Value card
- `render_product_card()` - Product card
- `render_stat_card()` - Statistics card
- `render_testimonial_card()` - Testimonial card
- `render_button()` - Button
- `render_input()` - Form input
- `render_textarea()` - Textarea
- `render_select()` - Select dropdown
- `render_alert()` - Alert message
- `render_pagination()` - Pagination
- `render_breadcrumb()` - Breadcrumb
- dan lainnya...

**Contoh Penggunaan:**
```php
require_once 'includes/components.php';

render_product_card(
    'Telur Segar',
    'Telur berkualitas tinggi...',
    'https://image.url/telur.jpg'
);

render_input('email', 'Email Anda', 'email', true, 'Masukkan email...');
```

### `includes/db-helper.php`
**Fungsi:** Database wrapper class untuk operasi query

**Metode Utama:**
- `select()` - SELECT query
- `insert()` - INSERT query
- `update()` - UPDATE query
- `delete()` - DELETE query
- `query()` - Custom query
- `count()` - Count rows
- `paginate()` - Pagination query

**Contoh Penggunaan:**
```php
require_once 'includes/db-helper.php';

$db = get_db();

// Select all users
$result = $db->select('users');

// Insert new product
$db->insert('products', [
    'name' => 'Telur Segar',
    'price' => 50000,
    'stock' => 100
]);

// Get paginated results
$result = $db->paginate('products', 1, 10);
```

### `includes/init.php`
**Fungsi:** Master initialization file

**Isi:**
- Session initialization
- Error reporting setup
- Timezone configuration
- Include semua file utility
- Database initialization
- Security headers
- Logging

**Penggunaan:**
```php
// Di awal setiap halaman PHP:
require_once 'includes/init.php';

// Sekarang semua helper dan components tersedia
```

---

## 🎨 CSS Files

### `assets/css/tailwind-config.css`
**Fungsi:** Global CSS variables dan Tailwind configuration

**Isi:**
- Color scheme (Material Design 3)
- Spacing variables
- Typography definitions
- Border radius utilities
- Font families

**Contoh Penggunaan:**
```css
/* Gunakan CSS variables */
.container {
    color: var(--color-primary);
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
}
```

### `assets/css/tentang.css`
**Fungsi:** Page-specific styles untuk halaman "Tentang Kami"

**Sections:**
- Hero section
- Story section
- Values section
- Products section
- Facilities section
- CTA section
- Responsive styles

---

## 🚀 JavaScript Files

### `assets/js/rameza-config.js`
**Fungsi:** Global app initialization dan configuration

**Features:**
- Global app object
- Event listeners setup
- Scroll animations
- Mobile menu toggle
- Notification system
- API call wrapper

**Contoh Penggunaan:**
```javascript
// Sudah auto-initialize saat DOM loaded

// Notify user
RamezaApp.notify('Berhasil!', 'success');

// Make API call
RamezaApp.apiCall('/api/products', {
    method: 'POST',
    body: JSON.stringify({...})
});
```

### `assets/js/animations.js`
**Fungsi:** Animation effects dan scroll interactions

**Features:**
- Scroll observer untuk fade-in animations
- Hover effects
- Parallax scrolling
- Counter animations
- Color transitions
- Pre-built animation keyframes

**Contoh Penggunaan:**
```html
<!-- Element akan auto-animate saat scroll -->
<div data-animate>Content akan fade in</div>

<!-- Parallax effect -->
<div data-parallax="0.5">Content dengan parallax</div>
```

### `assets/js/form-handler.js`
**Fungsi:** Form validation dan submission handling

**Features:**
- Real-time field validation
- Email validation
- Phone validation
- Form submission via AJAX
- Error state management
- Loading indicators

**Contoh Penggunaan:**
```html
<form data-form data-endpoint="/api/submit">
    <input type="email" required>
    <textarea required></textarea>
    <button type="submit">Submit</button>
</form>
```

---

## 📋 Cara Menggunakan

### 1. Halaman Baru Menggunakan Structure Baru

```php
<?php
require_once 'includes/init.php';

$page_title = 'Judul Halaman';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset_url('css/tailwind-config.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/page-name.css'); ?>">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
    <!-- Gunakan components -->
    <?php render_hero_section('Judul', 'Deskripsi', 'image.jpg'); ?>
</main>

<?php include 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="<?php echo asset_url('js/rameza-config.js'); ?>"></script>
<script src="<?php echo asset_url('js/animations.js'); ?>"></script>
</body>
</html>
```

### 2. Gunakan Helper Functions

```php
<?php
require_once 'includes/init.php';

// Redirect
if (!is_logged_in()) {
    redirect(base_url('login'));
}

// Format output
echo format_currency(100000);
echo format_date('2024-01-15');

// Sanitize input
$name = sanitize($_POST['name']);

// Validate
if (is_valid_email($_POST['email'])) {
    // Process...
}

// Database
$products = $db->select('products', ['category' => 'telur']);
?>
```

### 3. Gunakan Components

```php
<?php
render_input('name', 'Nama Lengkap', 'text', true);
render_textarea('message', 'Pesan Anda', true);
render_select('category', 'Kategori', ['telur' => 'Telur', 'pakan' => 'Pakan']);
render_button('Kirim', base_url('submit'));
render_alert('Berhasil disimpan!', 'success');
?>
```

---

## 🔐 Security Best Practices

1. **XSS Prevention**
   ```php
   // ALWAYS gunakan e() untuk output user input
   echo e($user_input);
   ```

2. **CSRF Protection**
   ```php
   $token = generate_csrf_token();
   // Verify dengan: verify_csrf_token($_POST['token'])
   ```

3. **Input Validation**
   ```php
   $email = sanitize($_POST['email']);
   if (!is_valid_email($email)) {
       // Handle error
   }
   ```

4. **API Responses**
   ```php
   // Return proper JSON
   echo api_response(true, 'Success', ['data' => $result]);
   ```

---

## 📊 Database Usage

```php
// Get database instance
$db = get_db();

// Select
$result = $db->select('products', ['status' => 'active']);

// Insert
$db->insert('reviews', [
    'product_id' => 1,
    'rating' => 5,
    'comment' => 'Bagus!'
]);

// Update
$db->update('products', 
    ['stock' => 50], 
    ['id' => 1]
);

// Delete
$db->delete('reviews', ['id' => 1]);

// Count
$total = $db->count('products');

// Paginate
$page_data = $db->paginate('products', 1, 10);
```

---

## 🎯 Checklist Migrasi

- [x] Create `includes/config.php`
- [x] Create `includes/helpers.php`
- [x] Create `includes/components.php`
- [x] Create `includes/db-helper.php`
- [x] Create `includes/init.php`
- [x] Create `assets/css/tailwind-config.css`
- [x] Update `assets/css/tentang.css`
- [x] Create `assets/js/rameza-config.js`
- [x] Create `assets/js/animations.js`
- [x] Create `assets/js/form-handler.js`
- [x] Create `tentang-baru.php` (template)
- [ ] Migrate other pages (beranda.php, index.php, etc.)
- [ ] Update existing includes
- [ ] Test all functionality

---

## 📞 Support

Untuk pertanyaan atau permasalahan, silakan hubungi tim development.

**Dibuat:** 2024
**Version:** 1.0
