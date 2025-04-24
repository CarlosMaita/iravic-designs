<script>
    $(document).ready(function(){
        const url = "{{ route('admin.home') }}";

            // orders Charts
            const ordersChart = createChart(document.getElementById('orders-graph') , 'Ventas'),
              ordersChartExpanded = createChart(document.getElementById('orders-graph-expanded') , 'Ventas');
        // refunds Charts
        const refundsChart = createChart( document.getElementById('refunds-graph') , 'Devoluciones'),
              refundsChartExpanded = createChart( document.getElementById('refunds-graph-expanded') , 'Devoluciones');
        // collections Charts
        const collectionsChart = createChart(document.getElementById('collections-graph'), 'Cobros'),
              collectionsChartExpanded = createChart(document.getElementById('collections-graph-expanded'), 'Cobros');
        // payments Charts
        const paymentsChart = createChart(document.getElementById('payments-graph'), 'Pagos'),
              paymentsChartExpanded = createChart(document.getElementById('payments-graph-expanded'), 'Pagos');
        // Payments Multiple Charts 
        const paymentsMultipleChart = createChartMultiple(document.getElementById('payments-multiples-graph'), 'Ventas en Efectivo, Tarjeta y Credito', ['Efectivo y Transferencia', 'Tarjeta', 'Crédito']),
              paymentsMultipleChartExpanded = createChartMultiple(document.getElementById('payments-multiples-graph-expanded'), 'Ventas en Efectivo, Tarjeta y Credito', ['Efectivo y Transferencia', 'Tarjeta', 'Crédito']);
        
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

            //Actualizar la data de Charts
            updateData( ordersChart, data.total_sales_graph)
            updateData( ordersChartExpanded, data.total_sales_graph)
            updateData( refundsChart,  data.total_returns_graph)
            updateData( refundsChartExpanded,  data.total_returns_graph)
            updateData( collectionsChart, data.total_collected_graph)
            updateData( collectionsChartExpanded, data.total_collected_graph)
            updateData( paymentsChart, data.total_paid_graph)
            updateData( paymentsChartExpanded, data.total_paid_graph)
            
            const dataMultiple = [data.total_cash_and_transfer_graph,data.total_card_graph , data.total_credit_graph ];
            updateData( paymentsMultipleChart, dataMultiple , true);
            updateData( paymentsMultipleChartExpanded, dataMultiple, true);
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

            function renderCanvas(ctx, ctxExpanded, title, data) {
                let labels = Object.keys(data),
                    values = Object.values(data);

                const config = {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: title,
                            data: values,
                            backgroundColor: 'rgba(60, 75, 100, 0.8)',
                            borderColor: 'rgba(60, 75, 100, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: "#000"
                                },
                                gridLines: {
                                    color: "rgba(60, 75, 100, 0.2)"
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontColor: "#000"
                                },
                                gridLines: {
                                    color: "rgba(60, 75, 100, 0.2)"
                                }
                            }]
                        },
                        legend: {
                            labels: {
                                fontColor: "#000"
                            }
                        }
                    }
                };

                new Chart(ctx, config);
                new Chart(ctxExpanded, config);
            }

            function createChart(ctx, title) 
            {
                let labels = [],
                    values = [];

                const config = {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: title,
                            data: values,
                            backgroundColor: 'rgba(60, 75, 100, 0.8)',
                            borderColor: 'rgba(60, 75, 100, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: "#000",
                                    callback: function(value, index, ticks) {
                                        return '$' + value;
                                    }
                                },
                                gridLines: {
                                    color: "rgba(60, 75, 100, 0.2)"
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontColor: "#000"
                                },
                                gridLines: {
                                    color: "rgba(60, 75, 100, 0.2)"
                                }
                            }]
                        },
                        legend: {
                            labels: {
                                fontColor: "#000"
                            }
                        }
                    }
                };

               return new Chart(ctx, config);
            }

            function createChartMultiple(ctx, title, labels){
                const config = {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: labels[0],
                            data: [],
                            backgroundColor: 'rgba(76, 175, 80, 0.8)',
                            borderColor: 'rgba(76, 175, 80, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: labels[1],
                            data: [],
                            backgroundColor: 'rgba(25, 150, 243, 0.8)',
                            borderColor: 'rgba(25, 150, 243, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: labels[2],
                            data: [],
                            backgroundColor: 'rgba(255, 152, 0, 0.8)',
                            borderColor: 'rgba(255, 152, 0, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: "#000",
                                    callback: function(value, index, ticks) {
                                        return '$' + value;
                                    }
                                },
                                gridLines: {
                                    color: "rgba(60, 75, 100, 0.2)"
                                },
                               
                            }],
                            xAxes: [{
                                ticks: {
                                    fontColor: "#000"
                                },
                                gridLines: {
                                    color: "rgba(60, 75, 100, 0.2)"
                                }
                            }]
                        },
                        legend: {
                            labels: {
                                fontColor: "#000"
                            }
                        }
                    }
                }

                return new Chart(ctx, config);
            }


            function updateData(chart, data, multiple) {
                if (multiple) {
                    const arrayData = data;
                    const labels = Object.keys(arrayData[0])
                    chart.data.labels = labels;
                    arrayData.forEach((data, index, array) => {
                        const values = Object.values(data);
                        chart.data.datasets[index].data = values
                    })
                } else {
                    const labels = Object.keys(data);
                    const values = Object.values(data);
                    
                    chart.data.labels = labels;
                    chart.data.datasets[0].data = values;
                }
                chart.update();
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