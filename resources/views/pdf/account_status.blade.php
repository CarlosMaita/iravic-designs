<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        .table {
            border-collapse: collapse;
            border-spacing:0;
        }
        .table td {
            padding: 5px 8px 0px 8px;
            vertical-align: top;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse; 
            font-family: sans-serif;
        }
        header {
            border-bottom: 2px solid;
            padding-bottom: 10px;
        }
        footer {
            border-top: 1px solid;
            border-bottom: 1px solid;
            position: fixed;
            bottom: -40px;
            left: 0px; 
            right: 0px;
            height: 40px;
            width: 100%
        }
        footer td {
            padding: 0;
        }
        footer td p {
            margin: 0;
            margin-bottom: 0px;
        }

        .phone {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
        }

        footer .social p {
            font-size: 14px;
            line-height: 28px;
            margin: 0px 0;
        }
        footer .social img {
            height: 16px;
            width: 16px;
        }

        @page {
            margin-bottom: 100px!important;
        }

        .table-customer td {
            font-size: 12px;
            border-bottom: 1px solid;
            padding: 5px 0px;
        }
        .table-customer td.td-title {
            border: unset;
            border-bottom-color: transparent;
        }
        .table-operations {
            border-bottom: 2px solid;
        }
        .table-operations th {
            border: 2px solid;
            padding: 10px 5px;
        }
        .table-operations td {
            border: 1px solid;
            font-size: 12px;
            padding: 7px 5px;
        }
        .table-operations tr td:first-child {
            /* border-left: 1px solid #fff!important; */
        }
        .table-operations tr td:nth-child(5) {
            /* border-right: 1px solid #fff!important; */
            padding: 0px 5px;
        }
    </style>

    <style>
        .container-wrapper {
            margin-top: 11.35px;
        }
        .block.full {
            padding: 20px 0px;
        }
        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .row {
            /* display: flex; */
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-10, .col-11, .col-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
            display: inline-block;
        }
        .col-12 {
            width: 100%;
        }
        .col-11 {
            width: 91.66666667%;
        }
        .col-10 {
            width: 83.33333333%;
        }
        .col-9 {
            width: 75%;
        }
        .col-8 {
            width: 66.66666667%;
        }
        .col-7 {
            width: 58.33333333%;
        }
        .col-6 {
            width: 45.3%;
        }
        .col-5 {
            width: 41.66666667%;
        }
        .col-4 {
            width: 32.333333%;
        }
        .col-3 {
            width: 25%;
        }
        .col-2 {
            width: 16.66666667%;
        }
        .col-1 {
            width: 8.33333333%;
        }

        .m-0 {
            margin: 0;
        }
        .mb-0 {
            margin-bottom: 0;
        }
        .pl-0 {
            padding-left: 0px;
        }
        .pr-0 {
            padding-right: 0px;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <header>
        <table width="100%">
            <tr>
                <td align="center" style="twidth: 100%;">
                    <img src="{{ asset('img/logo-black.png') }}" alt="Logo" width="64" class="logo"/>
                    {{-- <span style="font-size: 40px; font-weight: bold;">MN</span> <span style="color: gray; font-size: 24px; font-weight: bold; text-transform: uppercase;">calzados</span> --}}
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <td class="phone" width="50%">TEL.: 097 506 073</td>
                    <td class="social" width="50%">
                        <p style="margin: 0px 0;">
                            <span><img src="{{ asset('img/instagram.png') }}"> MN_CALZADOS_OK</span><span>  </span>
                            <span><img src="{{ asset('img/facebook.png') }}"> MN_calzados</span>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </footer>
    {{--  --}}
    <main>
        <div class="container-wrapper">
            <br>
            <table class="table-customer" width="100%">
                <tr>
                    <td class="td-title" width="8%"><b>Fecha:</b> </td>
                    <td width="72%">{{ $date }}</td>
                    <td class="td-title" width="10%">N Cliente:</b> </td>
                    <td class="text-center" width="10%">{{ $customer->id }}</td>
                </tr>
                <tr>
                    <td class="td-title" width="8%"><b>Nombre:</b> </td>
                    <td width="90%" colspan="3">{{ $customer->name }}</td>
                </tr>
                <tr>
                    <td class="td-title" width="8%"><b>Calle:</b> </td>
                    <td width="90%" colspan="3">{{ $customer->address }}</td>
                </tr>
            </table>
            {{--  --}}
            <br><br>
            {{--  --}}
            <table class="table-operations" width="100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>$</th>
                        <th>Tipo</th>
                        <th>Balance</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operations as $operation)
                    <tr>
                        <td class="text-center" style="width: 15%;">{{ $operation->date }}</td>
                        <td class="text-center" style="width: 15%;">{{ $operation->amount }}</td>
                        <td class="text-center" style="width: 17%;">{{ $operation->type }}</td>
                        <td class="text-center" style="width: 15%;">{{ $operation->balance }}</td>
                        <td style="width: 38%;">{{ $operation->comment }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>   
</html>