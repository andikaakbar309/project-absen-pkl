@extends('layouts/contentNavbarLayout')

@section('title', 'Attendance')

@section('content')
<style>
    #preview-image {
        height: 220px;
        width: 220px;
        border: 50% solid #45c4a0;
        transition: background ease-out 200ms;
        align-self: center;
        object-fit: cover;
    }
    
    #preview-image.is-invalid {
        border: 1px solid #e53e3e;
    }
    
    #preview {
        position: relative;
    }
    
    .uploaded-avatar-area {
        text-align: center;
        text-align: -webkit-center;
    }
    
    .hidden {
        display: none;
    }
</style>

@php($isEdit = isset($data))
<form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data" id="upload-foto">
    <div class="card">
        @csrf
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-title">@if($isEdit) Edit Kehadiran @else Tambah Kehadiran @endif </h3>
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" id="id" name="id" value="{{ $data->id ?? '' }}" />
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="date" name="date" value="{{ old('date', $isEdit ? $data->date : now()->toDateTimeString()) }}" placeholder="" readonly />
                <label for="date">Tanggal</label>
            </div>            
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" placeholder="" readonly />
                <label for="name">Nama *</label>
            </div>
            <div class="form-floating mb-5">
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" data-placeholder="Pilih Kehadiran">
                    <option value="">Pilih Kehadiran</option>
                    <option value="hadir" @if($isEdit) {{ old('status', $data->status) == 'hadir' ? 'selected' : '' }} @endif>Hadir</option>
                    <option value="izin" @if($isEdit) {{ old('status', $data->status) == 'izin' ? 'selected' : '' }} @endif>Izin</option>
                    <option value="sakit" @if($isEdit) {{ old('status', $data->status) == 'sakit' ? 'selected' : '' }} @endif>Sakit</option>
                </select>
                <label for="status">Kehadiran *</label>
                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            {{-- <div class="form-floating mb-4 mt-5" id="uploadFileSection">
                <h6 class="mb-4">Bukti (Foto, Dokumen, Dll)</h6>
                <input type="hidden" name="old_file" value="{{ $data->file ?? '' }}">
                <input type="file" style="display:none;" name="file" id="file" accept="image/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                <div id="preview" style="position:relative;" class="d-flex flex-column justify-content-center text-center w-100">
                    @php($file = $isEdit && !empty($data->file) ? asset('uploads/'.$data->file) : asset('assets/img/avatars/null.jpg'))
                    <img id="preview-image" style="height:220px; width:220px; border: 5px solid #c9c4c3; transition: background ease-out 200ms; align-self: center; object-fit: cover;" class="bg-pale-ash rounded @error('profile_file') is-invalid @enderror" src="{{ $file }}" alt="preview image" />
                    <span class="fs-13 text-ash"><i class="uil uil-exclamation-circle"></i> Maks. Ukuran 2MB</span>
                    <div>
                        <button type="button" id="upload-button" class="btn btn-primary mt-2" aria-labelledby="image" aria-describedby="image">
                            Upload Foto
                        </button>
                    </div>
                </div>
            </div> --}}

            <div class="form-floating mb-4 mt-5" id="uploadFileSection">
                <h6 class="mb-4">Bukti (Foto, Dokumen, Dll)</h6>
                <input class="form-control" type="file" name="file" id="file" accept="image/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                @error('file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-floating mb-4" id="alasanField">
                <textarea type="text" class="form-control" id="reasons" name="reasons" placeholder="Alasan wajib diisi" style="height: 200px">{{ old('reasons', $data->reasons ?? '') }}</textarea>
            </div>
            
            <div class="form-floating mb-4" id="lateReason">
                <textarea type="text" class="form-control" id="reasons" name="reasons" placeholder="Keterangan wajib diisi" style="height: 200px">{{ old('reasons', $data->reasons ?? '') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('attendance.index') }}" class="btn btn-light btn-active-light-primary">Back</a>    
            
            <button type="submit" class="btn btn-primary float-end">
                @if($isEdit) Save @else Save @endif
            </button>
        </div>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/4xzqv9nj5troam30vipberdj8kvfb03vhtrl8ygrd9sio5tn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const uploadButton = document.getElementById("upload-button");
        const fileInput = document.getElementById("file");
        const statusSelect = document.getElementById("status");
        const uploadFileSection = document.getElementById("uploadFileSection");
        const alasanField = document.getElementById("alasanField");
        
        uploadButton.addEventListener("click", function () {
            fileInput.click();
        });
        
        fileInput.addEventListener("change", function () {
            const previewImage = document.getElementById("preview-image");
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        toggleFieldsBasedOnStatus();
        
        statusSelect.addEventListener("change", toggleFieldsBasedOnStatus);
        
        function toggleFieldsBasedOnStatus() {
            const selectedStatus = statusSelect.value;
            
            if (selectedStatus === "izin" || selectedStatus === "sakit") {
                uploadFileSection.classList.remove("hidden");
                alasanField.classList.remove("hidden");
            } else {
                uploadFileSection.classList.add("hidden");
                alasanField.classList.add("hidden");
            }
            
            // Memanggil SweetAlert saat user memilih Hadir dan kedua field tersembunyi
            if (selectedStatus === "hadir" && uploadFileSection.classList.contains("hidden")) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Anda telah hadir hari ini.',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        }
        
        // Add form submission validation
        const form = document.getElementById("upload-foto");
        
        form.addEventListener("submit", function (event) {
            const selectedStatus = statusSelect.value;
            
            // Validate fields based on selected status
            if ((selectedStatus === "izin" || selectedStatus === "sakit") && (!fileInput.files.length || !alasanField.value.trim())) {
                // Menggunakan SweetAlert untuk memberikan pemberitahuan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Harap isi Bukti dan Alasan jika anda memilih Izin atau Sakit.',
                });
                
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script> --}}

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusSelect = document.getElementById("status");
        const uploadFileSection = document.getElementById("uploadFileSection");
        const alasanField = document.getElementById("alasanField");
        const lateReaason = document.getElementById("lateReason");
        const dateInput = document.getElementById("date");
        
        tinymce.init({
            selector: 'textarea',
            height: 300,
            plugins: 'link image code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
        });
        
        toggleFieldsBasedOnStatus();
        
        statusSelect.addEventListener("change", toggleFieldsBasedOnStatus);
        
        function toggleFieldsBasedOnStatus() {
            const selectedStatus = statusSelect.value;
            
            const currentDate = new Date(dateInput.value);
            const currentHour = currentDate.getHours();
            
            if (selectedStatus === "hadir" && currentHour >= 8) {
                lateReaason.classList.remove("hidden");
            } else {
                lateReaason.classList.add("hidden");
            }
            
            if (selectedStatus === "izin" || selectedStatus === "sakit") {
                uploadFileSection.classList.remove("hidden");
                alasanField.classList.remove("hidden");
            } else {
                uploadFileSection.classList.add("hidden");
                alasanField.classList.add("hidden");
            }
        }
        
        const form = document.getElementById("upload-foto");
        
        form.addEventListener("submit", function (event) {
            const selectedStatus = statusSelect.value;
            
            const fileInput = document.getElementById("file");
            const alasanField = document.getElementById("alasanField");
            
            if ((selectedStatus === "izin" || selectedStatus === "sakit") && (!fileInput.files.length || !alasanField.value.trim())) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Harap isi Bukti dan Alasan jika anda memilih Izin atau Sakit.',
                });
                
                event.preventDefault();
            } else if (selectedStatus === "hadir" && uploadFileSection.classList.contains("hidden")) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Anda telah hadir hari ini.',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });
</script>

@endsection