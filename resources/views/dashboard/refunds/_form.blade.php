<!-- MultiStep Form -->
<div class="container-fluid">
    <div class="row justify-content-center mt-0">
        <div class="col-12 text-center p-0 mb-2">
            <devolution-form
                crsf="{{ csrf_token() }}"
                :customer-param="{{ $customerParam ? $customerParam : json_encode(new stdClass()) }}"
                :customers="{{ $customers }}"  
                :products-available-to-buy="{{ $products }}"
                :products-for-refund="{{ $productsForRefund }}"
                url-discount="{{ route('config.discount') }}"
                url-products="{{ route('productos.index') }}"
                url-refund="{{ route('devoluciones.store') }}"
                url-refund-products="{{ route('devoluciones.create') }}"
                url-customer="{{ route('clientes.index') }}"
            ></devolution-form>
        </div>
    </div>
</div>

@push('css')
    <style>
        #msform {
            text-align: center;
            position: relative;
            margin-top: 20px
        }
        #msform fieldset .form-card {
            background: white;
            border: 0 none;
            border-radius: 0px;
            padding: 20px 40px 30px 40px;
            box-sizing: border-box;
            position: relative
        }
        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            margin: 0;
            padding-bottom: 20px;
            position: relative;
            width: 100%;
        }
        #msform fieldset:not(:first-of-type) {
            /* display: none; */
        }
        #msform fieldset .form-card {
            color: #9E9E9E;
            text-align: left;
        }

        select.list-dt {
            border: none;
            border-bottom: 1px solid #ccc;
            margin: 2px;
            outline: 0;
            padding: 2px 5px 3px 5px;
        }

        select.list-dt:focus {
            border-bottom: 2px solid skyblue
        }
        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
        }
        @media (max-width: 500px) {
            .fs-title {
                font-size: 18px;
            }
        }
        #progressbar {
            color: lightgrey;
            margin-bottom: 30px;
            overflow: hidden;
        }
        #progressbar .active {
            color: #000000;
        }
        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 20%;
            float: left;
            position: relative
        }
        #progressbar #customer-step:before {
            content: "1"
        }
        #progressbar #products-refund-step:before {
            content: "2"
        }
        #progressbar #products-order-step:before {
            content: "3"
        }
        #progressbar #payment-step:before {
            content: "4"
        }
        #progressbar #confirm-step:before {
            content: "5"
        }
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px
        }
        #progressbar li:after {
            background: lightgray;
            content: '';
            height: 2px;
            position: absolute;
            left: 0;
            top: 25px;
            width: 100%;
            z-index: -1;
        }
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #3c4b64
        }
        @media(max-width: 500px) {
            #progressbar li:before {
                width: 30px;
                height: 30px;
                line-height: 27px;
            }
            #progressbar li:after {
                top: 15px;
            }
        }
        .radio-group {
            position: relative;
            margin-bottom: 25px
        }
        .radio {
            display: inline-block;
            height: 50px;
            width: 22%;
            border-radius: 0;
            background: #3c4b64;
            box-sizing: border-box;
            cursor: pointer;
            margin: 8px 2px
        }

        @media(max-width: 500px) {
            .radio {
                width: 47%;
            }
        }

        .radio label {
            align-items: center;
            color: #fff;
            display: flex;
            justify-content: center;
            line-height: 50px;
            text-align: center;
            width: 100%;
        }
        .radio:hover {
            box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.3)
        }
        .radio.selected {
            box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2);
            background: #07d807;
        }
        .fit-image {
            width: 100%;
            object-fit: cover
        }
    </style>
@endpush