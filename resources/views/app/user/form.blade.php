@extends('layouts/contentNavbarLayout')

@section('title', 'User')

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
    
    input[type="file"] {
        display: none;
    }
    
    .uploaded-avatar-area {
        text-align: center;
        text-align: -webkit-center;
    }
</style>

@php($isEdit = isset($data))
<form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data" id="upload-foto">
    <div class="card">
        @csrf
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-title">@if($isEdit) Edit User @else Tambah User @endif </h3>
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" id="id" name="id" value="{{ $data->id ?? '' }}" />
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $data->name ?? '') }}" placeholder="" />
                <label for="name">Nama *</label>
            </div>
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $data->username ?? '') }}" placeholder="" />
                <label for="username">Username *</label>
            </div>
            <div class="form-floating mb-4">
                <select class="form-control" name="role" id="role">
                    <option value="">Pilih Role</option>
                    <option value="superadmin" @if($isEdit) {{ old('role', $data->role) == 'superadmin' ? 'selected' : '' }} @endif>Superadmin</option>
                    <option value="admin" @if($isEdit) {{ old('role', $data->role) == 'admin' ? 'selected' : '' }} @endif>Admin</option>
                    <option value="employee" @if($isEdit) {{ old('role', $data->role) == 'employee' ? 'selected' : '' }} @endif>Employee</option>
                </select>
                <label for="role">Role *</label>
            </div>
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="email" name="email" value="{{ old('email', $data->email ?? '') }}" placeholder="" />
                <label for="email">Email *</label>
            </div>
            <div class="form-floating mb-4 mt-5">
                <h6 class="mb-4">Avatar (Opsional)</h6>
                <input type="hidden" name="old_avatar" value="{{ $data->avatar ?? '' }}">
                <input type="file" style="display:none;" name="avatar" id="avatar" accept="image/*" />
                <div id="preview" style="position:relative;" class="d-flex flex-column justify-content-center text-center w-100">
                    @php($avatar = $isEdit && !empty($data->avatar) ? asset('uploads/'.$data->avatar) : asset('assets/img/avatars/null.jpg'))
                    <img id="preview-image" style="height:220px; width:220px; border: 5px solid #c9c4c3; transition: background ease-out 200ms; align-self: center; object-fit: cover;" class="bg-pale-ash rounded @error('profile_file') is-invalid @enderror" src="{{ $avatar }}" alt="preview image" />
                    <span class="fs-13 text-ash"><i class="uil uil-exclamation-circle"></i> Maks. Ukuran 2MB</span>
                    <div>
                        <button type="button" id="upload-button" class="btn btn-primary mt-2" aria-labelledby="image" aria-describedby="image">
                            Upload Foto
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password', $data->password ?? '') }}" placeholder="" />
                <label for="password">Password *</label>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('user.index') }}" class="btn btn-light btn-active-light-primary">Back</a>
            
            <button type="submit" class="btn btn-primary float-end">
                @if($isEdit) Save @else Save @endif
            </button>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const uploadButton = document.getElementById("upload-button");
        const avatarInput = document.getElementById("avatar");
        
        uploadButton.addEventListener("click", function () {
            avatarInput.click();
        });
        
        avatarInput.addEventListener("change", function () {
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
    });
</script>
@endsection