<?php
session_start();


unset($_SESSION['generated_token']);


echo json_encode(['status' => 'success']);
exit;
