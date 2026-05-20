<?php
/**
 * Reusable Components
 * includes/components.php
 */

/**
 * Render hero section
 */
function render_hero_section($title, $description, $background_image, $badge = null) {
    ?>
    <section class="hero-section" style="background-image: url('<?php echo e($background_image); ?>');">
        <div class="hero-background" style="background-image: url('<?php echo e($background_image); ?>');">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <?php if ($badge): ?>
                <span class="hero-badge"><?php echo e($badge); ?></span>
            <?php endif; ?>
            <h1 class="hero-title"><?php echo e($title); ?></h1>
            <p class="hero-description"><?php echo e($description); ?></p>
        </div>
    </section>
    <?php
}

/**
 * Render value card
 */
function render_value_card($icon, $title, $description, $icon_type = 'quality') {
    ?>
    <div class="value-card">
        <div class="value-icon <?php echo e($icon_type); ?>">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;"><?php echo e($icon); ?></span>
        </div>
        <h3 class="value-title"><?php echo e($title); ?></h3>
        <p class="value-description"><?php echo e($description); ?></p>
    </div>
    <?php
}

/**
 * Render product card
 */
function render_product_card($title, $description, $image_url) {
    ?>
    <div class="product-card">
        <div class="product-image-container">
            <img src="<?php echo e($image_url); ?>" alt="<?php echo e($title); ?>" class="product-image"/>
            <div class="product-overlay"></div>
        </div>
        <div class="product-content">
            <h3 class="product-title"><?php echo e($title); ?></h3>
            <p class="product-description"><?php echo e($description); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Render stat card
 */
function render_stat_card($number, $label) {
    ?>
    <div class="stat-card">
        <div class="stat-number" data-count="<?php echo e($number); ?>">0</div>
        <div class="stat-label"><?php echo e($label); ?></div>
    </div>
    <?php
}

/**
 * Render testimonial card
 */
function render_testimonial_card($name, $role, $message, $image_url = null) {
    ?>
    <div class="testimonial-card">
        <?php if ($image_url): ?>
            <img src="<?php echo e($image_url); ?>" alt="<?php echo e($name); ?>" class="testimonial-avatar"/>
        <?php endif; ?>
        <p class="testimonial-message">"<?php echo e($message); ?>"</p>
        <h4 class="testimonial-name"><?php echo e($name); ?></h4>
        <p class="testimonial-role"><?php echo e($role); ?></p>
    </div>
    <?php
}

/**
 * Render button
 */
function render_button($label, $href = '#', $type = 'primary', $attributes = '') {
    $class = "btn btn-$type";
    ?>
    <a href="<?php echo e($href); ?>" class="<?php echo $class; ?>" <?php echo $attributes; ?>>
        <?php echo e($label); ?>
    </a>
    <?php
}

/**
 * Render form input
 */
function render_input($name, $label, $type = 'text', $required = false, $placeholder = '') {
    ?>
    <div class="form-group">
        <label for="<?php echo e($name); ?>" class="form-label">
            <?php echo e($label); ?>
            <?php if ($required): ?>
                <span class="required">*</span>
            <?php endif; ?>
        </label>
        <input 
            type="<?php echo e($type); ?>" 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>" 
            class="form-input"
            placeholder="<?php echo e($placeholder); ?>"
            <?php if ($required): echo 'required'; endif; ?>
        />
    </div>
    <?php
}

/**
 * Render form textarea
 */
function render_textarea($name, $label, $required = false, $placeholder = '', $rows = 4) {
    ?>
    <div class="form-group">
        <label for="<?php echo e($name); ?>" class="form-label">
            <?php echo e($label); ?>
            <?php if ($required): ?>
                <span class="required">*</span>
            <?php endif; ?>
        </label>
        <textarea 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>" 
            class="form-textarea"
            placeholder="<?php echo e($placeholder); ?>"
            rows="<?php echo e($rows); ?>"
            <?php if ($required): echo 'required'; endif; ?>
        ></textarea>
    </div>
    <?php
}

/**
 * Render form select
 */
function render_select($name, $label, $options, $required = false) {
    ?>
    <div class="form-group">
        <label for="<?php echo e($name); ?>" class="form-label">
            <?php echo e($label); ?>
            <?php if ($required): ?>
                <span class="required">*</span>
            <?php endif; ?>
        </label>
        <select 
            id="<?php echo e($name); ?>" 
            name="<?php echo e($name); ?>" 
            class="form-select"
            <?php if ($required): echo 'required'; endif; ?>
        >
            <option value="">-- Pilih --</option>
            <?php foreach ($options as $value => $label): ?>
                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

/**
 * Render alert
 */
function render_alert($message, $type = 'info') {
    ?>
    <div class="alert alert-<?php echo e($type); ?>" role="alert">
        <span class="alert-icon">
            <?php
            $icons = [
                'success' => 'check_circle',
                'error' => 'error',
                'warning' => 'warning',
                'info' => 'info'
            ];
            $icon = $icons[$type] ?? 'info';
            ?>
            <span class="material-symbols-outlined"><?php echo $icon; ?></span>
        </span>
        <span class="alert-message"><?php echo e($message); ?></span>
        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <?php
}

/**
 * Render flash message from session
 */
function render_flash_message() {
    $flash = get_flash();
    if ($flash) {
        render_alert($flash['message'], $flash['type']);
    }
}

/**
 * Render pagination
 */
function render_pagination($current_page, $total_pages, $base_url) {
    if ($total_pages <= 1) return;
    
    ?>
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="<?php echo e($base_url . '?page=1'); ?>" class="pagination-link">« Pertama</a>
            <a href="<?php echo e($base_url . '?page=' . ($current_page - 1)); ?>" class="pagination-link">‹ Sebelumnya</a>
        <?php endif; ?>
        
        <?php
        $start = max(1, $current_page - 2);
        $end = min($total_pages, $current_page + 2);
        
        for ($i = $start; $i <= $end; $i++):
            $class = $i == $current_page ? 'active' : '';
            ?>
            <a href="<?php echo e($base_url . '?page=' . $i); ?>" class="pagination-link <?php echo $class; ?>">
                <?php echo e($i); ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($current_page < $total_pages): ?>
            <a href="<?php echo e($base_url . '?page=' . ($current_page + 1)); ?>" class="pagination-link">Selanjutnya ›</a>
            <a href="<?php echo e($base_url . '?page=' . $total_pages); ?>" class="pagination-link">Terakhir »</a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render breadcrumb
 */
function render_breadcrumb($items = []) {
    ?>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <?php foreach ($items as $label => $url): ?>
                <li class="breadcrumb-item">
                    <?php if ($url): ?>
                        <a href="<?php echo e($url); ?>"><?php echo e($label); ?></a>
                    <?php else: ?>
                        <span><?php echo e($label); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php
}

?>
