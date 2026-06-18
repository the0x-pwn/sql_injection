<!-- Navbar -->
<nav class="navbar-custom d-flex justify-content-between align-items-center">
    <div class="brand">SQL Injection Lab</div>
    <div class="d-flex align-items-center gap-3">
        <span class="nav-badge"><?= count($products) ?> Products</span>
        <button class="btn-nav-login" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
        <button class="btn-nav-register" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
    </div>
</nav>
<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash-wrap" id="flashMessage">
        <div class="flash-box flash-<?= htmlspecialchars($_SESSION['flash']['type']) ?>">
            <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
        </div>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
<!-- Hero -->
<section class="hero">
    <div class="hero-label">🧪 Training Lab</div>
    <h1>Explore <span>Vulnerable Products</span><br />Practice SQL Injection Here</h1>
    <p>A deliberately vulnerable product listing — find the injection points.</p>
</section>

<!-- Search -->
<form action="/search" method="get">
    <div class="search-wrap">
        <div class="search-input-row">
            <input type="text" name="query" id="searchInput" placeholder="Search a product..." />
            <button class="btn-search" type="submit">Search</button>
        </div>
        <div class="search-hint">Try searching: <span>Headphones</span>, <span>Camera</span>, <span>Laptop</span></div>
        <?php if (isset($_SESSION['flash_search'])): ?>
            <div class="flash-wrap" id="flashMessage">
                <div class="p-1 m-2 flash-box flash-<?= htmlspecialchars($_SESSION['flash_search']['type']) ?>">
                    <?= htmlspecialchars($_SESSION['flash_search']['msg']) ?>
                </div>
            </div>
            <?php unset($_SESSION['flash_search']); ?>
        <?php endif; ?>
    </div>
</form>

