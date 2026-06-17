<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Session Habis</title>

    <link rel="stylesheet" href="css/font-inter.css">
    <script src="js/sweetalert.js"></script>

<style>
        /* Terapkan font Inter ke seluruh halaman dan SweetAlert2 */
        body, 
        .swal2-popup, 
        .swal2-title, 
        .swal2-html-container, 
        .swal2-confirm {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        /* Opsional: background rapi */
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
</style>
</head>
<body>

<script>

Swal.fire({
    icon: 'warning',
    title: 'Akses Ditolak',
    html: `
        <b>ANDA BELUM LOGIN ATAU SESSION HABIS</b>
        <br><br>
        Silakan login melalui <b>ISREPORT</b> terlebih dahulu.
    `,
    allowOutsideClick: false,
    confirmButtonText: 'Login ISREPORT'
}).then(() => {

    window.location.href =
    'http://192.168.83.93:8081/login';

});

</script>

</body>
</html>