<?php

include 'handlers/subject_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-success new_subject"
                        href="manage_subject.php"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm"
                        placeholder="Search Subject">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="list">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="30%">
                            <col width="40%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($subjects as $row): ?>
                                <tr>
                                    <th class="text-center"><?php echo $i++ ?></th>
                                    <td><b><?php echo $row['code'] ?></b></td>
                                    <td><b><?php echo $row['subject'] ?></b></td>
                                    <td><b><?php echo $row['description'] ?></b></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="manage_subject.php?subject_id=<?php echo $row['subject_id']; ?>"
                                                class="btn btn-success btn-flat manage_subject">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" action="subject_list.php" style="display: inline;"
                                                class="delete-form">
                                                <input type="hidden" name="delete_id"
                                                    value="<?php echo isset($row['subject_id']) ? $row['subject_id'] : ''; ?>">
                                                <button type="submit" class="btn btn-secondary btn-flat delete_subject">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No subjects found.</p>
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
                text: 'This action will permanently delete the subject.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var subject_id = $('#subject_id').val();
                    $.ajax({
                        type: 'POST',
                        url: 'subject_list.php',
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Subject has been deleted.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {

                                window.location.href = 'subject_list.php';
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the subject!',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<script>
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