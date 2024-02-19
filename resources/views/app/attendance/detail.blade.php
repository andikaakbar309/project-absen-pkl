@extends('layouts/contentNavbarLayout')

@section('title', 'Attendance')

@section('content')
<style>
    .bread-crumb {
        padding-bottom: 10px;
    }
    
    .bread-crumb ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .bread-crumb li {
        display: inline-block;
        margin-right: 5px;
    }
    
    .bread-crumb a {
        text-decoration: none;
        color: #333;
        padding: 2px;
        position: relative;
    }
    
    .bread-crumb a:hover::after {
        content: "";
        display: block;
        position: absolute;
        height: 2px;
        background-color: #bfbebb;
        bottom: 0;
        left: 0;
        right: 0;
    }
    
    .bread-crumb li::after {
        content: " - ";
        color: #333;
    }
    
    .bread-crumb li:last-child::after {
        content: "";
    }
    
    .container {
        margin-top: 20px;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
    }
    
    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0 15px;
    }
    
    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 0 15px;
    }
    
    .inner {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
    }
    
    .basic-info .block {
        margin-bottom: 15px;
    }
    
    .basic-info .title {
        font-weight: bold;
    }
    
    .basic-info .content {
        margin-top: 5px;
    }
    
    .profile-image {
        width: 100%;
        height: auto;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .text{
        font-size: 25px;
    }
</style>

<div class="container">
    <div class="inner">
        <div class="text">Detail Kehadiran</div>
        <div class="bread-crumb">
            <ul class="clearfix">
                <li><a href="{{ route('attendance.index') }}">Kehadiran</a></li>
                <li class="current">Detail</li>
            </ul>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset('uploads/'. $data->user->avatar ?? '') }}" class="profile-image" alt="User Image">
            </div>
            <div class="col-md-8">
                <div class="basic-info">
                    <div class="block">
                        <div class="title">Full Name :</div>
                        <div class="content">{{ $data->name }}</div>
                    </div>
                    <div class="block">
                        <div class="title">Tanggal :</div>
                        <div class="content">{{ \Carbon\Carbon::parse($data->date)->format('d M Y H:i') }}</div>
                    </div>
                    <div class="block">
                        <div class="title">Status :</div>
                        <div class="content">
                            @if ($data->status == 'hadir')
                            <span class="badge bg-success">Hadir</span>
                            @elseif ($data->status == 'sakit')
                            <span class="badge bg-danger">Sakit</span>
                            @elseif ($data->status == 'izin')
                            <span class="badge bg-warning text-dark">Izin</span>
                            @else
                            {{ $data->status }}
                            @endif
                        </div>
                    </div>
                    <div class="block">
                        <div class="title">Alasan/keterangan :</div>
                        <div class="content">{!! $data->reasons ?? '-' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection