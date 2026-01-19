<?php
require_once '../db/config.php';
session_start();

class MessagesAPI
{
    var $db;
    var $userId;

    function __construct()
    {
        global $conn;
        $this->db = $conn;

        if (!$this->db) {
            $this->sendResponse('error', 'Database connection not available');
        }

        $this->userId = $_SESSION['user_id'] ?? 0;
    }

    function sendResponse($status, $message, $data = [])
    {
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
        exit;
    }

    function getInput($key)
    {
        // 1. Try standard POST
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        // 2. Try JSON body
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input[$key])) {
            return $input[$key];
        }

        // 3. Try GET 
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return '';
    }

    function handleRequest()
    {
        if (!$this->userId) {
            $this->sendResponse('error', 'Unauthorized. Please log in.');
        }

        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'send_message':
                $this->SendMessage();
                break;
            case 'get_conversations':
                $this->GetConversations();
                break;
            case 'get_messages':
                $this->GetMessages();
                break;
            default:
                $this->sendResponse('error', 'Invalid action');
        }
    }

    function SendMessage()
    {
        $receiverId = $this->getInput('receiver_id');
        $message = trim($this->getInput('message'));

        if (!$receiverId || !$message) {
            $this->sendResponse('error', 'Receiver ID and message are required');
        }

        if ($receiverId == $this->userId) {
            $this->sendResponse('error', 'You cannot message yourself');
        }

        $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iis", $this->userId, $receiverId, $message);
            if ($stmt->execute()) {
                $this->sendResponse('success', 'Message sent');
            } else {
                $this->sendResponse('error', 'Database error: ' . $stmt->error);
            }
            $stmt->close();
        } else {
            $this->sendResponse('error', 'Database prepare error');
        }
    }

    function GetConversations()
    {
        $sql = "
            SELECT 
                u.id as user_id,
                u.first_name,
                u.last_name,
                u.avatar,
                u.role,
                m.message as last_message,
                m.created_at,
                (SELECT COUNT(*) FROM messages WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) as unread_count
            FROM users u
            JOIN (
                SELECT 
                    CASE 
                        WHEN sender_id = ? THEN receiver_id 
                        ELSE sender_id 
                    END as partner_id,
                    MAX(id) as max_msg_id
                FROM messages
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY partner_id
            ) latest ON u.id = latest.partner_id
            JOIN messages m ON m.id = latest.max_msg_id
            ORDER BY m.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            // 4 params: 1 in subselect, 3 in join subselect
            $stmt->bind_param("iiii", $this->userId, $this->userId, $this->userId, $this->userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $conversations = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            $this->sendResponse('success', 'Conversations fetched', ['conversations' => $conversations]);
        } else {
            $this->sendResponse('error', 'Database error: ' . $this->db->error);
        }
    }

    function GetMessages()
    {
        $partnerId = $this->getInput('partner_id');

        if (!$partnerId) {
            $this->sendResponse('error', 'Partner ID required');
        }

        // Mark as read first
        $sql = "UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?";
        $update = $this->db->prepare($sql);
        if ($update) {
            $update->bind_param("ii", $partnerId, $this->userId);
            $update->execute();
            $update->close();
        }

        // Fetch history
        $sql = "
            SELECT m.*, u.first_name as sender_name, u.avatar as sender_avatar
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?) 
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC
        ";

        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiii", $this->userId, $partnerId, $partnerId, $this->userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $messages = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Fetch partner info for header
            $sql = "SELECT id, first_name, last_name, avatar, role FROM users WHERE id = ?";
            $userStmt = $this->db->prepare($sql);
            if ($userStmt) {
                $userStmt->bind_param("i", $partnerId);
                $userStmt->execute();
                $userResult = $userStmt->get_result();
                $partner = $userResult->fetch_assoc();
                $userStmt->close();
            } else {
                $partner = null;
            }

            $this->sendResponse('success', 'Messages fetched', ['messages' => $messages, 'partner' => $partner]);
        } else {
            $this->sendResponse('error', 'Database error: ' . $this->db->error);
        }
    }
}

header("Content-Type: application/json");
$api = new MessagesAPI();
$api->handleRequest();
