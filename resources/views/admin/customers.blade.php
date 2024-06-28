@extends('admin.layouts.template')

@section('content')
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

    
@endsection
