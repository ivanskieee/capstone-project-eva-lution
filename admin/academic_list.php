<?php
include 'handlers/academic_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-success new_academic"
                        href="manage_academic.php"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search Academic Year">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table tabe-hover table-bordered" id="list">
                        <colgroup>
                            <col width="5%">
                            <col width="25%">
                            <col width="25%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>System Default</th>
                                <th>Evaluation Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($academics as $row):
                                ?>
                                <tr>
                                    <th class="text-center"><?php echo $i++ ?></th>
                                    <td><b><?php echo $row['year'] ?></b></td>
                                    <td><b><?php echo $row['semester'] ?></b></td>
                                    <td class="text-center">
                                        <?php if ($row['is_default'] == 0): ?>
                                            <button type="button"
                                                class="btn btn-secondary bg-gradient-secondary col-sm-4 btn-flat btn-sm px-1 py-0 make_default"
                                                data-id="<?php echo isset($row['id']) ?>">No</button>
                                        <?php else: ?>
                                            <button type="button"
                                                class="btn btn-primary bg-gradient-primary col-sm-4 btn-flat btn-sm px-1 py-0">Yes</button>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 0): ?>
                                            <span class="badge badge-secondary">Not yet Started</span>
                                        <?php elseif ($row['status'] == 1): ?>
                                            <span class="badge badge-success">Starting</span>
                                        <?php elseif ($row['status'] == 2): ?>
                                            <span class="badge badge-primary">Closed</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="manage_academic.php?academic_id=<?php echo $row['academic_id']; ?>"
                                                class="btn btn-success btn-flat manage_academic">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" action="academic_list.php" style="display: inline;"
                                                class="delete-form">
                                                <input type="hidden" name="delete_id"
                                                    value="<?php echo isset($row['academic_id']) ? $row['academic_id'] : ''; ?>">
                                                <button type="submit" class="btn btn-secondary btn-flat delete_academic">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No academic year found.</p>
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
                text: 'This action will permanently delete the academic year.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'academic_list.php',  // Adjust the URL as needed
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Academic year has been deleted.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = 'academic_list.php'; // Adjust the redirect URL if needed
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the academic year!',
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