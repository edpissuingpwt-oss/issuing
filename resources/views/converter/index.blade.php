@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="main-card shadow-sm">
                <div class="card-header-modern text-center">
                    <h3 class="fw-bold text-dark mb-1">PB ENCRYPT/DECRYPT</h3>
                    <p class="text-muted small">Convert File PB IDM</p>
                </div>
                
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-4 d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('csv.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">PILIH FILE</label>
                            <div class="input-group">
                                <input type="file" name="file" class="form-control" id="inputGroupFile" required>
                            </div>
                        </div>

                        <div class="process-type-box mb-4">
                            <label class="form-label mb-3">TIPE CONVERT</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="action" id="action_decrypt" value="decrypt" checked>
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="action_decrypt">
                                        <i class="bi bi-unlock d-block mb-1 fs-4"></i> DECRYPT
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="action" id="action_encrypt" value="encrypt">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="action_encrypt">
                                        <i class="bi bi-lock d-block mb-1 fs-4"></i> ENCRYPT
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit w-100">
                            <i class="bi bi-arrow-repeat me-2"></i> PROSES & DOWNLOAD
                        </button>
                    </form>
                </div>
                
                <div class="text-center pb-4">
                    <span class="text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">&copy; {{ date('Y') }} EDP ISSUING-PWT</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection