<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
         .btn-indigo {
        background-color: #6366f1 !important;
        border-color: #6366f1 !important;
        color: #ffffff !important;
    }

    .btn-indigo:hover {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #ffffff !important;
    }



        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background-color: #1f2937;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #374151;
            color: #ffffff;
        }

        .topbar {
            background-color: #111827;
            height: 56px;
        }

        .content {
            padding: 30px;
        }

        .card {
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .filter-links a {
            margin-right: 15px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .filter-links a:hover {
            text-decoration: underline;
        }

        .bg-indigo {
    background-color: #6366f1;
    color: #fff;
        }

        .btn-indigo {
    background-color: #6366f1;
    border-color: #6366f1;
    color: #ffffff;
       }

.btn-indigo:hover {
    background-color: #4f46e5;
    border-color: #4f46e5;
    color: #ffffff;
}

    </style>
</head>
<body>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-white fw-bold p-3 border-bottom">
            🐬 Dolphin CRM
        </div>

        <a href="dashboard.php" class="active">
            <i class="bi bi-house me-2"></i> Home
        </a>
        <a href="new_contact.php">
            <i class="bi bi-person-plus me-2"></i> New Contact
        </a>
        <a href="users.php">
            <i class="bi bi-people me-2"></i> Users
        </a>
        <a href="logout.php">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>

    <!-- Main area -->
    <div class="flex-grow-1">
        <div class="topbar d-flex align-items-center px-4 text-white">
            
        </div>

