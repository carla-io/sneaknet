@extends('admin.layouts.template')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
       
        <div class="mt-3">
        <h2> Users</h2>
        <table id="usersTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
      
</div>
</div>
    </div>


<script type="text/javascript">
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                ajax: {
                    url: "/api/users",
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'role' },
                    { data: 'is_active', render: function(data, type, row) {
                        return data ? 'Active' : 'Inactive';
                    }},
                    { data: null, render: function(data, type, row) {
                        return `
                            <button onclick="updateRole(${row.id})">Update Role</button>
                            <button onclick="deactivateUser(${row.id})">Deactivate</button>
                        `;
                    }}
                ]
            });

        });

        window.updateRole = function(userId) {
                var newRole = prompt('Enter new role:');
                if (newRole) {
                    $.ajax({
                        url: "/api/update-users",
                        method: "POST",
                        data: {
                            user_id: userId,
                            role: newRole
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        }
                        // error: function (error) {
                        //  console.log(error);
                        // }
                    });
                }
            };
        

            window.deactivateUser = function(userId) {
                if (confirm('Are you sure you want to deactivate this user?')) {
                    $.ajax({
                        url: '/api/deactivate-users',
                        method: 'POST',
                        data: {
                            user_id: userId
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        }
                    });
                }
            };
       

</script>
    
@endsection
