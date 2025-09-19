<?php
session_start();

// Initialize guestbook entries in session if not exists
if (!isset($_SESSION['guestbook_entries'])) {
    $_SESSION['guestbook_entries'] = [];
}

// Handle POST form submission
if ($_POST && isset($_POST['name']) && isset($_POST['message'])) {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    // Basic validation
    if (!empty($name) && !empty($message)) {
        $entry = [
            'id' => count($_SESSION['guestbook_entries']) + 1,
            'name' => htmlspecialchars($name),
            'message' => htmlspecialchars($message),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $_SESSION['guestbook_entries'][] = $entry;
        $success_message = "Thank you for signing our guestbook!";
    } else {
        $error_message = "Please fill in all fields.";
    }
}

// Handle GET filtering by user name
$filter_user = isset($_GET['user']) ? $_GET['user'] : '';
$filtered_entries = $_SESSION['guestbook_entries'];

if (!empty($filter_user)) {
    $filtered_entries = array_filter($_SESSION['guestbook_entries'], function ($entry) use ($filter_user) {
        return stripos($entry['name'], $filter_user) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capoy Guestbook</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #ecf0f1;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --border-color: #e8e8e8;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: var(--text-color);
            line-height: 1.6;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: #000000;
            color: white;
        }

        .btn-primary:hover {
            background: #000000;
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--accent-color);
            color: white;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        .entry-item {
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .entry-item:hover {
            background: rgba(52, 152, 219, 0.02);
            margin: 0 -1.5rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            border-radius: 8px;
        }

        .entry-item:last-child {
            border-bottom: none;
        }

        .display-4 {
            font-weight: 300;
            color: var(--primary-color);
        }

        .lead {
            color: #6c757d;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .text-center.py-4 {
            padding: 3rem 0;
        }

        .empty-state {
            opacity: 0.7;
        }

        .stats-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            padding: 0.75rem;
        }

        .filter-active {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(52, 152, 219, 0.05) 100%);
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-4">Welcome to gisbok</h1>
                    <p class="lead">Leave a message for people around the wooooooooooooooooorld</p>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- POST Form for Adding Entries -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Sign Our gisbok</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter your name" required maxlength="50">
                                    <div class="invalid-feedback">Please provide your name.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statistics</label>
                                    <div class="stats-box">
                                        <small class="text-muted">
                                            Total entries: <strong><?php echo count($_SESSION['guestbook_entries']); ?></strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3"
                                          placeholder="Write your message here..." required maxlength="500"></textarea>
                                <div class="invalid-feedback">Please provide a message.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Sign Guestbook</button>
                        </form>
                    </div>
                </div>

                <!-- GET Filter Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Filter Messages</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="" class="row g-3">
                            <div class="col-md-8">
                                <label for="user" class="form-label">Filter by User Name</label>
                                <input type="text" class="form-control" id="user" name="user"
                                       placeholder="Enter name to filter..."
                                value="<?php echo htmlspecialchars($filter_user); ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="?" class="btn btn-outline-dark">Clear</a>
                            </div>
                        </form>

                        <?php if (!empty($filter_user)): ?>
                            <div class="filter-active">
                                <small class="text-muted">
                                    Showing results for: <strong>"<?php echo htmlspecialchars($filter_user); ?>"</strong>
                                    (<?php echo count($filtered_entries); ?> entries found)
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Display Entries -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Guestbook Entries
                            <?php if (!empty($filter_user)): ?>
                                <small class="text-muted">(Filtered)</small>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($filtered_entries)): ?>
                            <?php if (!empty($filter_user)): ?>
                                <div class="text-center py-4 empty-state">
                                    <p class="text-muted mb-3">
                                        No entries found for "<?php echo htmlspecialchars($filter_user); ?>"
                                    </p>
                                    <a href="?" class="btn btn-outline-primary">View All Entries</a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 empty-state">
                                    <p class="text-muted mb-3">No entries yet. Be the first to sign our guestbook!</p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php foreach (array_reverse($filtered_entries) as $entry): ?>
                                <div class="entry-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0" style="color: var(--accent-color);">
                                            <?php echo $entry['name']; ?>
                                        </h6>
                                        <small class="text-muted"><?php echo $entry['timestamp']; ?></small>
                                    </div>
                                    <p class="mb-2 text-muted"><?php echo nl2br($entry['message']); ?></p>
                                    
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap form validation
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
