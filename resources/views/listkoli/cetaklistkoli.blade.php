<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Koli</title>

    <link rel="icon" type="image/png" sizes="64x64" href="http://192.168.83.200:200/icons8-dungeons-and-dragons-48.png">
    
    <style>
        table, td, th {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        th, td {
            padding: 2px 4px;
            text-align: left;
        }

        th {
            background-color: lightgray;
        }

        .main-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .header-strong {
            font-size: 12px;
            font-weight: bold;
        }

        footer {
            font-size: 10px;
            text-align: center;
            margin-top: 10px;
        }

        /* --- PRINT MODE --- */
        @media print {
            @page {
                size: 80mm auto;   /* lebar thermal roll */
                margin: 2mm;       /* tipis biar hemat kertas */
            }

            body {
                font-size: 12px;   /* kecil pas di struk */
            }

            table.dataTable {
                width: 100% !important;
            }

            table.dataTable th,
            table.dataTable td {
                padding: 2px 4px;
                white-space: nowrap;   /* biar ga wrap aneh */
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            List Koli
        </div>

        <div>
            <span class="header-strong">Toko IDM : {{ $kdtoko }} ({{ $nmtoko }})</span><br>
            <span class="header-strong">No SJ : {{ $noSJ }}</span><br>
            <span class="header-strong">No Pick : {{ $noPick }}</span>
        </div>            

        <table id="tablePrint">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Zona</th>
                    <th>Koli</th>
                    <th>Item</th>
                </tr>
            </thead>
            <tbody>
            @foreach($allData as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->no_koli }}</td>
                    <td>{{ $row->zona }}</td>
                    <td>{{ $row->jml_plu }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <footer>
        &copy; 2025 EDP ISSUING-PWT
    </footer>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script>

        // Memanggil fungsi cetak setelah dokumen selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            window.print();
        });

        
        $(document).ready(function() {
            let data = JSON.parse(sessionStorage.getItem("alldata")) || [];
            let toko = sessionStorage.getItem("kdtoko");
            let pick = sessionStorage.getItem("noPick");
            let nosj = sessionStorage.getItem("noSJ");

            if (!data || !toko || !pick || !nosj) {
                alert("Data tidak tersedia. Pastikan Anda memilih item yang valid.");
                window.close();
            } else {
                $('#kdToko').html("Toko IDM : " + toko);
                $('#nopick').html("Pick : " + pick);
                $('#nosj').html("SJ : " + nosj);

                let index = 1;
                data.forEach((element) => {
                    $('#tablePrint tbody').append(`
                        <tr>
                            <td>${index}</td>
                            <td>${element.zona}</td>
                            <td>${element.no_koli}</td>
                            <td>${element.jml_plu}</td>
                        </tr>
                    `);
                    index++;
                });

                window.onload = function () {
                    window.print();
                };
            }
        });
    </script>
</body>
</html>
