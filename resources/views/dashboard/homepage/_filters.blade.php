{{-- Filters --}}
<div class="row justify-content-end">
    {{-- quick filters --}}
    <div class="col-xl-3 col-md-6 mb-2">
        <label for="quick-filters"><small>Filtros rápidos</small></label>
        <div id="quick-filters" class="block full round border-filter">
            <div class="row p-2">
                <div class="col-4">
                    <button class="text-black btn-void btn-filter-time item-selected" data-time="month"  >Mes</button>
                </div>
                <div class="col-4">
                    <button class="text-black btn-void btn-filter-time" data-time="six_months"  >6 Mes</button>
                </div>
                <div class="col-4">
                    <button class="text-black btn-void btn-filter-time" data-time="twelve_months" >12 Mes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- days filters --}}
    <div class="col-xl-3 col-md-6 mb-2">
        <label for="date-filters"><small>Filtros por fecha</small></label>
        <div id="date-filters" class="block full round border-filter">
            <div class="row p-2">
                <div class="col-8 d-flex">
                    <input type="text" placeholder="Inicio"  id="date_initial" name="date_initial" class="form-control border-none datepicker h-20px" 
                    data-date-format="dd/mm/yyyy" value='{{old("date_initial")}}' onkeydown="return false"><b>&nbsp;&nbsp;&nbsp;</b><input type="text" placeholder="Fin" 
                    id="date_final" name="date_final" class="form-control border-none datepicker h-20px" data-date-format="dd/mm/yyyy" value='{{old("date_final")}}'
                     onkeydown="return false">
                    </div>
                    <div class="col-4">
                        <button class="text-black btn-void btn-filter-time h-20px" data-time="on_date" >En Días</button>
                    </div>
            </div>
        </div>
    </div>

     {{-- Months filters --}}
     <div class="col-xl-3 col-md-6 mb-2">
        <label for="month-filters"><small>Filtros por mes</small></label>
        <div id="month-filters" class="block full round border-filter">
            <div class="row p-2">
                <div class="col-8 d-flex ">
                    <input type="text" placeholder="Inicio"  id="month_initial" name="month_initial" class="form-control border-none datepicker h-20px" 
                    data-date-format="dd/mm/yyyy" value='{{old("month_initial")}}' onkeydown="return false">&nbsp;&nbsp;&nbsp;<input type="text" placeholder="Fin" 
                    id="month_final" name="month_final" class="form-control border-none datepicker h-20px" data-date-format="dd/mm/yyyy" value='{{old("month_final")}}'
                     onkeydown="return false">
                </div>
                <div class="col-xs-4">
                    <button class="text-black btn-void btn-filter-time h-20px" data-time="on_months" >En Meses</button>
                </div>
            </div>
        </div>
    </div>

</div>


