$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var map = L.map('map', {
        scrollWheelZoom: false
    }).setView([ulong , ulat], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        // attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([ulong , ulat]).addTo(map)
        .bindPopup(uloc)
        .openPopup();

    var table5 = $('#tblsumm').DataTable({
        // "dom": 'rtip',
        "dom": 'Bfrtip',
        // "dom": 'lrt',
        "bFilter": false,
        "bInfo": false,
        "pageLength" : 10,
        "ordering": false,
        "info":     false,
        "buttons":[
            {
                text: 'copy',
                    extend: 'copyHtml5',
                    title: 'CV SUMMARY PER BARANGAY',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    className: 'btn btn-success waves-effect waves-light'
            },
            {
                text: 'pdf',
                    extend: 'pdfHtml5',
                    title: 'CV SUMMARY PER BARANGAY',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    customize: function (doc) {
                        doc.styles.tableHeader.alignment = 'center';
                        doc.defaultStyle.alignment = 'left';
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    },
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    className: 'btn btn-success waves-effect waves-light',
            },
            {
                text: 'print',
                    extend: 'print',
                    title: '',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#hlmun').val();
                        return '<h1 style="text-align:center;">CV SUMMARY PER BARANGAY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                    }
            }
        ]
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

});
