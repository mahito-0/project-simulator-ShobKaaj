<?php
require_once '../../../Shared/MVC/db/config.php';
session_start();

// Basic security: Ensure only admins can access this API
// In a real app, strict session role checking would be here.
// For now, we trust the JS frontend checks + session exists
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

class AdminAPI
{
    var $db;

    function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    function sendResponse($status, $message, $data = [])
    {
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
        exit;
    }

    function handleRequest()
    {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'get_stats':
                $this->getStats();
                break;
            case 'get_users':
                $this->getUsers();
                break;
            case 'get_jobs':
                $this->getJobs();
                break;
            case 'delete_job':
                $this->deleteJob();
                break;
            case 'update_status':
                $this->updateStatus();
                break;
            default:
                $this->sendResponse('error', 'Invalid Action');
        }
    }

    function getStats()
    {
        $stats = [
            'total_users' => 0,
            'verified_users' => 0,
            'terminated_users' => 0,
            'total_jobs' => 0
        ];

        // Total Users
        $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE role != 'admin'");
        if ($res) $stats['total_users'] = $res->fetch_assoc()['c'];

        // Verified Users
        $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE role != 'admin' AND is_verified = 'verified'");
        if ($res) $stats['verified_users'] = $res->fetch_assoc()['c'];

        // Terminated Users
        $res = $this->db->query("SELECT COUNT(*) as c FROM users WHERE role != 'admin' AND status = 'terminated'");
        if ($res) $stats['terminated_users'] = $res->fetch_assoc()['c'];

        // Total Jobs
        $res = $this->db->query("SELECT COUNT(*) as c FROM jobs");
        if ($res) $stats['total_jobs'] = $res->fetch_assoc()['c'];

        $this->sendResponse('success', 'Stats fetched', ['stats' => $stats]);
    }

    function getUsers()
    {
        $sql = "SELECT id, first_name, last_name, email, role, is_verified, status FROM users WHERE role != 'admin' ORDER BY id DESC";
        $result = $this->db->query($sql);
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $this->sendResponse('success', 'Users fetched', ['users' => $users]);
    }

    function getJobs()
    {
        // Join with users to get client name
        $sql = "SELECT j.id, j.title, j.budget, j.status, j.created_at, 
                       u.first_name, u.last_name 
                FROM jobs j 
                JOIN users u ON j.client_id = u.id 
                ORDER BY j.created_at DESC";

        $result = $this->db->query($sql);
        $jobs = $result->fetch_all(MYSQLI_ASSOC);
        $this->sendResponse('success', 'Jobs fetched', ['jobs' => $jobs]);
    }

    function deleteJob()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $jobId = $data['job_id'] ?? null;

        if (!$jobId) {
            $this->sendResponse('error', 'Job ID required');
        }

        // Ideally, check for dependencies (applications) and delete them first or use ON DELETE CASCADE
        // For now, assuming ON DELETE CASCADE or simply trying delete.

        $stmt = $this->db->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $jobId);

        if ($stmt->execute()) {
            $this->sendResponse('success', 'Job deleted successfully');
        } else {
            $this->sendResponse('error', 'Failed to delete job');
        }
    }

    function updateStatus()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'] ?? null;
        $type = $data['type'] ?? null;

        if (!$userId || !$type) {
            $this->sendResponse('error', 'Missing parameters');
        }

        $sql = "";

        if ($type === 'verify') {
            $sql = "UPDATE users SET is_verified = 'verified' WHERE id = ?";
        } elseif ($type === 'unverify') {
            $sql = "UPDATE users SET is_verified = 'unverified' WHERE id = ?";
        } elseif ($type === 'terminate') {
            $sql = "UPDATE users SET status = 'terminated' WHERE id = ?";
        } elseif ($type === 'activate') {
            $sql = "UPDATE users SET status = 'active' WHERE id = ?";
        } else {
            $this->sendResponse('error', 'Invalid type');
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $this->sendResponse('error', 'Prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $this->sendResponse('success', 'User updated successfully');
        } else {
            $this->sendResponse('error', 'Database error: ' . $stmt->error);
        }
    }
}

header('Content-Type: application/json');
$api = new AdminAPI();
$api->handleRequest();
