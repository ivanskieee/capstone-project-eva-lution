<?php

include '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_id = $_POST['school_id'];
    $action = $_POST['action'];

    if ($action == 'confirm') {
        // Fetch data from `pending_students`
        $query = "
            SELECT 
                school_id,
                email,
                password,
                firstname,
                lastname,
                subject,
                section
            FROM pending_students
            WHERE school_id = :school_id
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute(['school_id' => $school_id]);
        $student_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student_data) {
            // Match faculty based on subject
            $faculty_query = "
                SELECT faculty_id 
                FROM college_faculty_list 
                WHERE :subject LIKE CONCAT('%', subject, '%')
            ";
            $faculty_stmt = $conn->prepare($faculty_query);
            $faculty_stmt->execute(['subject' => $student_data['subject']]);
            $faculty_data = $faculty_stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($faculty_data) {
                // Insert the student into `student_list`
                $insert_query = "
                    INSERT INTO student_list (school_id, email, password, firstname, lastname, subject, section) 
                    VALUES (:school_id, :email, :password, :firstname, :lastname, :subject, :section)
                ";
                $stmt = $conn->prepare($insert_query);
                $stmt->execute([
                    'school_id' => $student_data['school_id'],
                    'email' => $student_data['email'],
                    'password' => $student_data['password'],
                    'firstname' => $student_data['firstname'],
                    'lastname' => $student_data['lastname'],
                    'subject' => $student_data['subject'],
                    'section' => $student_data['section']
                ]);
        
                // Remove the student from `pending_students`
                $delete_query = "DELETE FROM pending_students WHERE school_id = :school_id";
                $stmt = $conn->prepare($delete_query);
                $stmt->execute(['school_id' => $school_id]);

                header('Location: verify_accounts.php?status=confirmed');
                exit();
            } else {
                // No matching faculty found
                header('Location: verify_accounts.php?status=no_faculty_match');
                exit();
            }
        } else {
            // No student found
            header('Location: verify_accounts.php?status=not_found');
            exit();
        }
    } elseif ($action == 'remove') {
        // Remove the student from `pending_students`
        $query = "DELETE FROM pending_students WHERE school_id = :school_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['school_id' => $school_id]);

        header('Location: verify_accounts.php?status=rejected');
        exit();
    }
}

?>
