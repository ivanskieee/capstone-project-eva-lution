<?php
session_start();

// Clear the generated token from the session
unset($_SESSION['generated_token']);

// Send a response indicating success
echo json_encode(['status' => 'success']);
exit;
