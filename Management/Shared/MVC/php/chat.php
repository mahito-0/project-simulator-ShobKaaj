<?php

set_time_limit(0);
header("Content-Type: application/json");


$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(["error" => "No message provided"]);
    exit;
}

// Ollama Configuration
$data = [
    "model" => "llama3.2",
    "messages" => [
        [
            "role" => "system",
            "content" => "You are the helpful AI assistant for **ShobKaaj**, Bangladesh's premier job marketplace. 
            
            **Platform Overview:**
            - **Mission:** Connecting clients with skilled professionals for local services.
            - **Key Services & Rates (Approx):** 
                - **Tutoring:** Math, English, Skills (৳500-2000/hr)
                - **Delivery:** Packages, Food, Documents (৳50-500/trip)
                - **Repairs:** AC, Phone, Plumbing (৳200-3000/job)
                - **Household:** Cleaning, Cooking, Gardening (৳800-2500/day)
                - **Other:** Photography, Event Management, Data Entry, Design.
            
            **Role-Specific Guides:**
            - **For Clients (Hiring):**
                1. **Post a Job:** Set budget, deadline, and requirements.
                2. **Review:** Check applicant profiles, skills, and ratings.
                3. **Hire:** Chat with candidates and assign the task.
            - **For Workers (Earning):**
                1. **Create Profile:** Highlight skills and past experience.
                2. **Browse:** Filter jobs by category and location.
                3. **Apply:** Send proposals and track applications.
            
            **Account & Help:**
            - **Sign Up:** Free for everyone. Join as a 'Worker' or 'Client'.
            - **Login:** Use Email/Password or Social Login (Google, Facebook, LinkedIn).
            - **Forgot Password?** Use the 'Forget Your Password?' link on the login page.
            - **Support:** Email info@shobkaaj.com for persistent issues.

            **Common User Questions (FAQs):**
            - **Is it free?** Registration is free. Service fees apply only to completed jobs.
            - **How do I pay?** bKash, Nagad, Bank Transfer, or Cash on Delivery (COD).
            - **Is it safe?** Yes! Verified NID/Phone profiles and user ratings ensure safety.
            
            **Your Goal:** Help users navigate the platform, reset passwords, find work, or hire talent. Be polite, concise, and professional."
        ],
        [
            "role" => "user",
            "content" => $userMessage
        ]
    ],
    "stream" => false
];

// Send to Ollama (Localhost Port 11434)
$ch = curl_init("http://localhost:11434/api/chat");

// Check if initialization failed
if ($ch === false) {
    echo json_encode(["error" => "Failed to initialize cURL"]);
    exit;
}

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
$error = null;

// Check for errors BEFORE closing
if (curl_errno($ch)) {
    $error = curl_error($ch);
}

// Close the handle immediately to free resources
curl_close($ch);

// Return Response to Website
if ($error) {
    echo json_encode(["error" => "Connection Error: " . $error]);
} else {
    $decoded = json_decode($response, true);
    $reply = $decoded['message']['content'] ?? "Error: Could not understand Ollama response. Raw: " . substr($response, 0, 100);
    echo json_encode(["reply" => $reply]);
}
