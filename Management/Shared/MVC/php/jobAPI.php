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

    function createNotification($userId, $type, $title, $message)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO notifications (user_id, type, title, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            if ($stmt) {
                $stmt->bind_param("isss", $userId, $type, $title, $message);
                if (!$stmt->execute()) {
                    error_log("Notification insert failed: " . $stmt->error);
                }
                $stmt->close();
            } else {
                error_log("Notification prepare failed: " . $this->db->error);
            }
        } catch (Exception $e) {
            error_log("Notification exception: " . $e->getMessage());
        }
    }

    function getInput($key)
    {
        return $_REQUEST[$key] ?? '';
    }

    function handleRequest()
    {
        $action = $this->getInput('action');

        switch ($action) {
            case 'create':
                $this->CreateJob();
                break;
            case 'list':
                $this->ListJobs();
                break;
            case 'get_job':
                $this->GetJob();
                break;
            case 'apply':
                $this->ApplyForJob();
                break;
            case 'my_jobs':
                $this->GetClientJobs();
                break;
            case 'get_client_applications':
                $this->GetClientApplications();
                break;
            case 'hire_worker':
                $this->HireWorker();
                break;
            case 'reject_application':
                $this->RejectApplication();
                break;
            case 'complete_job':
                $this->CompleteJob();
                break;
            case 'get_categorized_worker_jobs':
                $this->GetCategorizedWorkerJobs();
                break;
            case 'get_worker_applications':
                $this->GetWorkerApplications();
                break;
            case 'get_worker_history':
                $this->GetWorkerHistory();
                break;
            case 'get_worker_stats':
                $this->GetWorkerStats();
                break;
            default:
                $this->sendResponse('error', 'Invalid action');
        }
    }

    function CreateJob()
    {
        $clientId = $this->getInput('client_id');
        $title = $this->getInput('title');
        $desc = $this->getInput('description');
        $budget = $this->getInput('budget');
        $category = $this->getInput('category');
        $location = $this->getInput('location');
        $deadline = $this->getInput('deadline');

        if (!$clientId || !$title || !$budget) {
            $this->sendResponse('error', 'Missing required fields');
        }

        $sql = "INSERT INTO jobs (client_id, title, description, budget, category, location, deadline, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'open', NOW())";

        try {
            $stmt = $this->db->prepare($sql);
        } catch (Exception $e) {
            $this->sendResponse('error', 'Prepare failed: ' . $e->getMessage());
        }

        if (!$stmt) {
            $this->sendResponse('error', 'Prepare failed: ' . $this->db->error);
        }
        $stmt->bind_param("issssss", $clientId, $title, $desc, $budget, $category, $location, $deadline);

        if ($stmt->execute()) {
            $this->sendResponse('success', 'Job posted successfully');
        } else {
            $this->sendResponse('error', 'Failed to create job: ' . $this->db->error);
        }
    }

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

    function GetJob()
    {
        $id = $this->getInput('id');
        if (!$id) {
            $this->sendResponse('error', 'Job ID required');
        }

        $sql = "SELECT j.*, u.first_name, u.last_name, u.avatar 
                FROM jobs j 
                JOIN users u ON j.client_id = u.id 
                WHERE j.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($job = $result->fetch_assoc()) {
            $this->sendResponse('success', 'Job fetched', ['job' => $job]);
        } else {
            $this->sendResponse('error', 'Job not found');
        }
    }

    function ApplyForJob()
    {
        $jobId = $this->getInput('job_id');
        $workerId = $this->getInput('worker_id');
        $bid = $this->getInput('bid_amount');
        $cover = $this->getInput('cover_letter');

        if (!$jobId || !$workerId) {
            $this->sendResponse('error', 'Invalid request parameters');
        }

        if (empty($bid)) {
            $this->sendResponse('error', 'Please enter your bid amount');
        }

        if (empty($cover)) {
            $this->sendResponse('error', 'Please write a cover letter');
        }

        // Check if already applied
        $checkSql = "SELECT id FROM applications WHERE job_id = ? AND worker_id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bind_param("ii", $jobId, $workerId);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            $this->sendResponse('error', 'You have already applied for this job');
        }
        $checkStmt->close();

        // Insert Application
        $sql = "INSERT INTO applications (job_id, worker_id, bid_amount, cover_letter, status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiss", $jobId, $workerId, $bid, $cover);

        if ($stmt->execute()) {
            $stmt->close(); // IMPORTANT: Close insert statement before running new queries

            // --- Notification Logic ---

            // 1. Get Job Info
            $jobInfoSql = "SELECT title, client_id FROM jobs WHERE id = ?";
            $jStmt = $this->db->prepare($jobInfoSql);
            $jStmt->bind_param("i", $jobId);
            $jStmt->execute();
            $jobRes = $jStmt->get_result();
            $jobInfo = $jobRes->fetch_assoc();
            $jStmt->close();

            // 2. Get Worker Info
            $workerInfoSql = "SELECT first_name, last_name FROM users WHERE id = ?";
            $wStmt = $this->db->prepare($workerInfoSql);
            $wStmt->bind_param("i", $workerId);
            $wStmt->execute();
            $workerRes = $wStmt->get_result();
            $workerInfo = $workerRes->fetch_assoc();
            $wStmt->close();

            // 3. Create Notification if data exists
            if ($jobInfo && $workerInfo) {
                $workerName = $workerInfo['first_name'] . ' ' . $workerInfo['last_name'];
                $notifTitle = "New Application";
                $notifMsg = "$workerName applied for your job: " . $jobInfo['title'];

                $this->createNotification($jobInfo['client_id'], 'important', $notifTitle, $notifMsg);
            }

            $this->sendResponse('success', 'Application submitted successfully');
        } else {
            $this->sendResponse('error', 'Failed to apply: ' . $this->db->error);
        }
    }

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

    function GetClientApplications()
    {
        $clientId = $this->getInput('client_id');

        $sql = "SELECT a.id, a.job_id, a.worker_id, a.bid_amount, a.status, a.cover_letter, a.created_at,
                       j.title as job_title, u.first_name, u.last_name, u.avatar
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                JOIN users u ON a.worker_id = u.id
                WHERE j.client_id = ? AND a.status != 'rejected' AND j.status != 'completed'
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

        if (!$appId || !$jobId) {
            $this->sendResponse('error', 'Missing application or job ID');
        }

        $this->db->begin_transaction();

        try {
            $stmt = $this->db->prepare("UPDATE applications SET status = 'accepted' WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed for application update: " . $this->db->error);
            }
            $stmt->bind_param("i", $appId);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed for application update: " . $stmt->error);
            }

            $stmt2 = $this->db->prepare("UPDATE jobs SET status = 'in_progress', hired_worker_id = (SELECT worker_id FROM applications WHERE id = ?) WHERE id = ?");
            if (!$stmt2) {
                throw new Exception("Prepare failed for job update: " . $this->db->error);
            }
            $stmt2->bind_param("ii", $appId, $jobId);

            if (!$stmt2->execute()) {
                throw new Exception("Execute failed for job update: " . $stmt2->error);
            }

            $this->db->commit();

            // Notify Worker
            $workerSql = "SELECT worker_id FROM applications WHERE id = ?";
            $wStmt = $this->db->prepare($workerSql);
            $wStmt->bind_param("i", $appId);
            $wStmt->execute();
            $workerData = $wStmt->get_result()->fetch_assoc();

            $jobSql = "SELECT title FROM jobs WHERE id = ?";
            $jStmt = $this->db->prepare($jobSql);
            $jStmt->bind_param("i", $jobId);
            $jStmt->execute();
            $jobInfo = $jStmt->get_result()->fetch_assoc();

            if ($workerData && $jobInfo) {
                $this->createNotification($workerData['worker_id'], 'success', "You're Hired!", "You have been hired for the job: " . $jobInfo['title']);
            }

            $this->sendResponse('success', 'Worker hired');
        } catch (Exception $e) {
            $this->db->rollback();
            $this->sendResponse('error', 'Failed to hire: ' . $e->getMessage());
        }
    }

    function RejectApplication()
    {
        $appId = $this->getInput('application_id');
        $stmt = $this->db->prepare("UPDATE applications SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $appId);

        if ($stmt->execute()) {

            // Notify Worker
            $appSql = "SELECT worker_id, job_id FROM applications WHERE id = ?";
            $aStmt = $this->db->prepare($appSql);
            $aStmt->bind_param("i", $appId);
            $aStmt->execute();
            $appData = $aStmt->get_result()->fetch_assoc();

            if ($appData) {
                $jobSql = "SELECT title FROM jobs WHERE id = ?";
                $jStmt = $this->db->prepare($jobSql);
                $jStmt->bind_param("i", $appData['job_id']);
                $jStmt->execute();
                $jobInfo = $jStmt->get_result()->fetch_assoc();

                $jobTitle = $jobInfo ? $jobInfo['title'] : 'a job';
                $this->createNotification($appData['worker_id'], 'important', "Application Rejected", "Your application for '$jobTitle' was not successful.");
            }

            $this->sendResponse('success', 'Application rejected');
        } else {
            $this->sendResponse('error', 'Failed to reject');
        }
    }

    function CompleteJob()
    {
        $jobId = $this->getInput('job_id');
        $workerId = $this->getInput('worker_id');
        $reviewerId = $this->getInput('reviewer_id');
        $rating = $this->getInput('rating');
        $comment = $this->getInput('comment');

        if (!$jobId || !$workerId || !$rating) {
            $this->sendResponse('error', 'Missing required fields');
        }

        $this->db->begin_transaction();

        try {
            $sqlJob = "UPDATE jobs SET status = 'completed' WHERE id = ?";
            $stmt = $this->db->prepare($sqlJob);
            if (!$stmt) {
                throw new Exception("Prepare failed for job update: " . $this->db->error);
            }
            $stmt->bind_param("i", $jobId);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed for job update: " . $stmt->error);
            }

            $sqlReview = "INSERT INTO reviews (job_id, reviewer_id, reviewee_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
            $stmt2 = $this->db->prepare($sqlReview);
            if (!$stmt2) {
                throw new Exception("Prepare failed for review insert: " . $this->db->error);
            }
            $stmt2->bind_param("iiids", $jobId, $reviewerId, $workerId, $rating, $comment);
            if (!$stmt2->execute()) {
                throw new Exception("Execute failed for review insert: " . $stmt2->error);
            }

            $this->db->commit();

            // Notify Worker
            $jobSql = "SELECT title, budget FROM jobs WHERE id = ?";
            $jStmt = $this->db->prepare($jobSql);
            $jStmt->bind_param("i", $jobId);
            $jStmt->execute();
            $jobInfo = $jStmt->get_result()->fetch_assoc();

            if ($jobInfo) {
                $this->createNotification($workerId, 'success', "Job Completed & Paid", "The job '" . $jobInfo['title'] . "' is marked complete. Payment of $" . $jobInfo['budget'] . " has been released.");
            }

            $this->sendResponse('success', 'Job completed and review saved');
        } catch (Exception $e) {
            $this->db->rollback();
            $this->sendResponse('error', 'Database Error: ' . $e->getMessage());
        }
    }

    function GetCategorizedWorkerJobs()
    {
        $workerId = $this->getInput('worker_id');
        if (!$workerId) {
            $this->sendResponse('error', 'Worker ID is required.');
        }

        $sqlCompleted = "SELECT j.*, u.first_name, u.last_name, u.avatar 
                         FROM applications a 
                         JOIN jobs j ON a.job_id = j.id 
                         JOIN users u ON j.client_id = u.id
                         WHERE a.worker_id = ? AND j.status = 'completed'
                         ORDER BY j.created_at DESC";

        $stmtCompleted = $this->db->prepare($sqlCompleted);
        $stmtCompleted->bind_param("i", $workerId);
        $stmtCompleted->execute();
        $completedJobs = $stmtCompleted->get_result()->fetch_all(MYSQLI_ASSOC);

        $sqlRunning = "SELECT j.*, a.bid_amount, u.first_name, u.last_name, u.avatar 
                       FROM applications a 
                       JOIN jobs j ON a.job_id = j.id 
                       JOIN users u ON j.client_id = u.id
                       WHERE a.worker_id = ? AND j.status = 'in_progress' AND a.status = 'accepted'
                       ORDER BY j.created_at DESC";

        $stmtRunning = $this->db->prepare($sqlRunning);
        $stmtRunning->bind_param("i", $workerId);
        $stmtRunning->execute();
        $runningJobs = $stmtRunning->get_result()->fetch_all(MYSQLI_ASSOC);

        $sqlApplied = "SELECT j.*, a.bid_amount, a.status as app_status, a.created_at as applied_at, 
                       u.first_name, u.last_name, u.avatar 
                       FROM applications a 
                       JOIN jobs j ON a.job_id = j.id 
                       JOIN users u ON j.client_id = u.id
                       WHERE a.worker_id = ? AND a.status = 'pending'
                       ORDER BY a.created_at DESC";

        $stmtApplied = $this->db->prepare($sqlApplied);
        $stmtApplied->bind_param("i", $workerId);
        $stmtApplied->execute();
        $appliedJobs = $stmtApplied->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->sendResponse('success', 'Categorized jobs fetched successfully', [
            'completed' => $completedJobs,
            'running' => $runningJobs,
            'applied' => $appliedJobs
        ]);
    }

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

    function GetWorkerStats()
    {
        $workerId = $this->getInput('worker_id');
        if (!$workerId) $this->sendResponse('error', 'ID required');

        $sql1 = "SELECT COUNT(*) as count FROM applications a 
                 JOIN jobs j ON a.job_id = j.id 
                 WHERE a.worker_id = ? AND j.status = 'completed'";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param("i", $workerId);
        $stmt1->execute();
        $completed = $stmt1->get_result()->fetch_assoc()['count'];

        $sql2 = "SELECT COALESCE(SUM(a.bid_amount), 0) as total FROM applications a 
                 JOIN jobs j ON a.job_id = j.id 
                 WHERE a.worker_id = ? AND j.status = 'completed'";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("i", $workerId);
        $stmt2->execute();
        $earnings = $stmt2->get_result()->fetch_assoc()['total'];

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
}

header("Content-Type: application/json");
$api = new JobAPI();
$api->handleRequest();
