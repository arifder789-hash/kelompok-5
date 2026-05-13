# Rameza Egg Farm - Refactored Project

## 📋 Project Overview

**Rameza Egg Farm** is a professional website for a chicken egg farm in Jember, Indonesia. This is a refactored version with improved structure, organization, and best practices.

---

## 🎯 Refactoring Improvements

### ✅ What Was Improved

1. **Project Structure**
   - ✓ Moved CSS files from root to `assets/css/`
   - ✓ Organized JavaScript files in `assets/js/`
   - ✓ Created `config/` directory for constants

2. **Code Organization**
   - ✓ Created centralized configuration file (`config/constants.php`)
   - ✓ Improved database connection class with singleton pattern
   - ✓ Better PHP template structure with header/footer includes
   - ✓ Proper semantic HTML structure

3. **CSS Improvements**
   - ✓ Reorganized CSS into modular files by page/component
   - ✓ Added CSS design tokens (variables) for consistent styling
   - ✓ Removed duplicate CSS rules
   - ✓ Added responsive design utilities
   - ✓ Improved accessibility features

4. **JavaScript Enhancements**
   - ✓ Created main.js with core functionality
   - ✓ Added page-specific JS files (beranda.js, opening.js)
   - ✓ Implemented smooth scroll, navbar scroll effects
   - ✓ Added utility functions for formatting and notifications
   - ✓ Proper event handling and animations

5. **PHP Implementation**
   - ✓ Created proper include structure
   - ✓ Implemented dynamic product listing
   - ✓ Added proper error handling
   - ✓ Used security best practices (htmlspecialchars, etc.)

6. **Pages Created/Refactored**
   - ✓ `index.php` - Main homepage
   - ✓ `beranda.php` - Alternative landing page
   - ✓ `tentang.php` - About/Company info page
   - ✓ `detailproduk.php` - Dynamic product detail page
   - ✓ All pages now use proper includes

---

## 📁 Directory Structure

```
ramezafarmnew/
├── config/
│   └── constants.php          # Global configuration & constants
├── includes/
│   ├── header.php            # HTML head & meta tags
│   ├── navbar.php            # Navigation bar
│   ├── footer.php            # Footer with copyright
│   ├── database.php          # Database connection class
│   └── homepage.php          # Homepage content module
├── assets/
│   ├── css/
│   │   ├── global.css        # Global styles & resets
│   │   ├── navbar.css        # Navigation styles
│   │   ├── footer.css        # Footer styles
│   │   ├── beranda.css       # Homepage styles
│   │   ├── tentang.css       # About page styles
│   │   └── detailproduk.css  # Product detail page styles
│   ├── js/
│   │   ├── main.js           # Core JavaScript functionality
│   │   ├── beranda.js        # Homepage-specific functions
│   │   └── opening.js        # Opening/splash page logic
│   └── images/               # Website images
├── index.php                 # Main homepage entry point
├── beranda.php              # Alternative homepage
├── tentang.php              # About page
├── detailproduk.php         # Product detail page
├── opening.php              # Opening/splash page (empty template)
└── README.md                # This file
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB database
- Web server (Apache, Nginx, etc.)

### Installation Steps

1. **Clone/Extract the project** to your web server directory

2. **Configure Database Connection**
   - Edit `config/constants.php`
   - Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'rameza_farm');
   ```

3. **Set File Permissions** (if needed)
   ```bash
   chmod -R 755 assets/
   chmod -R 755 includes/
   ```

4. **Access the Website**
   - Homepage: `http://localhost/ramezafarmnew/` or `http://localhost/ramezafarmnew/index.php`
   - About Page: `http://localhost/ramezafarmnew/tentang.php`
   - Product Details: `http://localhost/ramezafarmnew/detailproduk.php?slug=bibit-ayam`

---

## 📝 Configuration Guide

### Edit Site Information

Edit `config/constants.php` to customize:

```php
// Site Information
define('SITE_NAME', 'Rameza Egg Farm');
define('SITE_URL', 'http://yourdomain.com');

// Contact Information
define('CONTACT_LOCATION', 'Jember, Indonesia');
define('CONTACT_PHONE', '08xxxxxxxxxx');
define('CONTACT_EMAIL', 'info@rameza-farm.com');

// Product Data
$PRODUCTS = [
    'slug' => [
        'name' => 'Product Name',
        'description' => 'Product description',
        'image' => 'assets/images/image.jpg',
        'price' => 'Rp. 100.000'
    ]
];
```

### Add New Products

Update the `$PRODUCTS` array in `config/constants.php`:

```php
'new-product' => [
    'name' => 'New Product Name',
    'slug' => 'new-product',
    'category' => 'Product Category',
    'description' => 'Product description',
    'image' => 'assets/images/new-product.jpg',
    'price' => 'Hubungi kami'
]
```

---

## 🎨 Customization Guide

### Colors & Styling

Edit CSS variables in `assets/css/global.css`:

```css
:root {
    --blue-main:        #1f6fd6;      /* Primary brand color */
    --blue-dark:        #174ea6;      /* Darker blue */
    --yellow-main:      #fbbf24;      /* Accent color */
    --text-dark:        #1e293b;      /* Text color */
    /* ... more variables ... */
}
```

