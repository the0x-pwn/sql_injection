<?php if (isset($_SESSION['login']) && isset($_SESSION['id'])): ?>
    <!-- Navbar -->
    <nav class="navbar-custom d-flex justify-content-between align-items-center">
        <div class="brand">SQL Injection Lab</div>
        <div class="d-flex align-items-center gap-3">
            <span class="nav-badge">Dashboard</span>
            <form action="/logout" method="POST">
                <button type="submit" class="btn-nav-logout">⏻ Logout</button>
            </form>
        </div>

    </nav>
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert-box text-center alert-<?= $_SESSION['flash']['type'] ?>">
            <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    <!-- Dashboard -->
    <section class="dashboard-page">

        <!-- Welcome -->
        <div class="dashboard-header">
            <div class="hero-label">👤 User Profile</div>
            <h1>Welcome, <span><?= htmlspecialchars($user['username']) ?></span></h1>
            <p>Your account details are displayed below.</p>
        </div>

        <!-- Cards -->
        <div class="dashboard-grid">

            <!-- Profile Card -->
            <div class="dash-card">
                <div class="dash-card-header">
                    <span class="dash-card-icon">🧑</span>
                    <span class="dash-card-title">Profile Info</span>
                </div>
                <div class="dash-card-body">
                    <div class="meta-item">
                        <span class="meta-label">User ID</span>
                        <span class="meta-value">#<?= htmlspecialchars($user['id']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Username</span>
                        <span class="meta-value"><?= htmlspecialchars($user['username']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Password</span>
                        <span class="meta-value"><?= htmlspecialchars($user['password']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Member Since</span>
                        <span class="meta-value"><?= htmlspecialchars($user['created_at']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Last Updated</span>
                        <span class="meta-value"><?= htmlspecialchars($user['updated_at']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="meta-value status-in">● Active</span>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="dash-card">
                <div class="dash-card-header">
                    <span class="dash-card-icon">🔐</span>
                    <span class="dash-card-title">Security Info</span>
                </div>
                <div class="dash-card-body">
                    <div class="meta-item">
                        <span class="meta-label">Session ID</span>
                        <span class="meta-value meta-mono"><?= substr(session_id(), 0, 16) ?>...</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">IP Address</span>
                        <span class="meta-value meta-mono"><?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">User Agent</span>
                        <span class="meta-value meta-mono meta-truncate"><?= htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'], 0, 30)) ?>...</span>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Card -->
            <div class="dash-card edit-card" style="animation-delay: 0.2s;">
                <div class="dash-card-header">
                    <span class="dash-card-icon">✏️</span>
                    <span class="dash-card-title">Edit Profile (First-Order)</span>
                </div>
                <div class="dash-card-body">
                    <form action="/change-password" method="POST" class="edit-form">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="text" name="password" placeholder="Enter new password" class="form-input" autocomplete="off">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-update">🔄 Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="dash-card edit-card" style="animation-delay: 0.3s;">
                <div class="dash-card-header">
                    <span class="dash-card-icon">📝</span>
                    <span class="dash-card-title">My Notes (Second-Order)</span>
                </div>
                <div class="dash-card-body">

                    <!-- Add Note Form -->
                    <form action="/notes/add" method="POST" class="edit-form">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <div class="form-group">
                            <label>New Note</label>
                            <input type="text" name="note" placeholder="Write your note..." class="form-input" autocomplete="off">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-update">➕ Add</button>
                        </div>
                    </form>

                    <!-- Notes Table -->
                    <?php if (!empty($notes)): ?>
                        <table class="notes-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notes as $note): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($note['id']) ?></td>
                                        <td><?= htmlspecialchars($note['note']) ?></td>
                                        <td class="meta-mono"><?= htmlspecialchars($note['created_at']) ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="/notes/edit?note_id=<?= $note['id'] . '&' . 'user_id=' . $user['id'] ?>" class="btn-edit">✏️ Edit</a>
                                                <form action="/notes/delete" method="POST" style="display:inline;">
                                                    <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <button type="submit" class="btn-delete">🗑️ Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-notes">No notes yet. Add your first note above.</p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </section>

    <style>
        .dashboard-page {
            position: relative;
            z-index: 1;
            padding: 60px 20px 100px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 48px;
            animation: fadeUp 0.5s ease both;
        }

        .dashboard-header h1 {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        .dash-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .dash-card:hover {
            border-color: rgba(124, 58, 237, 0.35);
            box-shadow: 0 20px 60px rgba(124, 58, 237, 0.1);
        }

        .dash-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .edit-card {
            grid-column: 1 / -1;
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
            padding: 18px 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .meta-mono {
            font-family: monospace;
            font-size: 0.78rem !important;
            color: var(--accent2) !important;
        }

        .meta-truncate {
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            color: #f87171;
        }

        /* Edit Form */
        .edit-form {
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 16px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: 'Cairo', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .form-input:focus {
            border-color: rgba(124, 58, 237, 0.5);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .form-actions {
            display: flex;
            align-items: flex-end;
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
            white-space: nowrap;
        }

        .btn-update:hover {
            opacity: 0.85;
            transform: translateY(-1px);
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

        /* Notes Table */
        .notes-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .notes-table th {
            text-align: left;
            padding: 10px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }

        .notes-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }

        .notes-table tr:last-child td {
            border-bottom: none;
        }

        .notes-table tr:hover td {
            background: var(--surface);
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            background: rgba(124, 58, 237, 0.1);
            border: 1px solid rgba(124, 58, 237, 0.25);
            color: #a78bfa;
            padding: 5px 14px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: rgba(124, 58, 237, 0.2);
            border-color: rgba(124, 58, 237, 0.5);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            padding: 5px 14px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .no-notes {
            color: var(--muted);
            font-size: 0.88rem;
            text-align: center;
            padding: 20px 0;
        }
    </style>
<?php else: ?>
    <?php header('Location:' . '/');
    exit() ?>
<?php endif; ?>
