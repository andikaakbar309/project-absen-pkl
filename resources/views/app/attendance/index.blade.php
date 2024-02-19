@extends('layouts/contentNavbarLayout')

@section('title', 'Attendance')

@section('content')

{{-- <style>
    .custom-size-popup {
        width: 400px; /* Atur lebar sesuai kebutuhan */
        height: auto; /* Atur tinggi atau biarkan otomatis */
    }
</style> --}}

@if (session('status'))
@include('_partials.alert-box', [
'status' => session('status'),
'message' => session('message'),
])
@endif

<div class="card">
    <div class="card-body">
        <a href="{{ route('attendance.create') }}" class="btn btn-primary mb-3">
            <span class="mdi mdi-plus me-2"></span> Tambah Kehadiran
        </a>
        <table class="table table-striped" id="tabelku">
            <thead>
                <tr>
                    <th class="w-10px pe-2">No.</th>
                    <th class="min-w-50px">Nama</th>
                    <th class="min-w-100px">Status</th>
                    <th class="min-w-100px">Tanggal</th>
                    <th class="text-center min-w-100px">Action</th> 
                </tr>
            </thead>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function ($) {
        $('#tabelku').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('attendance-datatable') }}",
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
                data: 'status'
            },
            {
                data: 'date'
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
                targets: 2,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    let badgeClass = '';
                    let badgeText = '';
                    
                    switch (row.status) {
                        case 'hadir':
                        badgeClass = 'badge bg-success';
                        badgeText = 'Hadir';
                        break;
                        case 'izin':
                        badgeClass = 'badge bg-warning';
                        badgeText = 'Izin';
                        break;
                        case 'sakit':
                        badgeClass = 'badge bg-danger';
                        badgeText = 'Sakit';
                        break;
                        default:
                        badgeClass = 'badge bg-secondary';
                        badgeText = 'Unknown';
                    }
                    
                    return `<span class="${badgeClass}">${badgeText}</span>`;
                }
            },
            {
                targets: 3,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    let formattedDateTime = moment(data).format('DD MMMM YYYY HH:mm');
                    
                    return formattedDateTime;
                }
            },
            {
                targets: 4,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    var userRole = "{{ Auth::user()->role }}";
                    
                    var detailButton = '';
                    if (userRole == 'admin' || userRole == 'superadmin') {
                        detailButton = '<li><a class="dropdown-item" href="attendance/detail/' + row.id + '" title="Detail">Detail</a></li>';
                    }
                    
                    return `
                    <div class="dropdown">
                        <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical mdi-24px"></i>
                        </button>
                        <ul class="dropdown-menu">
                            ${detailButton}
                            <li><a class="dropdown-item" href="attendance/${row.id}/edit" title="Edit">Edit</a></li>
                            <li><a class="dropdown-item delete" data-id="${row.id}" href="#" title="Delete">Delete</a></li>
                        </ul>
                    </div>
                    `;
                }
            }
            ]
        });
        
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            
            const id = $(this).data('id');
            
            Swal.fire({
                icon: 'warning',
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus!',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'custom-size-popup',
                    confirmButton: 'btn btn-sm',
                    cancelButton: 'btn btn-sm',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `attendance/${id}`, 
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil dihapus.',
                                icon: 'success',
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal menghapus data.',
                                icon: 'error',
                            });
                        },
                    });
                }
            });
        });
    });
</script>
@endsection