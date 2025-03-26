<script>
    $(document).ready(function(){
        const url = "{{ route('admin.home') }}";
        
        setInit();

        // Methods
        function setInit(){
            setDatepickers();
            getData();
        }

        function getData( filter = '', start = '', end = '' ){
            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                data: {
                    filter,
                    start,
                    end
                },
                success: function (response) {
                    setDataTotalCards(response);
                }
            });

        }

        function setDataTotalCards(data){
            $("#open-closed-boxes").text(data.open_closed_boxes);
            $("#total-sales").text(data.total_sales);
            $("#total-paid").text(data.total_paid);
            $("#total-collected").text(data.total_collected);
            $("#total-returns").text(data.total_returns);
            $("#total-credit").text(data.total_credit);
            $("#total-cash").text(data.total_cash);
            $("#total-card").text(data.total_card);
            $("#total-transfer").text(data.total_transfer);
        }

        function setDatepickers(){
                var campos_fecha = $(".datepicker");
                var date_initial = $('#date_initial');
                var date_final = $('#date_final');

                var month_initial = $("#month_initial");
                var month_final   = $("#month_final");

                var dateToday = new Date();
                var fromDate = "01/" +  (dateToday.getMonth() + 1) + "/" + dateToday.getFullYear();
                var toDate = "01/" + (dateToday.getMonth() + 2) + "/" + dateToday.getFullYear();

                var fromMonth = (dateToday.getMonth() + 1) + "/" + dateToday.getFullYear();
                var toMonth = (dateToday.getMonth() + 2) + "/" + dateToday.getFullYear();



                date_initial.datepicker({
                    format: "dd/mm/yyyy",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    showOnFocus: true,
                }).datepicker("setDate",fromDate)
                .end().on('keypress paste', function (e) {
                    e.preventDefault();
                    return false;
                });

                date_final.datepicker({
                    format: "dd/mm/yyyy",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    showOnFocus: true,
                }).datepicker("setDate",toDate)
                .end().on('keypress paste', function (e) {
                    e.preventDefault();
                    return false;
                });

                month_initial.datepicker({
                    format: "mm/yyyy",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    showOnFocus: true,
                    minViewMode: "months"
                }).datepicker("setDate",fromMonth)
                .end().on('keypress paste', function (e) {
                    e.preventDefault();
                    return false;
                });

                month_final.datepicker({
                    format: "mm/yyyy",
                    todayBtn: "linked",
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    showOnFocus: true,
                    minViewMode: "months"
                }).datepicker("setDate",toMonth)
                .end().on('keypress paste', function (e) {
                    e.preventDefault();
                    return false;
                });
            }

            function ultimo_dia_del_mes(y,m){
                return new Date(y, m + 1, 0).getDate();
            }

            function changeFilterSelected(el){
                $(`.btn-filter-time`).removeClass('item-selected')
                el.addClass('item-selected');
            }

            // Eventos
            $('body').on("click", ".btn-filter-time" , function(e){
                e.preventDefault();
                let time = $(this).data('time');
                if (time == 'on_date') {
                    let date_inicial = $("#date_initial").val();
                    let date_final = $("#date_final").val();
                    getData(time, date_inicial, date_final);  
                }else if (time == 'on_months') {
                    let month_initial = `01/${$("#month_initial").val()}`;
                    let month_final = `01/${ $("#month_final").val()}`;
                    console.log(month_initial, month_final);
                    getData(time,  month_initial, month_final);  
                }else{
                    getData(time);  
                }
                changeFilterSelected($(this));
            });
    });
</script>