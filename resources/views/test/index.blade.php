@extends('layouts.app2')
<title>PB IDM MENTAHAN (SEBELUM UPLOAD)</title>
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
    
    <div class="center-box">
        <div class="card-modern">

            <form action="{{ route('test.result') }}" method="get">

                <label class="fw-bold mb-2">ZONA :</label>
                <select class="form-control form-select modern-select" name="flag" id="flag">
                    <option value="1">ZONA 1</option>
                    <option value="2">ZONA 2</option>
                    <option value="3">ZONA 3</option>
                    <option value="4">ZONA 4</option>
                    <option value="5">ZONA 5</option>
                    <option value="6">ZONA 6</option>
                    <option value="7">ZONA 7</option>
                    <option value="8">ZONA 8</option>
                    <option value="9">ZONA 9</option>
                    <option value="10">ZONA A</option>
                    <option value="11">ZONA B</option>
                    <option value="12">ZONA C</option>
                    <option value="13">ZONA D</option>
                    <option value="14">ZONA E</option>
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
