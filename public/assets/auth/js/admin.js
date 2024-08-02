$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table5 = $('#tblsumm').DataTable({
        "dom": 'rtip',
        "pageLength" : 11,
        "ordering": false,
        "info":     false

    });

    $('#filterMuncit').select2({
        placeholder: "Select Municipality",
        allowClear: true,
        ajax:{
            url:"",
            type:"POST",
            dataType:"json",
            delay:2500,
            quietMillis: 100,
            data: function(params){
                return{
                    search: params.term
                };
            },
            processResults: function(data){
                return{
                    results: $.map(data.items, function(obj,i) {
                        return {
                        id:obj, text:obj
                        };
                    })
                }
            },
            initSelection : function (element, callback) {
                var data = [];
                $(element.val()).each(function () {
                    data.push({id: this, text: this});
                });
                callback(data);
            }
        }

    });

    $('#filterPosition').select2({
        placeholder: "Select Position",
        allowClear: true,
        ajax:{
            url:"",
            type:"POST",
            dataType:"json",
            delay:2500,
            quietMillis: 100,
            data: function(params){
                var smuncit = $('#filterMuncit').val();
                return{
                    search: params.term,
                    smuncit: smuncit
                };
            },
            processResults: function(data){
                return{
                    results: $.map(data.items, function(obj,i) {
                        return {
                        id:obj, text:i
                        };
                    })
                }
            },
            initSelection : function (element, callback) {
                var data = [];
                $(element.val()).each(function () {
                    data.push({id: this, text: this});
                });
                callback(data);
            }
        }

    });

    $('#filterMuncit').on('change', function(){
        // var sposition = $('#filterPosition').val();
        var cmuncit = $('#filterMuncit').val();
        $('#filterPosition').val(null).trigger('change');

        $.ajax({
            url: "",
            type: "post",
            data: {cmuncit:cmuncit},
            success: function (res) {

                var getrv = {};
                for(var i = 0; i <res.position.length; i++){
                    getrv[i] = res.position[i].rv;
                }
                getrv = Object.values(getrv);
                // console.log(getrv)

                var getcv = {};
                for(var i = 0; i <res.position.length; i++){
                    getcv[i] = res.position[i].cv;
                }
                getcv = Object.values(getcv);

                var getbrgy = {};
                for(var i = 0; i <res.position.length; i++){
                    getbrgy[i] = res.position[i].barangay;
                }

                getbrgy = Object.values(getbrgy);

                // console.log(Object.values(getbrgy));
                // alert(uniqueValuescv);

                options = {
                    chart: {
                    height: 350,
                    type: "bar",
                    toolbar: {
                        show: !1
                    }
                },
                    plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "50%",
                        endingShape: "rounded"
                    }
                },
                    dataLabels: {
                    enabled: !1
                },
                    stroke: {
                    show: !0,
                    width: 2,
                    colors: ["transparent"]
                },
                    series: [
                {
                    name: '',
                    data: []
                },{
                    name: "RV",
                    data: [],
                },
                {
                    name: "CV",
                    data: [],
                }, {
                    name: '',
                    data: []
                }],
                colors: ["#f46a6a", "#1cbb8c", "#0f9cf3", "#fcb92c"],
                xaxis: {
                    categories: getbrgy
                },
                yaxis: {
                    title: {
                        text: "Registered Voters"
                    }
                },
                grid: {
                    borderColor: "#f1f1f1",
                    padding: {
                        bottom: 10
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(e) {
                            return  e + " votes"
                        }
                    }
                },
                    legend: {
                        offsetY: 10
                    }
                };

                (chart = new ApexCharts(document.querySelector("#testchart"), options)).render();

                chart.updateSeries([{
                    name: "",
                    data: []
                },{
                    name: 'RV',
                    data:getrv
                },{
                    name: 'CV',
                    data: getcv
                },{
                    name: "",
                    data: []
                }]);

            },
            error: function(xhr, status, error) {
                // Handle errors
            }
        });

    });

    $('#filterPosition').on('change',function(){

        var sposition = $('#filterPosition').val();
        var cmuncit = $('#filterMuncit').val();

        $.ajax({
            url: "",
            type: "post",
            data: {sposition:sposition,cmuncit:cmuncit},
            success: function (res) {

                var getrv = {};
                for(var i = 0; i <res.rvcv.length; i++){
                    getrv[i] = res.rvcv[i].rv;
                }
                getrv = Object.values(getrv);
                // console.log(getrv)

                var getcv = {};
                for(var i = 0; i <res.rvcv.length; i++){
                    getcv[i] = res.rvcv[i].cv;
                }
                getcv = Object.values(getcv);

                // get candidates
                var getcandidates = {};
                for(var i = 0; i <res.position.length; i++){
                    getcandidates[i] = res.position[i].candidate;
                }
                var getcandidates = Object.values(getcandidates);
                var uniqueValuescand = getcandidates.filter((value, index, self) => self.indexOf(value) === index);

                cand1 = uniqueValuescand[0];
                cand2 = uniqueValuescand[1];
                console.log(cand1);

                var items = res.position;
                var getvotesc1 = [];
                var getvotesc2 = [];

                var filteredDatac1 = items.filter(function(item){
                    return item.candidate === cand1;
                });

                for(var i = 0; i <filteredDatac1.length; i++){
                    getvotesc1[i] = filteredDatac1[i].votes;
                }

                var filteredDatac2 = items.filter(function(item){
                    return item.candidate === cand2;
                });

                for(var i = 0; i <filteredDatac2.length; i++){
                    getvotesc2[i] = filteredDatac2[i].votes;
                }

                // console.log(res);
                chart.updateSeries([{
                    name: cand1,
                    data: getvotesc1
                },{
                    name: 'RV',
                    data: getrv
                },{
                    name: 'CV',
                    data: getcv
                },{
                    name: cand2,
                    data: getvotesc2
                }])

            },error: function(xhr, status, error) {
                // Handle errors
            }
        });
    });

    $('#dist1').DataTable({
        "dom": 'Bfrtip',
        "autoWidth" : true,
        "buttons": [
            {
                text: 'excel',
                extend: 'excelHtml5',
                title: 'DISTRICT I SUMMARY',
                className: 'btn btn-success waves-effect waves-light',
                exportOptions: {
                    columns: ':visible:not(.not-export-col)'
                },
                messageTop:
                    'The information in this table is confidential.'
            },
            {
                text: 'print',
                extend: 'print',
                title: 'DISTRICT I SUMMARY',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                    // ':visible:not(.not-export-col)'
                    // columns: ":not(.not-export-column)"
                },
                className: 'btn btn-success waves-effect waves-light',
                    messageTop: function () {
                    return '<h4 style="text-align:center;">DISTRICT I SUMMARY</h4>';
                }
            },
        ]
    });

    $('#dist2').DataTable({

        "dom": 'frtip',
        "pageLength": 10,
        "order": [[0, 'asc']],
        "columnDefs": [
            { "targets": [0], "type": "natural" }, // Use natural sorting for the first column
            {
                "targets": [2,3,4,5], // Target the second column (index 1, which is Population)
                "render": $.fn.dataTable.render.number(',', '.', 0) // Format numbers with comma separators
            }
        ]
    });

    var modalTable = $('#tblsumm').DataTable({
        destroy: true, // Destroy existing DataTable (if any)
        columns: [
            { data: 'Barangay', name: 'Barangay' },
            { data: 'RV', name: 'RV' },
            { data: 'HL', name: 'HL' },
            { data: 'Members', name: 'Members' },
            { data: 'MA', name: 'MA' },
            { data: 'CV', name: 'CV' }
        ]
    });

    $('.viewSummary').on('click', function(){
        var sendmuncit = $(this).data('muncit'); // Get value of data-muncit attribute

        $.ajax({
            url: dashboardGetMuncit, // Replace with your controller URL
            method: 'POST',
            data: {
                sendmuncit: sendmuncit
             },
            success: function(response) {
                // console.log('Response from server:', response);
                if (response && response.data && response.data.length > 0) {
                    // Clear existing rows and add new data to DataTable
                    modalTable.clear().rows.add(response.data).draw();

                    // Show the modal
                    $('#cvsumm-modal-lg').modal('show');
                } else {
                    console.error('Empty or invalid data received.');
                    // Handle empty or invalid data scenario
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    $('.selectedMuncit').click(function(e){
        e.preventDefault(); // Prevent the default action (following the link)
        var selMun = $(this).data('mun');

        $.ajax({
            url: dashboardGetSelectedMuncit +'/'+selMun,
            type: 'GET',
            data: {selMun:selMun},
            success: function(response) {
                alert(response.message); // Example: Show response message
                // Handle further actions based on response if needed
            },
            error: function(xhr) {
                // Handle error
                alert('Error: ' + xhr.responseText);
            }
        });
    });

    // $('#btnChart').on('click',function(){
    //     var sposition = $('#filterPosition').val();
    //     var cmuncit = $('#filterMuncit').val();

    //     $.ajax({
    //         url: "",
    //         type: "post",
    //         data: {sposition:sposition,cmuncit:cmuncit},
    //         dataType:'json',
    //         async: true,
    //         cache: false,
    //         success: function (res) {

    //             var cand1 = $('#getpos1').val();
    //             var cand2 = $('#getpos2').val();

    //             var getrv = {};
    //             for(var i = 0; i <res.position.length; i++){
    //                 getrv[i] = res.position[i].rv;
    //             }

    //             // console.log(getrv);
    //             // var values = Object.values(getrv);
    //             // var uniqueValuesrv = values.filter((value, index, self) => self.indexOf(value) === index);

    //             var getcv = {};
    //             for(var i = 0; i <res.position.length; i++){
    //                 getcv[i] = res.position[i].cv;
    //             }
    //             // var values = Object.values(getcv);
    //             // var uniqueValuescv = values.filter((value, index, self) => self.indexOf(value) === index);


    //             // console.log(res.position);
    //             var items = res.position;
    //             var getvotesc1 = [];
    //             var getvotesc2 = [];

    //             var filteredDatac1 = items.filter(function(item){
    //                 return item.candidate === cand1;
    //             });

    //             for(var i = 0; i <filteredDatac1.length; i++){
    //                 getvotesc1[i] = filteredDatac1[i].votes;
    //             }

    //             var filteredDatac2 = items.filter(function(item){
    //                 return item.candidate === cand2;
    //             });

    //             for(var i = 0; i <filteredDatac2.length; i++){
    //                 getvotesc2[i] = filteredDatac2[i].votes;
    //             }

    //             // console.log(getvotes);

    //             chart.updateSeries([{
    //                 name: cand1,
    //                 data: getvotesc1
    //             },{
    //                 name: 'RV',
    //                 data:getrv
    //             },{
    //                 name: 'CV',
    //                 data: getcv
    //             },{
    //                 name: cand2,
    //                 data: getvotesc2
    //             }])
    //         },

    //         error: function(xhr, status, error) {
    //             // Handle errors
    //         }
    //     });
    // });


});
