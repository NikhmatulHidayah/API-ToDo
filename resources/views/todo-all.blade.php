<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">ToDo List</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Aktivitas</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="todo-list">
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="mb-3">
                            <label class="form-label">Aktivitas</label>
                            <input type="text" class="form-control" id="add-activity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-control" id="add-deadline" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Aktivitas</label>
                            <input type="text" class="form-control" id="edit-activity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-control" id="edit-deadline" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit-status">
                                <option value="0">Belum Dikerjakan</option>
                                <option value="1">Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchTodos() {
                $.get("/api/todo/all", function(data) {
                    let rows = "";
                    data.forEach(todo => {
                        rows += `<tr>
                            <td>${todo.activity}</td>
                            <td>${todo.date_deadline}</td>
                            <td>${todo.is_done ? 'Selesai' : 'Belum Dikerjakan'}</td>
                            <td>
                                <button class="btn btn-warning btn-edit" data-id="${todo.id}" data-activity="${todo.activity}" data-deadline="${todo.date_deadline}" data-status="${todo.is_done}">Edit</button>
                                <button class="btn btn-danger btn-delete" data-id="${todo.id}">Delete</button>
                            </td>
                        </tr>`;
                    });
                    $("#todo-list").html(rows);
                });
            }

            fetchTodos();

            $(document).on("click", ".btn-edit", function() {
                let id = $(this).data("id");
                let activity = $(this).data("activity");
                let deadline = $(this).data("deadline");
                let status = $(this).data("status");

                $("#edit-id").val(id);
                $("#edit-activity").val(activity);
                $("#edit-deadline").val(deadline);
                $("#edit-status").val(status);
                $("#editModal").modal("show");
            });

            $("#editForm").submit(function(e) {
                e.preventDefault();
                let id = $("#edit-id").val();
                let activity = $("#edit-activity").val();
                let deadline = $("#edit-deadline").val();
                let status = $("#edit-status").val();

                $.ajax({
                    url: `/api/todo/edit/${id}`,
                    type: "PUT",
                    contentType: "application/json",
                    data: JSON.stringify({ activity, date_deadline: deadline, is_done: status }),
                    success: function() {
                        $("#editModal").modal("hide");
                        fetchTodos();
                    }
                });
            });

            $("#addForm").submit(function(e) {
                e.preventDefault();
                let activity = $("#add-activity").val();
                let deadline = $("#add-deadline").val();

                $.ajax({
                    url: "/api/todo/create",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ activity, date_deadline: deadline, is_done: false }), // Status set default false
                    success: function(response) {
                        $("#addModal").modal("hide");
                        fetchTodos();
                    },
                    error: function(xhr, status, error) {
                        alert("Terjadi kesalahan saat menambah aktivitas.");
                    }
                });
            });


            $(document).on("click", ".btn-delete", function() {
                let id = $(this).data("id");
                if (confirm("Apakah Anda yakin ingin menghapus aktivitas ini?")) {
                    $.ajax({
                        url: `/api/todo/delete/${id}`,
                        type: "DELETE",
                        success: function() {
                            fetchTodos();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>