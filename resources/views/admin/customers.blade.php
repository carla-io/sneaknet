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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

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
