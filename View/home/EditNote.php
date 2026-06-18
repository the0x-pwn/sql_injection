<?php if (isset($_SESSION['login']) && isset($_SESSION['id'])): ?>
    <!-- Navbar -->
    <nav class="navbar-custom d-flex justify-content-between align-items-center">
        <div class="brand">SQL Injection Lab</div>
        <div class="d-flex align-items-center gap-3">
            <span class="nav-badge">Edit Note</span>
            <a href="/profile?user_id=<?= $_SESSION['id'] ?>" class="btn-nav-logout">← Back</a>
        </div>
    </nav>

    <!-- Edit Note Page -->
    <section class="dashboard-page">

        <div class="dashboard-header">
            <div class="hero-label">📝 Note Editor</div>
            <h1>Edit <span>Note</span></h1>
            <p>Modify your note below and save changes.</p>
        </div>

        <div class="edit-note-wrap">
            <div class="dash-card">
                <div class="dash-card-header">
                    <span class="dash-card-icon">✏️</span>
                    <span class="dash-card-title">Edit Note (Second-Order)</span>
                </div>
                <div class="dash-card-body">

                    <?php if (isset($_SESSION['flash'])): ?>
                        <div class="alert-box alert-<?= $_SESSION['flash']['type'] ?>">
                            <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
                        </div>
                        <?php unset($_SESSION['flash']); ?>
                    <?php endif; ?>

                    <form action="/notes/update" method="POST" class="edit-note-form">
                        <input type="hidden" name="note_id" value="<?= htmlspecialchars($note['id']) ?>">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($note['user_id']) ?>">

                        <div class="form-group">
                            <label>Note Content</label>
                            <textarea name="note" class="form-textarea" rows="4" placeholder="Write your note..."><?= htmlspecialchars($note['note']) ?></textarea>
                        </div>

                        <!-- Note Info -->
                        <div class="note-meta">
                            <div class="meta-item">
                                <span class="meta-label">Note ID</span>
                                <span class="meta-value">#<?= htmlspecialchars($note['id']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Created At</span>
                                <span class="meta-value meta-mono"><?= htmlspecialchars($note['created_at']) ?></span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="/profile?user_id=<?= $_SESSION['id'] ?>" class="btn-cancel">✕ Cancel</a>
                            <button type="submit" class="btn-update">💾 Save Changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </section>

    <style>
        .dashboard-page {
            position: relative;
            z-index: 1;
            padding: 60px 20px 100px;
            max-width: 700px;
            margin: 0 auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeUp 0.5s ease both;
        }

        .dashboard-header h1 {
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            font-weight: 900;
            margin: 16px 0 10px;
        }

        .dashboard-header h1 span {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dashboard-header p {
            color: var(--muted);
            font-size: 1rem;
        }

        .edit-note-wrap {
            animation: fadeUp 0.5s ease both;
        }

        .dash-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .dash-card:hover {
            border-color: rgba(124, 58, 237, 0.35);
            box-shadow: 0 20px 60px rgba(124, 58, 237, 0.1);
        }

        .dash-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }

        .dash-card-icon {
            font-size: 1.1rem;
        }

        .dash-card-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #a78bfa;
            letter-spacing: 0.5px;
        }

        .dash-card-body {
            padding: 24px 22px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .edit-note-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-textarea {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: 'Cairo', sans-serif;
            outline: none;
            resize: vertical;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .form-textarea:focus {
            border-color: rgba(124, 58, 237, 0.5);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .note-meta {
            display: flex;
            gap: 24px;
            padding: 12px 16px;
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .meta-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-size: 0.88rem;
            color: var(--text);
        }

        .meta-mono {
            font-family: monospace;
            font-size: 0.78rem !important;
            color: var(--accent2) !important;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 4px;
        }

        .btn-cancel {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            border-color: rgba(124, 58, 237, 0.3);
            color: var(--text);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border: none;
            border-radius: 12px;
            padding: 10px 28px;
            color: #fff;
            font-family: 'Cairo', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
        }

        .btn-update:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }

        .btn-nav-logout {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            padding: 7px 20px;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .alert-box {
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }
    </style>

<?php else: ?>
    <?php header('Location:' . '/');
    exit() ?>
<?php endif; ?>
