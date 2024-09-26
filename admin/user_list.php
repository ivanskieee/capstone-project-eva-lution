<?php
include 'handlers/user_handler.php';

?>

<nav class="main-header">
<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="new_users.php">
                    <i class="fa fa-plus"></i> Add New Users
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="student_list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($admin as $row): ?>
                        <tr>
                            <th class="text-center"><?php echo $i++; ?></th>
                            <td><b><?php echo htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname'])); ?></b></td>
                            <td><b><?php echo htmlspecialchars($row['email']); ?></b></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item view_student" href="javascript:void(0)" data-id="<?php echo isset($row['id']); ?>">View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="./index.php?page=edit_student&id=<?php echo isset($row['id']); ?>">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_student" href="javascript:void(0)" data-id="<?php echo isset($row['id']); ?>">Delete</a>
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