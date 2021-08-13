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

    {{-- Bootstrap --}}
    <style>

        html{font-family:sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
        body{margin:0}

        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        /* .row{margin-right:-15px;margin-left:-15px} */
        
        .col-xs-1,.col-xs-1-5,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}
        .col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{display: inline-block;}
        .col-xs-12{width:100%}
        .col-xs-11{width:91.66666667%}
        .col-xs-10{width:83.33333333%}
        .col-xs-9{width:75%}
        .col-xs-8{width:66.66666667%}
        .col-xs-7{width:58.33333333%}
        .col-xs-6{width:50%}
        .col-xs-5{width:41.66666667%}
        .col-xs-4{width:33.33333333%}
        .col-xs-3{width:25%}
        .col-xs-2{width:16.66666667%}
        .col-xs-1{width:8.33333333%}
        .col-xs-1-5{width:12%}

        .col-xs-9, .col-xs-2, .col-xs-1, .col-xs-1-5 {
            display: inline-block;
            float: none;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }
    </style>




    <style>
        .item-wrap {
            margin-bottom: 3em;
        }
        .heading-item h3 {
            font-size: 30px;
            font-weight: 900;
            margin-bottom: 1em;
        }
        .items {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border: 1px solid #e6e6e6;
            border-radius: 5px;
            padding: 10px 10px;
            padding-top: 20px;
            width: 100%;
        }
        .items .text {
            padding: 0 10px;
        }
        .product .text .content {
            flex-direction: column;
        }
        .product .text .content .name {
            font-size: 1em;
            font-weight: bold;
        }
        .product .text .content .description {
            font-size: 14px;
            line-height: 16px;
        }
        .items .text p {
            margin-bottom: 0;
            color: rgba(0, 0, 0, 0.8);
        }
        .items .text p {
            margin-bottom: 0;
            color: rgba(0, 0, 0, 0.8);
        }
        .product .text .content .price > span {
            /* border-bottom: 3px solid #ffa323; */
            color: #ffa323;
            font-weight: 600;
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
    <br/>
    <div class="container-fluid">
        @php
            $index = 0;
        @endphp
        @foreach ($categories as $category)
            <div class="row" @if ($index < (count($categories) - 1)) style="page-break-after: always;" @endif>
                <div class="col-xs-12 item-wrap">
                    <div class="heading-menu text-center">
                        <h3>{{ $category['name'] }}</h3>
                    </div>

                    @foreach ($category['products'] as $product)
                    <div class="row items product">
                        <div class="col-xs-9">
                            <div class="text">
                                <div class="content">
                                    <h3 class="name">{{ $product->name }} <span style="font-weight: normal;">(Cod: {{ $product->real_code }})</span></h3>
                                    <p class="description">Marca: {{ optional($product->brand)->name }}</p>
                                    <p class="price"><span>{{ $product->regular_price_str }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1-5">
                            @if (count($product->images))
                                <div class="item-img img" style="overflow: hidden;">
                                    <img src="{{ $product->images()->first()->url_img }}" alt="" class="img-fluid">
                                </div>
                            @endif
                        </div>
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
                    Company Slogan
                </td>
            </tr>
        </table>
    </div>
</body>
</html>