<!-- Products -->
<section class="products-section">
    <div class="row g-4" id="productsGrid">
        <?php foreach ($products as $index => $product): ?>
            <div class="col-12 col-md-6 col-lg-4 product-col" data-name="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-card">
                    <div class="product-img-wrap">
                        <img src="/img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                        <?php if (!empty($product['badge']) && $product['badge'] !== 'NULL'): ?>
                            <span class="product-badge"><?= htmlspecialchars($product['badge']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <div class="product-category"><?= htmlspecialchars($product['category']) ?></div>
                        <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                        <div class="product-desc"><?= htmlspecialchars($product['description']) ?></div>
                        <div class="product-footer">
                            <div class="product-price">$<?= number_format($product['price'], 0) ?></div>
                            <a href="/product?id=<?= $product['id'] ?>" class="btn-view">View Product</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lab-modal">
            <div class="modal-header lab-modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="modal-img-wrap">
                    <img id="modalImg" src="" alt="" />
                </div>
                <div class="modal-price" id="modalPrice"></div>
                <p class="modal-desc" id="modalDesc"></p>
                <div class="modal-meta" id="modalMeta"></div>
                <button class="btn-search w-100 mt-2">🛒 Add to Cart</button>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lab-modal">
            <div class="modal-header lab-modal-header">
                <h5 class="modal-title">🔐 Login</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php if (!empty($loginError)): ?>
                    <div class="lab-alert"><?= htmlspecialchars($loginError) ?></div>
                <?php endif; ?>
                <form method="POST" action="/login" id="form">
                    <div class="lab-form-group">
                        <label class="lab-label">Username</label>
                        <input type="text" name="username" id="username" class="lab-input" placeholder="Enter username" required />
                        <div id="username-msg" class="text-danger small mt-1 fs-8 d-none">
                            Username must be less than 8 characters
                        </div>
                    </div>
                    <div class="lab-form-group">
                        <label class="lab-label">Password</label>
                        <input type="password" id="password" name="password" class="lab-input" placeholder="Enter password" required />
                        <div id="password-msg" class="text-danger small mt-1 fs-8 d-none">
                            Password must be less than 8 characters
                        </div>
                    </div>
                    <button type="submit" class="btn-search w-100 mt-2" onclick="getValue()">Login</button>
                </form>
                <p class="lab-switch-text mt-3">
                    No account?
                    <a href="#" class="lab-link" onclick="switchModal('loginModal','registerModal')">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lab-modal">
            <div class="modal-header lab-modal-header">
                <h5 class="modal-title">📝 Register</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php if (!empty($registerError)): ?>
                    <div class="lab-alert"><?= htmlspecialchars($registerError) ?></div>
                <?php endif; ?>
                <form method="POST" action="/register">
                    <div class="lab-form-group">
                        <label class="lab-label">Username</label>
                        <input type="text" name="username" class="lab-input" placeholder="Choose a username" required />
                    </div>
                    <div class="lab-form-group">
                        <label class="lab-label">Password</label>
                        <input type="password" name="password" class="lab-input" placeholder="Choose a password" required />
                    </div>
                    <div class="lab-form-group">
                        <label class="lab-label">Confirm Password</label>
                        <input type="password" name="password_confirm" class="lab-input" placeholder="Repeat your password" required />
                    </div>
                    <button type="submit" class="btn-search w-100 mt-2">Create Account</button>
                </form>
                <p class="lab-switch-text mt-3">
                    Already have an account?
                    <a href="#" class="lab-link" onclick="switchModal('registerModal','loginModal')">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-nav-login {
        background: transparent;
        border: 1px solid var(--accent);
        color: #a78bfa;
        padding: 7px 20px;
        border-radius: 10px;
        font-family: 'Cairo', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-nav-login:hover {
        background: rgba(124, 58, 237, 0.12);
        border-color: #a78bfa;
    }

    .btn-nav-register {
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        border: none;
        color: white;
        padding: 7px 20px;
        border-radius: 10px;
        font-family: 'Cairo', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
        box-shadow: 0 4px 20px rgba(124, 58, 237, 0.3);
    }

    .btn-nav-register:hover {
        opacity: 0.88;
        transform: translateY(-1px);
    }

    .lab-modal {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 20px;
        color: var(--text);
    }

    .lab-modal-header {
        border-bottom: 1px solid var(--border);
        padding: 20px 24px;
    }

    .lab-modal-header .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--text);
    }

    .lab-form-group {
        margin-bottom: 16px;
    }

    .lab-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--muted);
        margin-bottom: 7px;
    }

    .lab-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
        font-family: 'Cairo', sans-serif;
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .lab-input:focus {
        border-color: rgba(124, 58, 237, 0.6);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    .lab-input::placeholder {
        color: var(--muted);
    }

    .lab-alert {
        padding: 10px 16px;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #f87171;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .lab-alert.success {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.25);
        color: #4ade80;
    }

    .lab-switch-text {
        text-align: center;
        font-size: 0.82rem;
        color: var(--muted);
        margin-bottom: 0;
    }

    .lab-link {
        color: #a78bfa;
        text-decoration: none;
        font-weight: 700;
    }

    .lab-link:hover {
        color: var(--accent2);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const products = <?= json_encode(array_map(function ($p) {
                            return [
                                'name'  => $p['name'],
                                'price' => '$' . number_format($p['price'], 0),
                                'img'   => '/img/' . $p['img'],
                                'desc'  => $p['description'],
                                'badge' => $p['badge'] ?? '',
                                'tags'  => [$p['category'], $p['badge'] ?? ''],
                            ];
                        }, $products), JSON_UNESCAPED_UNICODE) ?>;

    function openModal(index) {
        const p = products[index];
        document.getElementById('modalTitle').textContent = p.name;
        document.getElementById('modalPrice').textContent = p.price;
        document.getElementById('modalImg').src = p.img;
        document.getElementById('modalImg').alt = p.name;
        document.getElementById('modalDesc').textContent = p.desc;
        document.getElementById('modalMeta').innerHTML = p.tags
            .filter(t => t && t !== 'NULL')
            .map(t => `<span>${t}</span>`).join('');
        new bootstrap.Modal(document.getElementById('productModal')).show();
    }

    function switchModal(closeId, openId) {
        bootstrap.Modal.getInstance(document.getElementById(closeId)).hide();
        setTimeout(() => new bootstrap.Modal(document.getElementById(openId)).show(), 300);
    }
</script>