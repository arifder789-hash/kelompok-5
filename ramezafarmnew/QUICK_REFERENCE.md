# Quick Reference Guide - Rameza Farm

## 📚 File Quick Links

### Configuration Files
| File | Fungsi |
|------|--------|
| `includes/config.php` | Global configuration & constants |
| `includes/init.php` | Master initialization |

### Utility Files
| File | Fungsi |
|------|--------|
| `includes/helpers.php` | Helper functions |
| `includes/components.php` | Reusable components |
| `includes/db-helper.php` | Database wrapper |

### CSS Files
| File | Untuk |
|------|-------|
| `assets/css/tailwind-config.css` | Global styles & variables |
| `assets/css/tentang.css` | About page styles |

### JavaScript Files
| File | Untuk |
|------|-------|
| `assets/js/rameza-config.js` | Global configuration |
| `assets/js/animations.js` | Animations & effects |
| `assets/js/form-handler.js` | Form handling |

---

## 🔥 Most Used Functions

```php
// URL Utilities
base_url('path')              // Generate full URL
asset_url('css/style.css')    // Asset path

// Output (XSS Safe)
e($text)                      // Escape HTML

// Formatting
format_currency($amount)      // Rp format
format_date($date)            // Indonesian date

// Input
sanitize($input)              // Clean input

// Validation
is_valid_email($email)        // Email check
is_valid_phone($phone)        // Phone check

// Database
$db->select($table, $where)   // SELECT
$db->insert($table, $data)    // INSERT
$db->update($table, $data)    // UPDATE
$db->delete($table, $where)   // DELETE

// Components
render_hero_section()         // Hero banner
render_product_card()         // Product card
render_input()                // Form input
render_button()               // Button
render_alert()                // Alert box
```

---

## 💾 Database Query Examples

```php
// Select all
$result = $db->select('products');

// Select with condition
$result = $db->select('products', ['status' => 'active']);

// Count
$total = $db->count('products', ['status' => 'active']);

// Paginate
$page = $db->paginate('products', 1, 10);
foreach($page['data'] as $product) {
    echo $product['name'];
}

// Insert
$db->insert('reviews', [
    'product_id' => 1,
    'rating' => 5,
    'comment' => 'Excellent!'
]);

// Update
$db->update('products', 
    ['stock' => 100],
    ['id' => 1]
);

// Delete
$db->delete('reviews', ['id' => 1]);
```

---

## 🎨 Component Examples

### Hero Section
```php
render_hero_section(
    'Main Title',
    'Description text',
    'https://image.url/background.jpg',
    'Badge Text'
);
```

### Product Card
```php
render_product_card(
    'Product Name',
    'Product description',
    'https://image.url/product.jpg'
);
```

### Form Input
```php
render_input(
    'field_name',      // name attribute
    'Label Text',      // label
    'text',            // type (text, email, number, etc)
    true,              // required
    'Placeholder'      // placeholder
);
```

### Alert Message
```php
render_alert('Message text', 'success');   // success
render_alert('Message text', 'error');     // error
render_alert('Message text', 'warning');   // warning
render_alert('Message text', 'info');      // info
```

---

## 📝 Page Template

```php
<?php require_once 'includes/init.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title - <?php echo SITE_NAME; ?></title>
    
    <link rel="stylesheet" href="<?php echo asset_url('css/tailwind-config.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/page.css'); ?>">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
    <!-- Your content here -->
</main>

<?php include 'includes/footer.php'; ?>

<script src="<?php echo asset_url('js/rameza-config.js'); ?>"></script>
<script src="<?php echo asset_url('js/animations.js'); ?>"></script>
</body>
</html>
```

---

## 🔗 CSS Variables

```css
/* Colors */
var(--color-primary)
var(--color-secondary)
var(--color-surface)
var(--color-on-surface)

/* Spacing */
var(--spacing-xs)    /* 4px */
var(--spacing-sm)    /* 12px */
var(--spacing-md)    /* 24px */
var(--spacing-lg)    /* 48px */
var(--spacing-xl)    /* 80px */

/* Border Radius */
var(--radius-default)  /* 0.5rem */
var(--radius-lg)       /* 1rem */
var(--radius-xl)       /* 1.5rem */
```

---

## 🚀 JavaScript API

```javascript
// Initialize (auto-run)
RamezaApp.init();

// Notify user
RamezaApp.notify('Message', 'success');

// Make API call
RamezaApp.apiCall('/api/endpoint', {
    method: 'POST',
    body: JSON.stringify({...})
}).then(response => {
    console.log(response);
});

// Animations
// Add data-animate attribute to auto-animate on scroll
// Add data-parallax="speed" for parallax effect
```

---

## 📱 Responsive Classes

```html
<!-- Hidden on mobile, visible on desktop -->
<div class="hidden md:block">Desktop only</div>

<!-- Visible on mobile, hidden on desktop -->
<div class="md:hidden">Mobile only</div>

<!-- Responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
```

---

## 🐛 Common Issues & Solutions

**Issue:** Functions not found
**Solution:** Add `require_once 'includes/init.php';` at top

**Issue:** Database errors
**Solution:** Check config.php credentials and database exists

**Issue:** CSS not loading
**Solution:** Use `asset_url()` instead of hardcoded paths

**Issue:** XSS vulnerability
**Solution:** Always use `e()` for user input output

---

## ⚡ Performance Tips

1. Use `select()` with WHERE clause to limit results
2. Use `paginate()` for large datasets
3. Cache frequently accessed data
4. Minify CSS/JS in production
5. Compress images before upload

---

## 📞 Key Constants

```php
SITE_NAME           // "Rameza Farm"
SITE_URL            // "http://localhost/ramezafarmnew"
SITE_SINCE          // 2015
ASSETS_PATH         // Full asset URL
CSS_PATH            // CSS directory URL
JS_PATH             // JS directory URL
DB_HOST             // Database host
DB_USER             // Database user
```

---

## 🎯 Next Steps

1. ✅ Structure created
2. ⏳ Migrate remaining pages
3. ⏳ Update existing pages to use new structure
4. ⏳ Test all functionality
5. ⏳ Optimize for production

