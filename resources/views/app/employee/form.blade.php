@extends('layouts/contentNavbarLayout')

@section('title', 'Employee')

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
<form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data" id="upload-foto">
    <div class="card">
        @csrf
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-title">@if($isEdit) Edit Karyawan @else Tambah Karyawan @endif </h3>
            </div>
        </div>
        <div class="card-body">
            <input type="hidden" id="id" name="id" value="{{ $data->id ?? '' }}" />
            <div class="form-floating mb-5">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $data->name ?? '') }}" placeholder="" />
                <label for="name">Nama *</label>
            </div>
            <div class="form-floating mb-5">
                <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $data->position ?? '') }}" placeholder="" />
                <label for="position">Posisi *</label>
            </div>
            <div class="form-floating mb-5 mt-5">
                <h6 class="mb-5">Photo (Opsional)</h6>
                <input type="hidden" name="old_photo" value="{{ $data->photo ?? '' }}">
                <input type="file" style="display:none;" name="photo" id="photo" accept="image/*" />
                <div id="preview" style="position:relative;" class="d-flex flex-column justify-content-center text-center w-100">
                    @php($photo = $isEdit && !empty($data->photo) ? asset('uploads/'.$data->photo) : asset('assets/img/avatars/1.png'))
                    <img id="preview-image" style="height:220px; width:220px; border: 5px solid #c9c4c3; transition: background ease-out 200ms; align-self: center; object-fit: cover;" class="bg-pale-ash rounded @error('profile_file') is-invalid @enderror" src="{{ $photo }}" alt="preview image" />
                    <span class="fs-13 text-ash"><i class="uil uil-exclamation-circle"></i> Maks. Ukuran 2MB</span>
                    <div>
                        <button type="button" id="upload-button" class="btn btn-primary mt-2" aria-labelledby="image" aria-describedby="image">
                            Upload Foto
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-5">
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $data->address ?? '') }}" placeholder="" />
                <label for="address">Alamat *</label>
            </div>
            <div class="form-floating mb-5">
                <input type="number" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $data->phone_number ?? '') }}" placeholder="" />
                <label for="phone_number">No. Telp *</label>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('employee.index') }}" class="btn btn-light btn-active-light-primary">Back</a>    
            
            <button type="submit" class="btn btn-primary float-end">
                @if($isEdit) Save @else Save @endif
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        var isEdit = $('#id').val() != '';
            $('#upload-button').on("click", (event) => {
                event.preventDefault();
                $('input[name=photo]').click();
            });
            $('input[name=photo]').on("change", event => {
                const file = event.target.files[0];
                $('#preview-image').attr('src', URL.createObjectURL(file));
            });
        }
        loadEvents();
    });
</script>
@endsection