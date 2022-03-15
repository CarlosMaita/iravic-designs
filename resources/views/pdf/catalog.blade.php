<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $customer->name }} Historial de Cuenta</title>

</head>
<body>
    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    {{-- <h3>{{ config('app.name') }}</h3> --}}
                    <pre>
                        <b>{{ config('app.name') }}</b>
                        cel: 097506073
                        tel: 2347 75 97
                        Local: av. Río de la plata 14
                    </pre>
                </td>
                <td align="center">
                </td>
                <td align="right" style="width: 40%;">
                    <img src="{{ asset('img/logo-white.png') }}" alt="Logo" width="64" class="logo"/>
                </td>
            </tr>
        </table>
    </div>
    <br/>
    <div class="container-fluid">
        @php
            $index = 0;
        @endphp
        @foreach ($categories as $category)
            <div class="row">
                {{--  @if ($index < (count($categories) - 1)) style="page-break-after: always;" @endif --}}
                <div class="col-xs-12 item-wrap">
                    <div class="heading-menu text-center">
                        <h3>{{ $category['name'] }}</h3>
                    </div>
                    {{--  --}}
                    @foreach ($category['products'] as $product)
                    <div class="row items product">
                        <div class="col-xs-9">
                            <div class="text">
                                <div class="content">
                                    <h3 class="name">{{ $product->name }} <span style="font-weight: normal;">(Cod: {{ $product->real_code }})</span></h3>
                                    <p class="description">Marca: {{ optional($product->brand)->name }}</p>

                                    @if (!$product->product_combinations()->count())
                                    <p class="price"><span class="font-weight-bold">{{ $product->regular_price_str }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1-5">
                            <div class="item-img img" style="overflow: hidden;">    
                                @if (count($product->images))
                                    <img src="{{ $product->images()->first()->url_img }}" class="img-fluid">
                                @else
                                    <img src="{{ asset('img/no_image.jpg') }}" class="img-fluid">
                                @endif
                            </div>
                        </div>

                        @if (isset($product['combinations'])) 
                        <hr>
                        <div class="col-xs-12">
                            <p>Disponible en:</p>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table" width="100%;" style=>
                                        <thead>
                                            <tr>
                                                <th align="left" width="10%;">Cod</th>
                                                <th align="left" width="30%;">Color</th>
                                                <th align="left" width="30%;">Talla</th>
                                                <th align="right" width="30%;">Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product['combinations'] as $combination)
                                                <tr>
                                                    <td>{{ $combination->real_code }}</td>
                                                    <td>{{ optional($combination->color)->name }}</td>
                                                    <td>{{ optional($combination->size)->name }}</td>
                                                    <td align="right" style="color: #ffa323; font-weight: 700;">{{ $combination->regular_price_str }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @php
                $index +=1;
            @endphp
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
                    Desarrollado por <a href="https://brocsoft.com" target="_blank">Brocsoft</a>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>