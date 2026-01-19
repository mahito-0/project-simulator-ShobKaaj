<?php
require_once '../db/config.php';
session_start();

// Prevent PHP warnings/notices from breaking JSON structure
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

class NotificationsAPI
{
    var $db;
    var $user_id;

    function __construct()
    {
        global $conn;
        $this->db = $conn;

        if (!isset($_SESSION['user_id'])) {
            $this->sendResponse('error', 'Unauthorized');
        }
        $this->user_id = $_SESSION['user_id'];
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
        if (isset($_GET[$key])) return $_GET[$key];
        if (isset($_POST[$key])) return $_POST[$key];
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input[$key])) return $input[$key];
        return '';
    }

    function handleRequest()
    {
        $action = $this->getInput('action');

        switch ($action) {
            case 'get_notifications':
                $this->GetNotifications();
                break;
            case 'mark_read':
                $this->MarkAsRead();
                break;
            case 'delete':
                $this->DeleteNotification();
                break;
            case 'get_unread_count':
                $this->GetUnreadCount();
                break;
            default:
                $this->GetNotifications(); // Default action
                break;
        }
    }

    function GetNotifications()
    {
        $filter = $this->getInput('filter'); // all, unread, important

        $sql = "SELECT * FROM notifications WHERE user_id = ?";

        if ($filter === 'unread') {
            $sql .= " AND is_read = 0";
        } elseif ($filter === 'important') {
            $sql .= " AND type IN ('warning', 'alert', 'important')";
        }

        $sql .= " ORDER BY created_at DESC LIMIT 50";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $this->user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $notifications = $result->fetch_all(MYSQLI_ASSOC);
            $this->sendResponse('success', 'Notifications fetched', ['notifications' => $notifications]);
        } else {
            $this->sendResponse('error', 'Failed to fetch notifications');
        }
        $stmt->close();
    }

    function MarkAsRead()
    {
        $id = $this->getInput('id');

        if ($id === 'all') {
            $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $this->user_id);
        } else {
            $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $id, $this->user_id);
        }

        if ($stmt->execute()) {
            $this->sendResponse('success', 'Marked as read');
        } else {
            $this->sendResponse('error', 'Update failed');
        }
        $stmt->close();
    }

    function DeleteNotification()
    {
        $id = $this->getInput('id');

        if (!$id) {
            $this->sendResponse('error', 'Notification ID required');
        }

        $sql = "DELETE FROM notifications WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $this->user_id);

        if ($stmt->execute()) {
            $this->sendResponse('success', 'Notification deleted');
        } else {
            $this->sendResponse('error', 'Delete failed');
        }
        $stmt->close();
    }

    function GetUnreadCount()
    {
        $sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $this->sendResponse('success', 'Count fetched', ['count' => $row['count']]);
    }
}

header("Content-Type: application/json");
$api = new NotificationsAPI;
$api->handleRequest();
