@extends('layouts/contentNavbarLayout')

@section('title', 'Employee')

@section('content')
@if (session('status'))
    @include('_partials.alert-box', [
        'status' => session('status'),
        'message' => session('message'),
    ])
@endif

<div class="card">
    <div class="card-body">
        <a href="{{ route('employee.create') }}" class="btn btn-primary mb-3">
            <span class="mdi mdi-plus me-2"></span> Tambah Karyawan
        </a>
        <table class="table table-striped" id="tabelku">
            <thead>
                <tr>
                    <th class="w-10px pe-2">No.</th>
                    <th class="min-w-50px">Nama</th>
                    <th class="min-w-100px">Alamat</th>
                    <th class="min-w-100px">Jabatan</th>
                    <th class="text-center min-w-100px">Action</th> 
                </tr>
            </thead>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function ($) {
        $('#tabelku').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('get-users') }}",
            lengthChange: false,
            order: [
            [4, 'desc']
            ],
            columns: [{
                data: 'id'
            },
            {
                data: 'name'
            },
            {
                data: 'address'
            },
            {
                data: 'position'
            },
            {
                data: 'created_at'
            },
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                targets: 4,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                    <div class="dropdown">
                        <a class="btn btn-primary dropdown-toggle bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border:none; box-shadow:none;">
                            <i class="fas fa-ellipsis-v" style="color: #000;"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Detail</a></li>
                            <li><a class="dropdown-item" href="employee/${row.id}/edit" title="Edit">Edit</a></li>
                            <li><a class="dropdown-item" href="#">Delete</a></li>
                        </ul>
                    </div>
                    `}
                },
                ]
            });
        });
    </script>
    @endsection