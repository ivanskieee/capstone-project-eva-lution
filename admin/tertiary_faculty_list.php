<?php
include "handlers/faculty_handler.php"; // Handle faculty-related operations
include "handlers/delete_faculty.php";
?>

<nav class="main-header">
    <div class="col-lg-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="new_faculty.php"><i class="fa fa-plus"></i> Add New Faculty</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered" id="list">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>School ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($tertiary_faculties as $row): ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><b><?php echo $row['school_id'] ?></b></td>
                                <td><b><?php echo ucwords($row['firstname'] . ' ' . $row['lastname']) ?></b></td>
                                <td><b><?php echo $row['email'] ?></b></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="./index.php?page=edit_faculty&id=<?php echo isset($row['id']) ? $row['id'] : ''; ?>">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <form method="post" action="tertiary_faculty_list.php" style="display: inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo isset($row['faculty_id']) ? $row['faculty_id'] :'';?>">
                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this faculty member?');">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</nav>
