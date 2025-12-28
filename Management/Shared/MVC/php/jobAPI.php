<?php
require_once '../db/config.php';
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

class JobAPI
{
    var $db;

    function __construct()
    {
        global $conn;
        $this->db = $conn;
        if (!$this->db) {
            $this->sendResponse('error', 'Database connection unavailable');
        }
    }

    function sendResponse($status, $message, $data = [])
    {
        $response = ['status' => $status, 'message' => $message];
        if (!empty($data)) {
            $response = array_merge($response, $data);
        }
        echo json_encode($response);
        exit;
    }

    function getInput($key)
    {
        return $_REQUEST[$key] ?? '';
    }

    function handleRequest()
    {
        $action = $this->getInput('action');

        switch ($action) {
            case 'list':
                $this->ListJobs();
                break;
            case 'get_worker_stats':
                $this->GetWorkerStats();
                break;
            case 'get_worker_history':
                $this->GetWorkerHistory();
                break;
            case 'get_worker_applications':
                $this->GetWorkerApplications();
                break;
            case 'my_jobs':
                $this->GetClientJobs();
                break;
            case 'get_client_applications':
                $this->GetClientApplications();
                break;
            case 'reject_application':
                $this->RejectApplication();
                break;
            case 'hire_worker':
                $this->HireWorker();
                break;
            case 'create':
                $this->CreateJob();
                break;
            default:
                $this->sendResponse('error', 'Invalid action');
        }
    }

    // Public Job Search
    function ListJobs()
    {
        $search = $this->getInput('search');
        $location = $this->getInput('location');
        $category = $this->getInput('category');

        $sql = "SELECT j.*, u.first_name, u.last_name, u.avatar 
                FROM jobs j 
                JOIN users u ON j.client_id = u.id 
                WHERE j.status = 'open'";

        $params = [];
        $types = "";

        if ($search) {
            $sql .= " AND (j.title LIKE ? OR j.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= "ss";
        }
        if ($location) {
            $sql .= " AND j.location LIKE ?";
            $params[] = "%$location%";
            $types .= "s";
        }
        if ($category && $category !== 'all') {
            $sql .= " AND j.category = ?";
            $params[] = $category;
            $types .= "s";
        }

        $sql .= " ORDER BY j.created_at DESC";

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $jobs = $result->fetch_all(MYSQLI_ASSOC);
        $this->sendResponse('success', 'Jobs fetched', ['jobs' => $jobs]);
    }

    // Worker Dashboard Stats
    function GetWorkerStats()
    {
        $workerId = $this->getInput('worker_id');
        if (!$workerId) $this->sendResponse('error', 'ID required');

        // Completed Jobs
        $sql1 = "SELECT COUNT(*) as count FROM applications a 
                 JOIN jobs j ON a.job_id = j.id 
                 WHERE a.worker_id = ? AND j.status = 'completed'";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param("i", $workerId);
        $stmt1->execute();
        $completed = $stmt1->get_result()->fetch_assoc()['count'];

        // Earnings
        $sql2 = "SELECT COALESCE(SUM(a.bid_amount), 0) as total FROM applications a 
                 JOIN jobs j ON a.job_id = j.id 
                 WHERE a.worker_id = ? AND j.status = 'completed'";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("i", $workerId);
        $stmt2->execute();
        $earnings = $stmt2->get_result()->fetch_assoc()['total'];

        // Rating
        $sql3 = "SELECT COALESCE(AVG(rating), 0) as avg_rating FROM reviews WHERE reviewee_id = ?";
        $stmt3 = $this->db->prepare($sql3);
        $stmt3->bind_param("i", $workerId);
        $stmt3->execute();
        $rating = number_format($stmt3->get_result()->fetch_assoc()['avg_rating'], 1);

        $this->sendResponse('success', 'Stats fetched', [
            'completed_jobs' => $completed,
            'earnings' => $earnings,
            'rating' => $rating
        ]);
    }

