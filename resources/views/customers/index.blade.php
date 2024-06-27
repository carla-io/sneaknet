<!DOCTYPE html>
<html>
<head>
    <title>Laravel DataTables</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Laravel DataTables - Users</h2>
        <table class="table table-bordered" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Action</th> <!-- New column for action buttons -->
                </tr>
            </thead>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.users.getUsers') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'role', name: 'role' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        function updateRole(userId, newRole) {
            $.ajax({
                url: '/admin/update-role/' + userId,
                type: 'POST',
                data: {
                    role: newRole,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.success);
                    $('#users-table').DataTable().ajax.reload();
                }
            });
        }

        function deactivateUser(userId) {
            $.ajax({
                url: '/admin/deactivate-user/' + userId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.success);
                    $('#users-table').DataTable().ajax.reload();
                }
            });
        }
    </script>
</body>
</html>
