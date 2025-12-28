<?php
session_start();
// Initialize variables for form data and errors
$first_name = $last_name = $email = $phone = $user_type = "";
$password = $confirm_password = "";

$first_name_err = $last_name_err = $email_err = $phone_err = "";
$password_err = $confirm_password_err = $user_type_err = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Determine if we are processing a Registration or Login request
    $is_registration = isset($_POST["first_name"]);

    if ($is_registration) {
        // --- Registration Logic ---

        // Basic Name Validation
        if (empty($_POST["first_name"])) {
            $first_name_err = "First Name is required";
        } else {
            $first_name = test_input($_POST["first_name"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
                $first_name_err = "Only letters and spaces allowed";
            }
        }

        if (empty($_POST["last_name"])) {
            $last_name_err = "Last Name is required";
        } else {
            $last_name = test_input($_POST["last_name"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
                $last_name_err = "Only letters and spaces allowed";
            }
        }

        // Email Validation
        if (empty($_POST["email"])) {
            $email_err = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email address";
            }
        }

        // Phone Validation
        if (empty($_POST["phone"])) {
            $phone_err = "Phone number is required";
        } else {
            $phone = test_input($_POST["phone"]);
            if (!preg_match("/^[0-9]*$/", $phone)) {
                $phone_err = "Only numbers allowed";
            }
        }

        // Password Validation
        if (empty($_POST["password"])) {
            $password_err = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
        }

        if (empty($_POST["confirm_password"])) {
            $confirm_password_err = "Please confirm your password";
        } else {
            $confirm_password = test_input($_POST["confirm_password"]);
            if ($password != $confirm_password) {
                $confirm_password_err = "Passwords do not match";
            }
        }

        // Role Selection
        if (empty($_POST["user_type"])) {
            $user_type_err = "Please select a role";
        } else {
            $user_type = test_input($_POST["user_type"]);
        }
    } else {
        // --- Login Logic ---

        if (empty($_POST["email"])) {
            $email_err = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format";
            }
        }

        if (empty($_POST["password"])) {
            $password_err = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
        }
    }
}

/**
 * Sanitize input data
 */
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Include the View
require_once '../html/auth.php';
