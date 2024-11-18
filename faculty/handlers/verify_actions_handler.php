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
        $stmt->execute(['school_id' => $school_id]); // Use school_id for binding
        $student_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if data was fetched successfully
        if ($student_data) {
            // Check if the student's section matches any faculty in the college_faculty_list
            $faculty_query = "
                SELECT faculty_id 
                FROM college_faculty_list 
                WHERE section = :section
            ";
            $faculty_stmt = $conn->prepare($faculty_query);
            $faculty_stmt->execute(['section' => $student_data['section']]);
            $faculty_data = $faculty_stmt->fetch(PDO::FETCH_ASSOC);

            if ($faculty_data) {
                // Insert the fetched student data into `student_list`
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

                // Delete the approved student from `pending_students`
                $delete_query = "DELETE FROM pending_students WHERE school_id = :school_id";
                $stmt = $conn->prepare($delete_query);
                $stmt->execute(['school_id' => $school_id]); // Use school_id for binding

                header('Location: verify_accounts.php?status=confirmed');
                exit();
            } else {
                // Handle case where no matching faculty is found for the section
                header('Location: verify_accounts.php?status=no_faculty_match');
                exit();
            }
        } else {
            // Handle case where no data is found
            header('Location: verify_accounts.php?status=not_found');
            exit();
        }
    } elseif ($action == 'remove') {
        // Remove the student from `pending_students`
        $query = "DELETE FROM pending_students WHERE school_id = :school_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['school_id' => $school_id]); // Use school_id for binding

        header('Location: verify_accounts.php?status=rejected');
        exit();
    }
}
?>
