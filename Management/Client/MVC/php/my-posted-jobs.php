<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    // Redirect non-clients
    header("Location: ../../../Shared/MVC/html/auth.php");
    exit;
}
require_once '../html/my-posted-jobs.php';
