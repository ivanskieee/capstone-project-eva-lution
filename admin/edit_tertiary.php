<?php
include '../database/connection.php';

// Check if 'id' is set and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM college_faculty_list WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Fetch the results
        $qry = $stmt->fetch();
        
        if ($qry) {
            // Assign values to variables dynamically
            foreach ($qry as $k => $v) {
                $$k = $v;
            }
        } else {
            echo "No record found.";
        }
    } else {
        echo "Error executing query.";
    }
} else {
    echo "Invalid ID.";
}

// Include the new faculty page
include 'new_faculty.php';
?>
