    <!-- Navbar -->
    <nav class="navbar-custom d-flex justify-content-between align-items-center">
        <div class="brand">SQL Injection Lab</div>
        <div class="d-flex align-items-center gap-3">
            <span class="nav-badge">Product Details</span>
        </div>
    </nav>

    <!-- Product Page -->
    <section class="product-page">
        <?php foreach ($products as $product): ?>
            <!-- Breadcrumb -->
            <nav class="breadcrumb-wrap">
                <a href="/">Products</a>
                <span>›</span>
                <span><?= htmlspecialchars($product['name']) ?></span>
            </nav>

            <div class="product-wrap">

                <!-- Image -->
                <div class="product-img-box">
                    <img src="/img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                    <?php if (!empty($product['badge']) && $product['badge'] !== 'NULL'): ?>
                        <span class="product-badge"><?= htmlspecialchars($product['badge']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Details -->
                <div class="product-details">
                    <div class="product-category-label"><?= htmlspecialchars($product['category']) ?></div>
                    <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                    <div class="product-price-big">$<?= number_format($product['price'], 2) ?></div>
                    <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>

                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="meta-label">Category</span>
                            <span class="meta-value"><?= htmlspecialchars($product['category']) ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Product ID</span>
                            <span class="meta-value">#<?= $product['id'] ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="meta-value status-in">In Stock</span>
                        </div>
                    </div>

                    <a href="/" class="btn-back">← Back to Products</a>
                </div>

            </div>
        <?php endforeach; ?>
    </section>
