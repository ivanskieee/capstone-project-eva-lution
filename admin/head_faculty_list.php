<?php
include "handlers/head_faculty_handler.php";
?>

<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="col-12 mb-3">
            <h2 class="text-start"
                style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                Head Faculty List</h2>
        </div>
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-success" href="new_head_faculty.php"><i
                            class="fa fa-plus"></i> Add New Head Faculty</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm"
                        placeholder="Search Head Faculties">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                            foreach ($head_faculties as $row): ?>
                                <tr>
                                    <th class="text-center"><?php echo $i++ ?></th>
                                    <td><b><?php echo $row['school_id'] ?></b></td>
                                    <td><b><?php echo ucwords($row['firstname'] . ' ' . $row['lastname']) ?></b></td>
                                    <td><b><?php echo $row['email'] ?></b></td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="true">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="new_head_faculty.php?head_id=<?php echo $row['head_id']; ?>">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <form method="post" action="head_faculty_list.php" style="display: inline;"
                                                class="delete-form" id="delete-form">
                                                <input type="hidden" name="delete_id"
                                                    value="<?php echo isset($row['head_id']) ? $row['head_id'] : ''; ?>">
                                                <button type="submit" class="dropdown-item"
                                                    onclick="confirmDeletion()">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No head faculty found.</p>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function () {
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will permanently delete the head faculty.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: 'POST',
                        url: 'head_faculty_list.php',
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Head Faculty deleted successfully.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = 'head_faculty_list.php';
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error deleting faculty. Please try again.',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        document.getElementById('searchInput').addEventListener('keyup', function () {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('#list tbody tr');
            var noRecordsMessage = document.getElementById('noRecordsMessage');
            var matchesFound = false;

            rows.forEach(function (row) {
                var cells = row.querySelectorAll('td');
                var matches = false;

                cells.forEach(function (cell) {
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        matches = true;
                    }
                });

                if (matches) {
                    row.style.display = '';
                    matchesFound = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (matchesFound) {
                noRecordsMessage.style.display = 'none';
            } else {
                noRecordsMessage.style.display = '';
            }
        });
    });
</script>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    body {
        overflow-y: hidden;
    }

    html {
        scroll-behavior: smooth;
    }

    .main-header {
        max-height: 100vh;
        overflow-y: scroll;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }

    .main-header::-webkit-scrollbar {
        display: none;
    }
</style>