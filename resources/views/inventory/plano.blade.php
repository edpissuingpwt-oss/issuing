@extends('layouts.app')
<title>PLANO (MINUS & PLUS)</title>
<style>
/* Modern Card */
.modern-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    background: #ffffff;
}
[data-theme="dark"] .modern-card {
    background: #1e1f25;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35);
}

/* Header */
.modern-header {
    background: linear-gradient(90deg, #007bff, #007bff);
    color: #ffffff !important;
    border-radius: 1rem 1rem 0 0;
    padding: 1rem 1.5rem;
    text-align: center;
    font-weight: 700;
    letter-spacing: 0.5px;
}
[data-theme="dark"] .modern-header {
    background: linear-gradient(90deg, #2a5298, #1e3c72);
}

/* Select box */
.modern-select {
    border-radius: 0.75rem;
    padding: 0.75rem;
    font-size: 1rem;
}

/* Button Modern */
.modern-btn {
    border-radius: 0.75rem;
    padding: 0.6rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
    transition: 0.3s;
}
.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 123, 255, 0.35);
}
</style>

@section('content')
<div class="container mt-4" style="max-width: 650px;">
    
    <div class="modern-card">

        <div class="modern-header">
            PLANO (MINUS & PLUS)
        </div>

        <div class="card-body p-4">

            <form action="{{ route('plano.result') }}" method="get">

                <label class="fw-bold mb-2">Jenis :</label>
                <select class="form-control form-select modern-select" name="flag" id="flag">
                    <option value="1">1. Plano Minus Gudang</option>
                    <option value="2">2. Plano Minus Toko</option>
                    <option value="3">3. Plano Minus Gudang dan Toko</option>
                    <option value="4">4. Plano Plus Gudang</option>
                    <option value="5">5. Plano Plus Toko</option>
                    <option value="6">6. Plano Plus Gudang dan Toko</option>
                    <option value="7">7. Rekap</option>
                </select>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary modern-btn">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
