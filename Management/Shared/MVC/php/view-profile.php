<?php
require_once '../db/config.php';
session_start();

$id = $_GET['id'] ?? null;
$error = null;
$user = null;
$stats = [];
$reviews = [];

if ($id) {
    try {
        global $db;

        $stmt = $db->prepare("SELECT id, first_name, last_name, email, phone, role, avatar, skills FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
        
            if (empty($user['avatar'])) {
                
                $user['avatar'] = '/project-simulator-ShobKaaj/Management/Shared/MVC/images/logo.png';
            }
            


            if ($user['role'] === 'worker') {
            
                $sqlStats = "SELECT 
                    (SELECT COUNT(*) FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE applications.worker_id = ? AND jobs.status = 'completed') as completed_jobs,
                    (SELECT COALESCE(SUM(bid_amount), 0) FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE applications.worker_id = ? AND jobs.status = 'completed') as total_earnings,
                    (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE reviewee_id = ?) as rating";
                $stmtStats = $db->prepare($sqlStats);
                $stmtStats->execute([$id, $id, $id]);
                $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

                
                $sqlReviews = "SELECT r.rating, r.comment, r.created_at, j.title as job_title, u.first_name as reviewer_name 
                               FROM reviews r 
                               JOIN jobs j ON r.job_id = j.id
                               JOIN users u ON r.reviewer_id = u.id
                               WHERE r.reviewee_id = ? 
                               ORDER BY r.created_at DESC LIMIT 10";
                $stmtReviews = $db->prepare($sqlReviews);
                $stmtReviews->execute([$id]);
                $reviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);
            } else {
            
                $sqlStats = "SELECT 
                    (SELECT COUNT(*) FROM jobs WHERE client_id = ?) as jobs_posted,
                     (SELECT COALESCE(SUM(budget), 0) FROM jobs WHERE client_id = ? AND status = 'completed') as total_spent";
                $stmtStats = $db->prepare($sqlStats);
                $stmtStats->execute([$id, $id]);
                $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
            }
        } else {
            $error = "User not found.";
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
} else {
    $error = "No user ID specified.";
}

require_once '../html/view-profile.php';