    // Worker Job History
    function GetWorkerHistory()
    {
        $workerId = $this->getInput('worker_id');

        $sql = "SELECT j.title, j.budget, u.first_name, u.last_name, j.client_id,
                (SELECT rating FROM reviews WHERE job_id = j.id AND reviewee_id = ?) as rating
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                JOIN users u ON j.client_id = u.id
                WHERE a.worker_id = ? AND j.status = 'completed'
                ORDER BY j.created_at DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt->bind_param("ii", $workerId, $workerId);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            if (!$result) {
                throw new Exception("Get result failed: " . $stmt->error);
            }
            $history = $result->fetch_all(MYSQLI_ASSOC);
            $this->sendResponse('success', 'History fetched', ['history' => $history]);
        } catch (Exception $e) {
            $this->sendResponse('error', 'History fetch failed: ' . $e->getMessage());
        }
    }

    // Worker Active Applications
    function GetWorkerApplications()
    {
        $workerId = $this->getInput('worker_id');

        $sql = "SELECT a.id, a.bid_amount, a.status, j.title as job_title, j.budget
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                WHERE a.worker_id = ? AND j.status != 'completed'
                ORDER BY a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $workerId);
        $stmt->execute();
        $apps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->sendResponse('success', 'Applications fetched', ['applications' => $apps]);
    }

    // Client Posted Jobs
    function GetClientJobs()
    {
        $clientId = $this->getInput('client_id');

        $sql = "SELECT * FROM jobs WHERE client_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->sendResponse('success', 'Jobs fetched', ['jobs' => $jobs]);
    }

    // Client Received Applications
    function GetClientApplications()
    {
        $clientId = $this->getInput('client_id');

        $sql = "SELECT a.id, a.job_id, a.worker_id, a.bid_amount, a.status, 
                       j.title as job_title, u.first_name, u.last_name, u.avatar
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                JOIN users u ON a.worker_id = u.id
                WHERE j.client_id = ? AND a.status != 'rejected'
                ORDER BY a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $apps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->sendResponse('success', 'Applications fetched', ['applications' => $apps]);
    }

    function HireWorker()
    {
        $appId = $this->getInput('application_id');
        $jobId = $this->getInput('job_id');

        // 1. Mark Application as Accepted
        $stmt = $this->db->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
        $stmt->bind_param("i", $appId);
        $stmt->execute();

        // 2. Mark Job as In Progress (or Assgined)
        $stmt2 = $this->db->prepare("UPDATE jobs SET status = 'in_progress', hired_worker_id = (SELECT worker_id FROM applications WHERE id = ?) WHERE id = ?");
        $stmt2->bind_param("ii", $appId, $jobId);

        if ($stmt2->execute()) {
            $this->sendResponse('success', 'Worker hired');
        } else {
            $this->sendResponse('error', 'Failed to hire');
        }
    }

    function RejectApplication()
    {
        $appId = $this->getInput('application_id');
        $stmt = $this->db->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $appId);

        if ($stmt->execute()) {
            $this->sendResponse('success', 'Application rejected');
        } else {
            $this->sendResponse('error', 'Failed to reject');
        }
    }

    function CreateJob()
    {
        $clientId = $this->getInput('client_id');
        $title = $this->getInput('title');
        $desc = $this->getInput('description');
        $budget = $this->getInput('budget');
        $category = $this->getInput('category');
        // Optional default location/deadline if not provided, or validation
        $location = $this->getInput('location');
        $deadline = $this->getInput('deadline');

        if (!$clientId || !$title || !$budget) {
            $this->sendResponse('error', 'Missing required fields');
        }

        // Handle potentially missing optional fields for the INSERT
        // Assuming database allows NULLs or we provide defaults. 
        // For now, let's just insert what we have.

        $sql = "INSERT INTO jobs (client_id, title, description, budget, category, location, deadline, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'open', NOW())";

        $stmt = $this->db->prepare($sql);
        // Note: location/deadline might be empty string, which is fine if DB column is VARCAR/DATE
        $stmt->bind_param("issssss", $clientId, $title, $desc, $budget, $category, $location, $deadline);

        if ($stmt->execute()) {
            $this->sendResponse('success', 'Job posted successfully');
        } else {
            $this->sendResponse('error', 'Failed to create job: ' . $this->db->error);
        }
    }
}

header("Content-Type: application/json");
$api = new JobAPI();
$api->handleRequest();