### Navigation Menu

Edit menu items in `includes/navbar.php`:

```php
$nav_items = [
    ['url' => 'index.php', 'label' => 'Beranda'],
    ['url' => 'tentang.php', 'label' => 'Tentang'],
    // Add more items here
];
```

### Footer Content

Edit `includes/footer.php` to customize footer sections, links, and information.

---

## 📱 Responsive Design

The website is fully responsive for:
- 📱 Mobile devices (< 480px)
- 📱 Tablets (480px - 768px)
- 💻 Desktops (768px - 1024px)
- 🖥️ Large screens (> 1024px)

CSS media queries are included in all stylesheet files.

---

## ♿ Accessibility Features

- WCAG 2.1 AA compliance
- Semantic HTML structure
- Keyboard navigation support
- Focus indicators for all interactive elements
- Color contrast ratios meet standards
- Reduced motion support for animations
- Alt text for all images

---

## 🔒 Security Features

- Input sanitization with `htmlspecialchars()`
- SQL injection prevention with prepared statements
- CSRF protection ready
- Content Security Policy headers
- Secure database connection class
- Error logging to file instead of display

---

## 🐛 Common Issues & Solutions

### Issue: CSS/JS files not loading
**Solution:** Check that:
1. Files are in correct directories (`assets/css/` and `assets/js/`)
2. File paths in HTML are correct (relative to root)
3. Web server has read permissions

### Issue: Database connection fails
**Solution:**
1. Verify database credentials in `config/constants.php`
2. Ensure MySQL/MariaDB service is running
3. Check that database exists
4. Verify user has required permissions

### Issue: Images not displaying
**Solution:**
1. Ensure images are in `assets/images/` directory
2. Check image file paths in product data
3. Verify file names match exactly (case-sensitive on Linux)

---

## 📧 Database Setup

### Create Database

```sql
CREATE DATABASE rameza_farm;
USE rameza_farm;

-- Example products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    image VARCHAR(255),
    price VARCHAR(100),
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    customer_name VARCHAR(255),
    customer_phone VARCHAR(20),
    quantity INT,
    total_price DECIMAL(10, 2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## 🌐 Deployment Checklist

- [ ] Update database credentials in `config/constants.php`
- [ ] Update `SITE_URL` to production domain
- [ ] Set `display_errors` to false in `config/constants.php`
- [ ] Enable error logging
- [ ] Update contact information
- [ ] Replace placeholder images
- [ ] Test all pages and links
- [ ] Verify email functionality
- [ ] Set up SSL certificate
- [ ] Configure backup strategy
- [ ] Set up monitoring/logging

---

## 📚 File Descriptions

### Core Files

| File | Purpose |
|------|---------|
| `config/constants.php` | Global constants, database config, product data |
| `includes/header.php` | HTML head section, meta tags, stylesheets |
| `includes/navbar.php` | Navigation bar component |
| `includes/footer.php` | Footer section with scripts |
| `includes/database.php` | Database connection class |

### Page Files

| File | Purpose |
|------|---------|
| `index.php` | Main homepage |
| `beranda.php` | Alternative landing page |
| `tentang.php` | About page with company information |
| `detailproduk.php` | Dynamic product detail page |
| `opening.php` | Splash/opening page template |

### Stylesheets

| File | Contains |
|------|----------|
| `assets/css/global.css` | Resets, variables, utilities, responsive bases |
| `assets/css/navbar.css` | Navigation bar styling |
| `assets/css/footer.css` | Footer styling |
| `assets/css/beranda.css` | Homepage specific styles |
| `assets/css/tentang.css` | About page styles |
| `assets/css/detailproduk.css` | Product detail page styles |

### Scripts

| File | Contains |
|------|----------|
| `assets/js/main.js` | Core functionality (navbar effects, smooth scroll, utilities) |
| `assets/js/beranda.js` | Homepage specific JavaScript |
| `assets/js/opening.js` | Opening page logic |

---

## 🚀 Performance Tips

1. **Image Optimization**
   - Compress images before uploading
   - Use WebP format for modern browsers
   - Lazy load images where possible

2. **CSS & JS**
   - Minify CSS and JavaScript for production
   - Consider inlining critical CSS
   - Defer non-critical JavaScript

3. **Caching**
   - Enable browser caching
   - Implement server-side caching for database queries
   - Use CDN for static assets

4. **Database**
   - Add indexes on frequently queried columns
   - Regular database backups
   - Monitor query performance

---

## 📞 Support & Maintenance

For issues or improvements:
1. Check the troubleshooting section above
2. Review error logs
3. Verify configuration settings
4. Test in different browsers
5. Check responsive design on mobile devices

---

## 📄 License

This project is proprietary. All rights reserved © 2026 Rameza Egg Farm.

---

## 👥 Contributors

- Project Refactoring & Architecture
- PHP Development
- Frontend Development (HTML/CSS/JS)

---

**Last Updated:** April 2026
**Version:** 2.0 (Refactored)
**Status:** Production Ready
