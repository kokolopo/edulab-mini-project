@extends('layout.index')
@section('contain')
    <div class="container">
        <h1>Data Students</h1>
        <a class="btn btn-success" href="javascript:void(0)" id="createNewStudent">Add New Student</a>
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>class</th>
                    <th>status</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection