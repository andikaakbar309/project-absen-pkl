@extends('layouts/contentNavbarLayout')

@section('title', 'Action - Scanner')

@section('content') 

<h3>Scanner</h3>
    <form action="">
        <div class="card">
            @csrf
            <div class="card-header">
                <div class="card-title">
                    
                </div>
                
                <div class="card-toolbar">
                    
                </div>
            </div>
            
            <div class="card-body">
                
                <div style="width: 100%" id="scanner"></div>
            </div>
            <div class="card-footer d-grid">
                <button type="submit" class="btn btn-primary float-end">
                    <i class="ki-duotone ki-scan-barcode fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                        <span class="path7"></span>
                        <span class="path8"></span>
                    </i> Open QR
                </button>
            </div>
        </div>
    </form>
    
    @endsection
