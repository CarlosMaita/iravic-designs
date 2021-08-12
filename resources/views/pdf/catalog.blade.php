<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catálogo</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .products table {
            margin: 15px;
        }
        .products h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>
    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    {{-- <h3></h3> --}}
                    <pre>
                        Street 15
                        123456 City
                        United Kingdom
                    </pre>
                </td>
                <td align="center">
                </td>
                <td align="right" style="width: 40%;">
                    <img src="{{ asset('img/no_image.jpg') }}" alt="Logo" width="64" class="logo"/>
                </td>
            </tr>
        </table>
    </div>
    {{--  --}}
    <br/>
    <div class="products">
        <h3>Productos</h3>
        <hr>

        @foreach ($products as $product)
        <table width="100%">
            <thead>
                <tr>
                    <th align="left" width="10%">Código</th>
                    <th align="left" width="55%">Nombre</th>
                    <th align="left" width="20%">Marca</th>
                    <th align="left" width="15%">Precio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ optional($product->brand)->name }}</td>
                    <td>{{ $product->regular_price_str }}</td>
                </tr>

                @if (count($product->product_combinations)) 
                    <tr>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th align="left" width="10%">Código</th>
                                    <th align="left" width="55%">Color</th>
                                    <th align="left" width="20%">Talla</th>
                                    <th align="left" width="15%">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->product_combinations as $product_combination)
                                    <tr>
                                        <td>{{ $product_combination->real_code }}</td>
                                        <td>{{ optional($product_combination->color)->name }}</td>
                                        <td>{{ optional($product_combination->size)->name }}</td>
                                        <td>{{ $product_combination->regular_price_str }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </tr>
                @endif

                @if (count($product->images))
                    <tr>
                        <td colspan="4" align="center">
                            <img src="{{ $product->images()->first()->url_img }}" style="margin-top: 1rem; max-width: 450px;">
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <hr>
        @endforeach
    </div>
    {{--  --}}
    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                </td>
                <td align="right" style="width: 50%;">
                    Company Slogan
                </td>
            </tr>
        </table>
    </div>
</body>
</html>