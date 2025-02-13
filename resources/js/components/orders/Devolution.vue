<template>
    <div>
        <div class="card px-0 pt-4 pb-0 mt-3 mb-3" style="z-index: 0;">
            <!-- style="z-index: 0;" -->
            <p>Complete cada paso hasta llegar al final</p>
            <form id="form-refunds" autocomplete="off"  v-on:submit.prevent>
                <input type="hidden" name="_token" :value="crsf">
                <input v-if="customerParam.id" type="hidden" name="customer_param" :value="customerParam.id">
                <!--  -->
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <div id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar" class="p-0">
                                <li :class="{ 'active' : currentStep >= 1 }" id="customer-step"><strong>Cliente</strong></li>
                                <li :class="{ 'active' : currentStep >= 2 }" id="products-refund-step"><strong>Devolver</strong></li>
                                <li :class="{ 'active' : currentStep >= 3 }" id="products-order-step"><strong>Llevar</strong></li>
                                <li :class="{ 'active' : currentStep >= 4 }" id="payment-step"><strong>Pago</strong></li>
                                <li :class="{ 'active' : currentStep >= 5 }" id="confirm-step"><strong>Finalizar</strong></li>
                            </ul> 
                            <!-- fieldsets -->
                            <fieldset id="section-customer" :class="{ 'd-none' : currentStep != 1 }">
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title">Información de Cliente</h2>
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="customer">Cliente</label>
                                                <v-select
                                                    placeholder="Seleccionar"
                                                    v-model="customerSelected"
                                                    :options="customers"
                                                    :getOptionLabel="option => option.name ? option.name : ''"
                                                    @input="setCustomerIdSelected"
                                                >
                                                </v-select>
                                                <input type="hidden" name="customer_id" :value="customer_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="customerSelected" id="customer-selected-container" class="row">
                                        <div class="col-12">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <small class="form-text text-muted font-weight-bold text-success">Cliente Seleccionado</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombre</label>
                                                        <p class="form-control h-100">{{ customerSelected.name }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>DNI</label>
                                                        <p class="form-control h-100">{{ customerSelected.dni }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Teléfono</label>
                                                        <p class="form-control h-100">{{ customerSelected.telephone }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Calificación</label>
                                                        <p class="form-control h-100">{{ customerSelected.qualification }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Crédito máximo</label>
                                                        <p class="form-control h-100">{{ customerSelected.max_credit_str }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Saldo</label>
                                                        <p class="form-control h-100">{{ customerSelected.balance }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Dirección</label>
                                                        <p class="form-control h-100">{{ customerSelected.address }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="goNext" class="btn btn-info" type="button">Siguiente</button>
                            </fieldset>
                            <!--  -->
                            <fieldset id="section-refund" :class="{ 'd-none' : currentStep != 2 }">
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title">Productos que puede devolver</h2> 
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="product_refund">Producto</label>
                                                <div class="input-group mb-3">
                                                    <v-select placeholder="Seleccionar"
                                                                :options="productsAvailableForRefund"
                                                                v-model="productForRefundSelected"
                                                                label="name"
                                                                :value.sync="productForRefundSelected"
                                                    >
                                                    <!-- @input="setProductRefundSelected" -->
                                                    </v-select>
                                                    <div class="input-group-prepend">
                                                        <span @click="openModalProductToRefund()" class="input-group-text" type="button"><i class="fa fa-plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div v-if="productsSelectedForRefund.length" class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Productos seleccionados</small>
                                        </div>
                                    </div>
                                    <div v-if="productsSelectedForRefund.length" class="row">
                                        <div class="col-12">
                                            <table class="table-refund">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Venta</th>
                                                        <th scope="col">Producto</th>
                                                        <th scope="col">Código</th>
                                                        <th scope="col">Descripción</th>
                                                        <th scope="col">Precio</th>
                                                        <th scope="col">Crédito</th>
                                                        <th scope="col">Disponible</th>
                                                        <th scope="col">Cantidad</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <ProductItemToRefund 
                                                        v-for="(item, index) in productsSelectedForRefund" :key="item.id"
                                                        :item="item"
                                                        :index="index"
                                                        :can-remove="true"
                                                        @removeProduct="removeProductToRefund"
                                                        @updateQty="updateProductForRefundQty"
                                                    ></ProductItemToRefund>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div v-if="productsSelectedForRefund.length" class="row mt-3">
                                        <div class="col-12">
                                            <p class="font-weight-bold text-dark mb-0">Total Devolución Crédito: {{ totalDevolucionCredito | toCurrency }}</p>
                                            <p class="font-weight-bold text-dark mb-0">Total Devolución Débito: {{ totalDevolucionDebito | toCurrency }}</p>
                                            <p class="font-weight-bold text-dark mb-0">Total Devolución: {{ totalDevolucion | toCurrency }}</p>
                                        </div>
                                    </div>
                                </div>
                                <button @click="goPrevious" class="btn btn-secondary" type="button">Anterior</button>
                                <button @click="goNext" class="btn btn-info" type="button">Siguiente</button>
                            </fieldset>
                            <!--  -->
                            <fieldset id="section-buy" :class="{ 'd-none' : currentStep != 3 }">
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title">Productos que se puede llevar</h2> 
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="product">Producto</label>
                                                <div class="input-group mb-3">
                                                    <v-select placeholder="Seleccionar"
                                                            :options="productsAvailableToBuy"
                                                            v-model="productToBuySelected"
                                                            :value.sync="productToBuySelected"
                                                            :getOptionLabel="getProductsToBuyOptionLabel"
                                                    >
                                                    <!-- :selectable="option => option.stock_user > 0" -->
                                                    <!-- :getOptionLabel="getProductsToBuyOptionLabel" -->
                                                    <!-- :getOptionLabel="option => option.is_regular ? option.name : ''" -->
                                                    <!-- :get-option-label="(item) => item.name" -->
                                                    </v-select>
                                                    <div class="input-group-prepend">
                                                        <span @click="openModalProductToBuy" class="input-group-text" type="button"><i class="fa fa-plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div v-if="productsSelectedToBuy.length" class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Productos seleccionados</small>
                                        </div>
                                    </div>
                                    <div v-if="productsSelectedToBuy.length" class="row">
                                        <div class="col-12">
                                            <table class="table-refund">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Código</th>
                                                        <th scope="col">Descripción</th>
                                                        <th scope="col">Precio</th>
                                                        <th scope="col">Disponible</th>
                                                        <th scope="col">Cantidad</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <ProductItemToBuy 
                                                        v-for="(item, index) in productsSelectedToBuy" :key="`product-${item.id}-buy`"
                                                        :item="item"
                                                        :index="index"
                                                        :can-remove="true"
                                                        @removeProduct="removeProductToBuy"
                                                        @updateQty="updateProductForBuyQty"
                                                    ></ProductItemToBuy>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div v-if="productsSelectedToBuy.length" class="row mt-3">
                                        <div class="col-12">
                                            <p class="font-weight-bold text-dark">Total: {{ totalCompra | toCurrency  }}</p>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-secondary" type="button" @click="goPrevious">Anterior</button>
                                <button class="btn btn-info" type="button" @click="goNext">Siguiente</button>
                            </fieldset>
                            <!--  -->
                            <fieldset id="section-payment" :class="{ 'd-none' : currentStep != 4 }">
                                <div id="card-payment" class="form-card py-1 px-md-4 px-2">
                                    <h2 class="fs-title">Información de Pago</h2>
                                    <div class="radio-group text-center">
                                        <div class='radio' data-value="bankwire" for="bankwire" :class="{ 'selected' : paymentMethodSelected == 'bankwire' }">
                                            <label for="bankwire">Transferencia</label>
                                            <input v-model="paymentMethodSelected" id="bankwire" class="d-none" type="radio" name="payment_method" value="bankwire">
                                        </div>
                                        <div class='radio' data-value="cash" for="cash" :class="{ 'selected' : paymentMethodSelected == 'cash' }">
                                            <label for="cash">Efectivo</label>
                                            <input v-model="paymentMethodSelected" id="cash" class="d-none" type="radio" name="payment_method" value="cash">
                                        </div>
                                        <div class='radio' data-value="card" for="card" :class="{ 'selected' : paymentMethodSelected == 'card' }">
                                            <label for="card">Tarjeta</label>
                                            <input v-model="paymentMethodSelected" id="card" class="d-none" type="radio" name="payment_method" value="card">
                                        </div>
                                        <div class='radio' data-value="credit" for="credit" :class="{ 'selected' : paymentMethodSelected == 'credit' }">
                                            <label for="credit">Crédito </label>
                                            <input v-model="paymentMethodSelected" id="credit" class="d-none" type="radio" name="payment_method" value="credit">
                                        </div>
                                        <br>
                                    </div>
                                    <div v-if="customerSelected" class="row">
                                        <div class="col-md-12">
                                            <p class="font-weight-bold text-dark mb-1">Crédito Máximo: {{ customerSelected.max_credit_str }}</p>
                                            <p class="font-weight-bold text-dark mb-1">Saldo: {{ customerSelected.balance }}</p>
                                            <p class="font-weight-bold text-dark mb-1">Crédito Disponible: {{ customerSelected.available_credit_str }}</p>
                                            <hr>
                                            <div class="row mb-4">
                                                <div class="col-sm-6">
                                                    <div class="card mb-0" style="height: 100%;">
                                                    <div class="card-body">
                                                        <h5 class="card-title font-weight-bold text-dark">Devolución</h5>
                                                        <p class="card-text mb-0"><b>Total Devolución Crédito:</b> {{ totalDevolucionCredito | toCurrency }}</p>
                                                        <p class="card-text mb-0"><b>Total Devolución Débito:</b> {{ totalDevolucionDebito | toCurrency }}</p>
                                                        <p class="card-text mb-0"><b>Total Devolución:</b> {{ totalDevolucion | toCurrency }}</p>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card mb-0" style="height: 100%;">
                                                        <div class="card-body">
                                                            <h5 class="card-title font-weight-bold text-dark">Nueva compra</h5>
                                                            <p class="card-text mb-0"><b>Subtotal:</b> {{ subtotalCompra | toCurrency }}</p>
                                                            <p class="card-text mb-0"><b>Descuento:</b> {{ discount | toCurrency }}</p>
                                                            <p class="card-text mb-0"><b>Total Compra:</b> {{ totalCompra | toCurrency }}</p>
                                                            <!-- <p class="card-text"><b>Total nuevo a Cancelar:</b> {{ totalCancelar | toCurrency }}</p> -->
                                                            <button @click="openModalDiscount" class="btn btn-primary">Aplicar descuento</button>
                                                            <input type="hidden" name="discount" :value="discount">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- frequency collection component -->
                                            <input type="hidden" id="total-order" :value="totalCancelar">
                                            <credit-information-component v-if="paymentMethodSelected  == 'credit'"></credit-information-component>
                                            <!-- end frequency collection component -->
                                            <hr> 
                                            <div v-if="paymentMethodSelected == 'credit' && totalCompra > totalDevolucionCredito" class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-md-10 mx-auto">
                                                            <div class="alert alert-warning" role="alert">
                                                                El monto total a cancelar es superior al total para Devolución de Crédito.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8 mx-auto">
                                                            <div class="custom-control custom-checkbox text-center">
                                                                <input v-model="isCreditShared"
                                                                        type="checkbox" 
                                                                        class="custom-control-input" 
                                                                        id="checkCustomerDebt"
                                                                        name="is_credit_shared"
                                                                        value="1"
                                                                >
                                                                <label class="custom-control-label" for="checkCustomerDebt">El Crédito se asignara a otro cliente?</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="canShowTotalCancelar()" class="row mb-4">
                                                        <div class="col-md-8 mx-auto">
                                                            <p class="mb-0 text-center">Monto a cancelar: {{ totalCancelar | toCurrency }}</p>
                                                        </div>
                                                    </div>
                                                    <div v-if="isCreditShared" class="row">
                                                        <div class="col-md-6 mx-auto">
                                                            <div class="form-group">
                                                                <label>Cliente</label>
                                                                <!-- <div class="input-group mb-3"> -->
                                                                    <v-select
                                                                        placeholder="Seleccionar"
                                                                        v-model="customerNewCreditSelected"
                                                                        :options="customers"
                                                                        :getOptionLabel="option => option.name ? option.name : ''"
                                                                        :selectable="option => option.id != customerSelected.id"
                                                                        @input="setCustomerIdNewDebtSelected"
                                                                    ></v-select>
                                                                    <!-- <div class="input-group-prepend">
                                                                        <span @click="openModalNewCustomer" class="input-group-text" type="button"><i class="fa fa-plus"></i></span>
                                                                    </div> -->
                                                                    <input type="hidden" name="customer_id_new_credit" :value="customer_id_new_credit">
                                                                <!-- </div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="form-card mt-3" :class="{'d-none' : !needsNewVisit }">
                                        <hr>
                                        <div class="row mt-3">
                                            <div class="col-md-12 mx-auto">
                                                <div class="custom-control custom-checkbox text-center">
                                                    <input v-model="enableNewVisit"
                                                            type="checkbox" 
                                                            class="custom-control-input" 
                                                            id="enableNewVisit"
                                                            name="enable_new_visit"
                                                            value="1"
                                                    >
                                                    <label class="custom-control-label" for="enableNewVisit">¿Desea agendar una visita? El cliente <b>{{ customerNameVisit }}</b> tendrá deuda al final la devolución/compra.</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" :class="{'d-none' : !enableNewVisit }">
                                            <div class="col-md-6 mx-auto">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="visit-date">Fecha</label>
                                                            <input ref="visit_date_ref" class="form-control datepicker-form" id="visit-date" name="visit_date" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="visit-comment">Comentario</label>
                                                            <textarea v-model="visitComment" class="form-control" name="visit_comment" id="visit-comment" cols="30" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <button class="btn btn-secondary" type="button" @click="goPrevious">Anterior</button>
                                <button class="btn btn-info" type="button" @click="goNext">Siguiente</button>
                            </fieldset>
                            <!-- {{--  --}} -->
                            <fieldset :class="{ 'd-none' : currentStep != 5 }">
                                <div v-if="productsSelectedForRefund.length" class="form-card px-md-4 px-2">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Resumen Productos a devolver</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table-refund">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Venta</th>
                                                        <th scope="col">Producto</th>
                                                        <th scope="col">Código</th>
                                                        <th scope="col">Descripción</th>
                                                        <th scope="col">Precio</th>
                                                        <th scope="col">Crédito</th>
                                                        <th scope="col">Disponible</th>
                                                        <th scope="col">Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <ProductItemToRefund 
                                                        v-for="(item, index) in productsSelectedForRefund" :key="item.id"
                                                        :item="item"
                                                        :index="index"
                                                        :can-remove="false"
                                                        @removeProduct="removeProductToRefund"
                                                        @updateQty="updateProductForRefundQty"
                                                    ></ProductItemToRefund>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div v-if="productsSelectedToBuy.length" class="form-card px-md-4 px-2">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Resumen Productos a llevarse</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table-refund">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Código</th>
                                                        <th scope="col">Descripción</th>
                                                        <th scope="col">Precio</th>
                                                        <th scope="col">Disponible</th>
                                                        <th scope="col">Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <ProductItemToBuy 
                                                        v-for="(item, index) in productsSelectedToBuy" :key="`product-${item.id}-buy-resumen`"
                                                        :item="item"
                                                        :index="index"
                                                        :can-remove="false"
                                                        @removeProduct="removeProductToBuy"
                                                        @updateQty="updateProductForBuyQty"
                                                    ></ProductItemToBuy>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title text-center mt-2">Confirmar Devolución</h2>
                                    <div class="row mt-3 mb-4">
                                        <div class="col-12">
                                            <p v-if="!productsSelectedToBuy.length" class="font-weight-bold text-dark text-center mb-0">Total Devolución: {{ totalDevolucion | toCurrency }}</p>
                                            <p v-if="productsSelectedToBuy.length" class="font-weight-bold text-dark text-center mb-0">Método de pago: 
                                                <span v-if="paymentMethodSelected == 'credit'">Credito</span>
                                                <span v-else-if="paymentMethodSelected == 'cash'">Efectivo</span>
                                                <span v-else-if="paymentMethodSelected == 'card'">Tarjeta</span>
                                                <span v-else-if="paymentMethodSelected == 'bankwire'">Transferencia</span>
                                            </p>
                                            <p v-if="canShowTotalCancelar(true)" class="font-weight-bold text-dark text-center mb-0">Total a cancelar: {{ totalCancelar | toCurrency }}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <div class="card mb-0" style="height: 100%;">
                                            <div class="card-body">
                                                <h5 class="card-title font-weight-bold text-dark">Devolución</h5>
                                                <p class="card-text mb-0"><b>Total Devolución Crédito:</b> {{ totalDevolucionCredito | toCurrency }}</p>
                                                <p class="card-text mb-0"><b>Total Devolución Débito:</b> {{ totalDevolucionDebito | toCurrency }}</p>
                                                <p class="card-text mb-0"><b>Total Devolución:</b> {{ totalDevolucion | toCurrency }}</p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card mb-0" style="height: 100%;">
                                                <div class="card-body">
                                                    <h5 class="card-title font-weight-bold text-dark">Nueva compra</h5>
                                                    <p class="card-text mb-0"><b>Subtotal:</b> {{ subtotalCompra | toCurrency }}</p>
                                                    <p class="card-text mb-0"><b>Descuento:</b> {{ discount | toCurrency }}</p>
                                                    <p class="card-text mb-0"><b>Total Compra:</b> {{ totalCompra | toCurrency }}</p>
                                                    <button @click="openModalDiscount" class="btn btn-primary">Aplicar descuento</button>
                                                    <input type="hidden" name="discount" :value="discount">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="goPrevious" class="btn btn-secondary" type="button">Anterior</button>
                                <button @click="submitForm" class="btn btn-success" type="button">Confirmar</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </form>
            <!--  -->
        </div>
        <!--  -->
        <ModalDiscount
            ref="modalDiscount"
            :url-discount="urlDiscount"
            @addDiscount="handleAddDiscount"
        ></ModalDiscount>
        <!--  -->
        <ModalProductToRefund 
            ref="modalProductToRefund"
            @addProduct="handleAddProductToRefund"
        ></ModalProductToRefund>
        <!--  -->
        <ModalProductStock 
            ref="modalProductStock"
            @addProduct="handleAddProductToBuy"
        ></ModalProductStock>
    </div>
</template>

<script>
    import ModalDiscount from './ModalDiscount.vue'
    import ModalProductStock from './ModalProductStocks.vue'
    import ModalProductToRefund from './ModalProductToRefund.vue'
    import ProductItemToRefund from './ProductItemToRefund.vue'
    import ProductItemToBuy from './ProductItemToBuy.vue'
    import CreditInformationComponent from './CreditInformationComponent.vue'

    export default {
        components: {
            ModalDiscount,
            ModalProductStock,
            ModalProductToRefund,
            ProductItemToRefund,
            ProductItemToBuy,
            CreditInformationComponent
        },
        props: {
            crsf: {
                type: String,
                default: ''
            },
            customerParam: {
                type: Object,
                default: null
            },
            customers: {
                type: Array,
                default: []
            },
            productsForRefund: {
                type: Array,
                default: []
            },
            productsAvailableToBuy: {
                type: Array,
                default: []
            },
            urlDiscount: {
                type: String,
                default: ''  
            },
            urlCustomer: {
                type: String,
                default: ''
            },
            urlProducts: {
                type: String,
                default: ''
            },
            urlRefund: {
                type: String,
                default: ''
            },
            urlRefundProducts: {
                type: String,
                default: ''
            }
        },
        data: () => ({
            enableNewVisit: 0,
            visitDate: null,
            visitComment: null,
            renderedDatepicker: false,
            customer_id: null,
            customer_id_new_credit: null,
            customerNewCreditSelected: null,
            customerSelected: null,
            currentStep: 1,
            isCreditShared: 0,
            discount: 0,
            paymentMethodSelected: null,
            productForRefundSelected: null,
            productsAvailableForRefund: [],
            productsSelectedForRefund: [],
            productsSelectedToBuy: [],
            productToBuySelected: null,
            loading: false,
            mounted: false,
        }),
	    computed: {
            /**
             * Retorna el nombre del cliente al que se le aplicara la visita
             * Si selecciona un cliente para la nueva deuda, este sera el nombre a mostrar
             * Sino, sera el del cliente de la devolucion
             */
            customerNameVisit() {
                if (this.customerNewCreditSelected) {
                    return this.customerNewCreditSelected.name;
                } else if (this.customerSelected) {
                    return this.customerSelected.name
                }

                return '';
            },

            /**
             * Verifica si hace falta pautar una nueva visita
             * Debe tener productos a comprar y que el pago sea a credito
             * Ademas, el cliente debe quedar con balance deudor
             */
            needsNewVisit: function() {
                if (!this.productsSelectedToBuy.length || !this.paymentMethodSelected) return false;

                let result = 0;

                if (this.customerNewCreditSelected) {
                    if (this.paymentMethodSelected == 'credit') {
                        result = this.customerNewCreditSelected.balance_numeric - this.totalCancelar;
                    } else {
                        this.customerNewCreditSelected.balance_numeric
                    }
                } else if (this.customerSelected) {
                    if (this.paymentMethodSelected == 'credit') {
                        result = this.customerSelected.balance_numeric - this.totalCancelar;
                    } else {
                        result = this.customerSelected.balance_numeric;
                    }
                }

                return result < 0 ? true : false;
            },

            /**
             * Calcula el subtotal de compra nueva, sin contar el descuento
             */
            subtotalCompra: function () {
                return this.productsSelectedToBuy.reduce(function(prev, cur) {
                    return prev + (cur.qty * cur.product.regular_price);
                }, 0.00);
            },

            /**
             * Calcula el total de compra nueva, menos el descuento
             */
            totalCompra: function() {
                return this.subtotalCompra - this.discount;
            },

            /**
             * Calcula el total a devolver de productos comprados a credito
             */
            totalDevolucionCredito: function () {
                return this.productsSelectedForRefund.filter((item) => {
                    return item.orderProduct.order.payed_credit == 1
                })
                .reduce(function(prev, cur) {
                    return prev + (cur.qty * cur.orderProduct.product_price);
                }, 0.00);
            },

            /**
             * Calcula el total a devolver de productos comprados a debito
             */
            totalDevolucionDebito: function () {
                return this.productsSelectedForRefund.filter((item) => {
                    return item.orderProduct.order.payed_credit != 1
                })
                .reduce(function(prev, cur) {
                    return prev + (cur.qty * cur.orderProduct.product_price);
                }, 0.00);
            },

            /**
             * Calcula el total a devolver
             */
            totalDevolucion: function () {
                return this.totalDevolucionCredito + this.totalDevolucionDebito;
            },

            /**
             * Calcular el total de la compra
             * Toma el cuenta el total devuelto de productos comprados a credito y a debito si la deuda es compartida
             */
            totalCancelar: function () {
                // if (
                //     this.paymentMethodSelected == 'credit' 
                // ) {
                //     if( this.totalCompra > this.totalDevolucionCredito && this.isCreditShared == 1) {
                //         return this.totalCompra - (this.totalDevolucion + this.customerSelected.balance_numeric);
                //     }
                //     return this.totalCompra - (this.totalDevolucion + this.customerSelected.balance_numeric);
                // }

                return this.totalCompra - (this.totalDevolucion + this.customerSelected.balance_numeric);
            }
	    },
        async mounted() {
            if (typeof this.customerParam.id != 'undefined' ) {
                this.customerSelected = this.customerParam;
                this.customer_id = this.customerParam.id;
            }

            this.productsAvailableForRefund = this.productsForRefund; // Setea productos disponibles para devolucion
            this.mounted = true;
        },
        methods: {
            /**
             * Valida si puede mostrar el total a cancelar.
             * El metodo de pago debe ser a credito
             * Y el total debe ser superior al monto de la devolucion
             */
            canShowTotalCancelar(withCustomer = false) {
                if (withCustomer) {
                    return this.paymentMethodSelected == 'credit' 
                        && this.totalCompra > this.totalDevolucionCredito  
                        && this.customerNewCreditSelected;
                } else {
                    return this.paymentMethodSelected == 'credit' 
                        && this.totalCompra > this.totalDevolucionCredito;
                }
            },

            /**
             * Setea el cliente seleccionado, porque el Select vue envia el objeto
             */
            setCustomerIdSelected(value) {
                this.customer_id = value.id;
                this.resetDevolution();
            },

            /**
             * Setea el cliente seleccionado para nueva deuda, porque el Select vue envia el objeto
             */
            setCustomerIdNewDebtSelected(value) {
                this.customer_id_new_credit = value.id;
            },

            /**
             * Proceso el envio del formulario a la api
             */
            submitForm() {
                var form = $('#form-refunds')[0];
                var formData = new FormData(form);

                $.ajax({
                        url: this.urlRefund,
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        data: formData,
                        processData: false,
                        contentType: false,
                    success: function (response) {
                        if (response.success) {
                            window.location.href = response.data.redirect;
                        } else if (response.message){
                            new Noty({
                                text: response.message,
                                type: 'error'
                            }).show();
                        } else if (response.error) {
                            new Noty({
                                text: response.error,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: "No se ha podido realizar la operación en este momento.",
                                type: 'error'
                            }).show();
                        }
                    },
                    error: function (e) {
                        if (e.responseJSON.errors) {
                            $.each(e.responseJSON.errors, function (index, element) {
                                if ($.isArray(element)) {
                                    new Noty({
                                        text: element[0],
                                        type: 'error'
                                    }).show();
                                }
                            });
                        } else if (e.responseJSON.error){
                            new Noty({
                                text: e.responseJSON.error,
                                type: 'error'
                            }).show();
                        } else if (e.responseJSON.message){
                            new Noty({
                                text: e.responseJSON.message,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: "No se ha podido realizar la operación en este momento.",
                                type: 'error'
                            }).show();
                        }
                    }
                });
            },

            /**
             * Valida si puede avanzar al siguiente paso del formulario
             */
            canGoNextStep() {
                if (
                    this.currentStep == 1 
                    && !this.customerSelected
                ) {
                    new Noty({
                        text: "Debe seleccionar un cliente.",
                        type: 'error'
                    }).show();
                    return false;
                } else if (this.currentStep == 2) {
                    var productsWithQty = this.productsSelectedForRefund.filter((item) => item.qty > 0);

                    if (!productsWithQty.length) {
                        new Noty({
                            text: "Debe seleccionar al menos 1 producto a devolver.",
                            type: 'error'
                        }).show();

                        return false;
                    }
                } else if (
                    this.currentStep == 4 
                    && this.productsSelectedToBuy.length 
                    && !this.paymentMethodSelected
                ) {
                    new Noty({
                        text: "Debe seleccionar un método de pago.",
                        type: 'error'
                    }).show();

                    return false;
                } else if (
                    this.currentStep == 4 
                    && this.productsSelectedToBuy.length 
                    && this.paymentMethodSelected 
                    && this.paymentMethodSelected == 'credit'
                    && this.isCreditShared
                    && !this.customerNewCreditSelected
                ) {
                    new Noty({
                        text: "Debe seleccionar el cliente al que se le asignara el nuevo Crédito.",
                        type: 'error'
                    }).show();

                    return false;
                }

                return true;
            },

            /**
             * Avanza un paso en el formulario
             * - Valida que se cumplan los datos requeridos para avanzar
             * - Si no selecciona un producto a comprar, se salta el paso 4 y avanza de una vez hasta el resumen
             * - Si pasa del paso 4 al 5, llama a la funcion para setear el datepicker solamente si no ha sido renderizado
             */
            goNext() {
                if (this.canGoNextStep()) {
                    if (this.currentStep == 3 && !this.productsSelectedToBuy.length) {
                        this.currentStep += 2;
                    } else {
                        this.currentStep += 1;
                    }

                    if (this.currentStep == 4 && !this.renderedDatepicker) {
                        this.setDatePicker();

                        // $(this.$refs.datepicker).datepicker({
                        //     format: 'dd/mm/yyyy'
                        // })
                        // .on("changeDate", e => {
                        //     this.update(e.target.value);
                        // });

                        // update(value) {
                        //     $(this.$refs.datepicker).datepicker("update", value);
                        // }
                    }
                }
            },

            /**
             * Retrocede un paso del formulario
             */
            goPrevious() {
                if (this.currentStep == 5 && !this.productsSelectedToBuy.length) {
                    this.currentStep -= 2;
                } else {
                    this.currentStep -= 1;
                }
            },

            /**
             * Evento callback que recibe el descuento del modal para descuentos
             */
            handleAddDiscount(discount) {
                this.discount = discount;
            },

            /**
             * Procesa evento para agregar producto a comprar
             * Si intenta agregar un producto que ya estaba seleccionado, solamente actualiza el producto
             * Si no estaba seleccionado, lo agrega
             * 
             */
            handleAddProductToBuy(orderProduct) {
                const index = this.productsSelectedToBuy.findIndex(_item => _item.id === orderProduct.id);

                if (index > -1) {
                    Vue.set(this.productsSelectedToBuy, index, orderProduct);
                    this.$emit("updateQuantityToBuy", index, orderProduct.qty)
                } else {
                    this.productsSelectedToBuy.push(orderProduct);
                }
            },

            /**
             * Procesa evento para agregar producto a devolver
             * Si intenta agregar un producto que ya estaba seleccionado, solamente actualiza el producto
             * Si no estaba seleccionado, lo agrega
             * 
             */
            handleAddProductToRefund(products_to_refund) {
                products_to_refund.forEach((orderProduct) => {
                    const index = this.productsSelectedForRefund.findIndex(_item => _item.id === orderProduct.id);

                    if (index > -1) {
                        Vue.set(this.productsSelectedForRefund, index, orderProduct);
                        this.$emit("updateQuantityToRefund", index, orderProduct.qty)
                    } else {
                        this.productsSelectedForRefund.push(orderProduct);
                    }
                });
            },

            /**
             * Abre modal para aplicar descuento a la venta
             */
            openModalDiscount() {
                this.$refs.modalDiscount.showModal(this.subtotalCompra, this.discount);
            },

            /**
             * Abre modal para ingresar cantidad de devolucion de un producto
             */
            openModalProductToRefund() {
                if (this.productForRefundSelected) {
                    this.$refs.modalProductToRefund.showModal(this.productForRefundSelected);
                }
            },

            /**
             * Abre modal para ingresar cantidad a comprar de un producto
             */
            openModalProductToBuy() {
                if (this.productsSelectedToBuy) {
                    var self = this;

                    $.ajax({
                        url: `${this.urlProducts}/${this.productToBuySelected.id}`,
                        type: "GET",
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            var product = res;
                            if (product && product.stock_user > 0) {
                                self.$refs.modalProductStock.showModal(product);
                            } else if (product) {
                                swal({
                                    title: 'Sin stock asociado',
                                    html: `<div class="d-inline-flex flex-column text-left">
                                                <p class="mb-0">Depósito: ${product.stock_depot}</p>
                                                <p class="mb-0">Local: ${product.stock_local}</p>
                                                <p class="mb-0">Camión: ${product.stock_truck}</p>
                                            </div>`,
                                    type: 'info',
                                }).then(function () {
                                }).catch(swal.noop);
                            } else {
                                new Noty({
                                    text: "No se ha podido obtener la información del producto en este momento.",
                                    type: 'error'
                                }).show();
                            }
                        },
                        error: function(e) {
                            if (e.responseJSON.message) {
                                new Noty({
                                    text: e.responseJSON.message,
                                    type: 'error'
                                }).show();
                            } else if (e.responseJSON.error) {
                                new Noty({
                                    text: e.responseJSON.error,
                                    type: 'error'
                                }).show();
                            } else {
                                new Noty({
                                    text: "No se ha podido obtener la información del producto en este momento.",
                                    type: 'error'
                                }).show();
                            }
                        }
                    });
                }
            },

            /**
             * Elimina un producto seleccionado para devolucion
             */
            removeProductToRefund(index) {
                this.productsSelectedForRefund.splice(index, 1);
            },

            /**
             * Elimina un producto seleccionado compra
             */
            removeProductToBuy(index) {
                this.productsSelectedToBuy.splice(index, 1);
            },

            /**
             * Evento ejecutado en el select vue para actualizar y setear la cantidad de un producto para comprar
             */
            updateProductForBuyQty(index, qty) {
                this.productsSelectedToBuy[index].qty = qty;
            },

            /**
             * Evento ejecutado en el select vue para actualizar y setear la cantidad de un producto para devolucion
             */
            updateProductForRefundQty(index, qty) {
                this.productsSelectedForRefund[index].qty = qty;
            },

            getCustomerDataAjax(customer_id){
                try {
                    var url = `${this.urlCustomer}/${customer_id}`;
                    var self = this;
                    $.get(url, function(res) {
                        if(self.customerSelected.dni !== res.dni ) {
                            self.customerSelected = res;
                        }
                    })
                    .fail(function() {
                        // self.customerSelected = [];
    
                        new Noty({
                            text: "No se ha podido obtener la información del cliente en este momento.",
                            type: 'error'
                        }).show();
                    });
                } catch (error) {
                    console.error(error);
                }
                // $.ajax({
                //     url: `${URL_CUSTOMER}/${customer_id}`,
                //     type: "GET",
                //     processData: false,
                //     contentType: false,
                //     success: function(res) {
                //        return res;
                //     }
                // }); 
            },

            /**
             * Peticion HTTP para obtener el listado de productos disponibles que tiene el cliente para devolucion
             */
            httpGetProductsForRefund() {
                var url = `${this.urlRefundProducts}?cliente=${this.customerSelected.id}`;
                var self = this;
                self.productsAvailableForRefund = [];

                $.get(url, function(res) {
                    self.productsAvailableForRefund = res;
                })
                .fail(function() {
                    self.productsAvailableForRefund = [];

                    new Noty({
                        text: "No se ha podido obtener los productos disponibles para devolución.",
                        type: 'error'
                    }).show();
                });
            },
            

            /**
             * Retorna label de producto para ser impreso en el select vue
             * El select vue pasa como parametro el producto completo como objeto
             */
            getProductsToBuyOptionLabel(option) {
                var label = '';

                if (option.is_regular) {
                    label += `${option.name} (Cod: ${option.code})`;
                } else {
                    label += `${option.name} (${ option.size ? option.size.name + '-' : '' } ${ option.color ? option.color.name + '-' : '' } Cod: ${option.real_code})`;
                }

                return label;
            },

            /**
             * Setea el datapicker usado para pautar la proxima visita
             */
            setDatePicker() {
                this.renderedDatepicker = true;

                var inputs = $('.datepicker-form');

                inputs.each((index, element) => {
                    var value = element.value;

                    if (value) {
                        var dateParts = value.split("-");
                        var date = dateParts[2] + "/" +  dateParts[1] + "/" + dateParts[0];
                    } else {
                        var date = new Date(value);
                    }

                    $(element).datepicker({
                        format: "dd-mm-yyyy",
                        todayBtn: "linked",
                        language: "es",
                        autoclose: true,
                        todayHighlight: true,
                        showOnFocus: true,
                    }).datepicker("setDate", date)
                    .end().on('keypress paste', function (e) {
                        // e.preventDefault();
                        // return false;
                    });
                });
            },
            resetDevolution(){
                this.productsSelectedForRefund = [];
                this.productForRefundSelected = [];
            }
        },
        watch: {
            /**
             * Evento de cambio de cliente seleccionado
             * Llama a metodo HTTP para obtener productos disponibles que tiene el cliente para devolucion
             */
            customerSelected: function(value) {
                if (this.customerSelected && this.customerSelected.dni !== null) {
                    this.getCustomerDataAjax(this.customerSelected.id);
                    this.httpGetProductsForRefund();
                }
            }
        }
    }
</script>

<style lang="scss">
    .select2-container .select2-selection--single {
        height: 37px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 37px;
    }
    .input-group .v-select {
        width: calc(100% - 38px);
    }

    table.table-refund {
        border: 1px solid #ccc;
        border-collapse: collapse;
        margin: 0;
        padding: 0;
        width: 100%;
        table-layout: fixed;

        caption {
            font-size: 1.5em;
            margin: .5em 0 .75em;
        }

        tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: .35em;
        }

        th,
        td {
            padding: .625em;
            text-align: center;
        }

        th {
            font-size: .5em;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        td {
            font-size: 0.7rem;
        }
    }

    @media screen and (max-width: 600px) {
        table.table-refund {
            border: 0;

            caption {
                font-size: 1.3em;
            }
            
            thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }
            
            tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }
            
            td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }
            
            td::before {
                /*
                * aria-label has no advantage, it won't be read inside a table
                content: attr(aria-label);
                */
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
            
            td:last-child {
                border-bottom: 0;
            }
        }
    }
    @media screen and (max-width: 600px) {
        table.table-refund td {
            min-height: 34px;
        }
    }
</style>