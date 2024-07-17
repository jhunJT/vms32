@extends('layouts.auth')
    @section('content')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                         <div class="col-md-3">
                                            <div class="mb-1 ">
                                               <div class="input-group gap-2">
                                                   <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterDist" id="filterDist">
                                                        <option selected disabled></option>
                                                        <option value="District I">District I</option>
                                                        <option value="District II">District II</option>
                                                   </select>
                                               </div>
                                           </div>
                                       </div>
                                        {{-- <div class="col-md-3">
                                             <div class="mb-1 ">
                                                <div class="input-group gap-2">
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterMuncit" id="filterMuncit"></select>
                                                </div>
                                            </div>
                                        </div> --}}
                                       <div class="col-md-3">
                                            <div class="mb-1 ">
                                                <div class="input-group gap-2">
                                                    <select class="form-control " style="width: 100%;"  tabindex="-1" aria-hidden="true" name="filterPos" id="filterPos">
                                                        {{-- <option selected >Select Position</option> --}}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        <div class="mb-1 ">
                                           <div class="input-group gap-2">
                                               <input type="file" class="form-control" name="uploadData" id="uploadData" placeholder="Upload Data" disabled>
                                           </div>
                                       </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h2>National Election 2022 Result</h2>
                                            {{-- <div class="input-group gap-2">
                                                <select class="form-control " style="width: 20%;"  tabindex="-1" aria-hidden="true" name="filterMuncit" id="filterMuncit"></select>
                                                <select class="form-control " style="width: 20%;"  tabindex="-1" aria-hidden="true" name="filterBrgy" id="filterPosition"></select>
                                                <button class="btn btn-primary" id="btnChart">Submit</button>
                                            </div> --}}
                                            <div id="testchart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
    <script src="{{ asset('assets/auth/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/pages/apexcharts.init.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); //initalize csrf

        $('#filterBrgy').on('change', function(){
            $('#filterPos, #filterCan').val('').trigger('change');
        });

        $('#filterPos').on('change', function(){
            $('#filterCan').val('').trigger('change');
        });

        $('#filterDist').select2({
            placeholder: "Select District",
            minimumResultsForSearch: -1,
        });

        $('#filterDist').on('change', function(){
            var district = $('#filterDist').val();
            $('#filterMuncit').val('').trigger('change');
            $('#filterPos').val('').trigger('change');
            // fetchDataAndUpdateChartDist();
            $.ajax({
                url: "{{ route('nle2022.data') }}",
                type: "post",
                data: {district:district},
                success: function (res) {
                    // console.log(res);

                    var getmun = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getmun[i] = res.districtData[i].Municipality;
                    }
                    getmun = Object.values(getmun);
                    // console.log(getmun)

                    var getrv = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getrv[i] = res.districtData[i].RV;
                    }
                    getrv = Object.values(getrv);
                    // console.log(getrv)

                    var getcv = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getcv[i] = res.districtData[i].CV;
                    }
                    getcv = Object.values(getcv);
                    // console.log(getcv)

                    // console.log(Object.values(getbrgy));
                    // alert(uniqueValuescv);

                    options = {
                        chart: {
                        height: 400,
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
                    colors: ["#fcb92c", "#1cbb8c", "#0f9cf3", "#f46a6a"],
                    xaxis: {
                        categories: getmun
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
                            // position: 'bottom'
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

        // $('#filterBrgy').select2({
        //     placeholder: "Select Barangay",
        //     allowClear: true,
        //     maximumSelectionLength: 1,
        //     ajax:{
        //         url:"{{ route('nle2022.fetchbrgyss') }}",
        //         type:"POST",
        //         dataType:"json",
        //         delay:250,
        //         quietMillis: 100,
        //         data: function(params){
        //             muncitss = $('#filterMuncit').val();
        //             return{
        //                 search: params.term,
        //                 muncitss: muncitss
        //             };
        //         },
        //         processResults: function(data){
        //             return{
        //                 results: $.map(data.items, function(obj,i) {
        //                     return {
        //                     id:obj, text:i
        //                     };
        //                 })
        //             }
        //         }
        //     }
        // });

        $('#filterPos').select2({
            placeholder: "Select Position",
            allowClear: true,
            minimumResultsForSearch: -1,
            ajax:{
                url:"{{ route('nle2022.fetchpositionss') }}",
                type:"POST",
                dataType:"json",
                delay:250,
                quietMillis: 100,
                data: function(params){
                    // muncitss = $('#filterMuncit').val();
                    brgyss = $('#filterBrgy').val();
                    return{
                        search: params.term,
                        // muncitss:muncitss,
                        // brgyss:brgyss
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
                }
            }
        });

        // $('#filterCan').select2({
        //     placeholder: "Select Candidate",
        //     allowClear: true,
        //     minimumResultsForSearch: -1,
        //     ajax:{
        //         url:"{{ route('nle2022.fetchcandidate') }}",
        //         type:"POST",
        //         dataType:"json",
        //         delay:250,
        //         quietMillis: 100,
        //         data: function(params){
        //             poss = $('#filterPos').val();
        //             return{
        //                 search: params.term,
        //                 poss:poss
        //             };
        //         },
        //         processResults: function(data){
        //             return{
        //                 results: $.map(data.items, function(obj,i) {
        //                     return {
        //                     id:obj, text:i
        //                     };
        //                 })
        //             }
        //         }
        //     }
        // });

         $('#filterMuncit').select2({
            placeholder: "Select Municipality",
            allowClear: true,
            ajax:{
                url:"{{ route('nle2022.fetchmuncitss') }}",
                type:"POST",
                dataType:"json",
                delay:2500,
                quietMillis: 100,
                data: function(params){
                    var district = $('#filterDist').val();
                    // console.log(district);
                    return{
                        search: params.term,
                        district: district
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

        $('#filterMuncit').on('change', function(){
            distpost();
        });

        $('#filterMuncit').on('select2:unselect', function(e){
            $('#filterPosition').val(null).trigger('change');
            fetchDataAndUpdateChartDist();
            // var chart = new ApexCharts(document.querySelector("#testchart"), options);
            // chart.destroy();

        });

        $('#filterPos').on('change',function(){
            distpost();
            var sposition = $('#filterPos').val();
            var cmuncit = $('#filterMuncit').val();


        });

        $('#filterPos').on('select2:select',function(e){
            var district = $('#filterDist').val();
            if(district === null){
                $('#filterPos').val('').trigger('change');
                Swal.fire({
                    title: "Message",
                    text: "Please Select District/Municipality",
                    icon: "warning"
                });
            }else{
                distpost();
            }
        });

        function distpost(){
            var district = $('#filterDist').val();
            var sposition = $('#filterPos').val();
            var muncit = $('#filterMuncit').val();
            // console.log(dist, post, muncit);

            if(district !== '' && sposition!== ''){
                // console.log(district, sposition);
                $.ajax({
                    url: "{{ route('nle2022.data') }}",
                    type: "post",
                    data: {sposition:sposition,muncit:muncit, district:district},
                    success: function (res) {
                        // console.log(res);
                        var getrv = {};
                        for(var i = 0; i <res.districtData.length; i++){
                            getrv[i] = res.districtData[i].RV;
                        }
                        getrv = Object.values(getrv);
                        // console.log(getrv)

                        var getcv = {};
                        for(var i = 0; i <res.districtData.length; i++){
                            getcv[i] = res.districtData[i].CV;
                        }
                        getcv = Object.values(getcv);

                        var getcandidates = {};
                        for(var i = 0; i <res.districtData2.length; i++){
                            getcandidates[i] = res.districtData2[i].candidate;
                        }
                        var getcandidates = Object.values(getcandidates);
                        var uniqueValuescand = getcandidates.filter((value, index, self) => self.indexOf(value) === index);
                        cand1 = uniqueValuescand[0];
                        cand2 = uniqueValuescand[1];
                        // console.log(cand1, cand2);

                        var items = res.districtData2;
                        var votesByMunicipalityC1 = {};
                        var votesByMunicipalityC2 = {};


                        var filteredDatac1 = items.filter(function(item){
                            return item.candidate === cand1;
                        });

                        for(var i = 0; i <filteredDatac1.length; i++){
                            var municipality = filteredDatac1[i].municipality;
                            var votes = filteredDatac1[i].votes;
                            var votsumm = parseInt(votes,10);

                            if (!votesByMunicipalityC1[municipality]) {
                                votesByMunicipalityC1[municipality] = 0;
                            }

                            votesByMunicipalityC1[municipality] += votsumm;
                            var votes1 = Object.values(votesByMunicipalityC1);
                        }

                        // console.log(votes1);

                        var filteredDatac2 = items.filter(function(item){
                            return item.candidate === cand2;
                        });

                        for(var i = 0; i <filteredDatac2.length; i++){
                            var municipality = filteredDatac2[i].municipality;
                            var votes = filteredDatac2[i].votes;
                            var votsumm1 = parseInt(votes,10);

                            if (!votesByMunicipalityC2[municipality]) {
                                votesByMunicipalityC2[municipality] = 0;
                            }

                            votesByMunicipalityC2[municipality] += votsumm1;
                            var votes2 = Object.values(votesByMunicipalityC2);
                        }

                        console.log(votes1, votes2,cand1, cand2 );


                        chart.updateSeries([{
                            name: cand1,
                            data: votes1
                        },{
                            name: 'RV',
                            data: getrv
                        },{
                            name: 'CV',
                            data: getcv
                        },{
                            name: cand2,
                            data: votes2
                        }])

                    },error: function(xhr, status, error) {
                        // Handle errors
                    }
                });

            }else if(dist !== '' && muncit !=='' && district !=='' ){
                alert('dd');
                var fdistrict = $('#filterDist').val();
                var cmuncit = $('#filterMuncit').val();
                $('#filterPos').val(null).trigger('change');
                $.ajax({
                    url: "{{ route('archives.getposition') }}",
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
                        // console.log(getbrgy);
                        // console.log(Object.values(getbrgy));
                        // alert(uniqueValuescv);

                        options = {
                            chart: {
                            height: 400,
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
                        colors: ["#fcb92c", "#1cbb8c", "#0f9cf3", "#f46a6a"],
                        xaxis: {
                            categories: getbrgy,
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
                                // position: 'bottom'
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
            }
        }

        function loadMun(){
            // var cmuncit = $('#filterMuncit').val();
            $.ajax({
                url: "{{ route('nle2022.data') }}",
                type: "post",
                // data: {cmuncit:cmuncit},
                success: function (res) {
                    // console.log(res);

                    var getmun = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getmun[i] = res.districtData[i].Municipality;
                    }
                    getmun = Object.values(getmun);
                    // console.log(getmun)

                    var getrv = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getrv[i] = res.districtData[i].RV;
                    }
                    getrv = Object.values(getrv);
                    console.log(getrv)

                    var getcv = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getcv[i] = res.districtData[i].CV;
                    }
                    getcv = Object.values(getcv);
                    console.log(getcv)

                    // console.log(Object.values(getbrgy));
                    // alert(uniqueValuescv);

                    options = {
                        chart: {
                        height: 400,
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
                    colors: ["#fcb92c", "#1cbb8c", "#0f9cf3", "#f46a6a"],
                    xaxis: {
                        categories: getmun
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
                            // position: 'bottom'
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
        }

        function fetchDataAndUpdateChartDist(){
            var district = $('#filterDist').val();
             $.ajax({
                url: "{{ route('nle2022.data') }}",
                type: "post",
                data: {district:district},
                success: function (res) {
                    // console.log(res);

                    var getmun = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getmun[i] = res.districtData[i].Municipality;
                    }
                    getmun = Object.values(getmun);
                    // console.log(getmun)

                    var getrv = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getrv[i] = res.districtData[i].RV;
                    }
                    getrv = Object.values(getrv);
                    // console.log(getrv)

                    var getcv = {};
                    for(var i = 0; i <res.districtData.length; i++){
                        getcv[i] = res.districtData[i].CV;
                    }
                    getcv = Object.values(getcv);
                    // console.log(getcv)

                    // console.log(Object.values(getbrgy));
                    // alert(uniqueValuescv);

                    options = {
                        chart: {
                        height: 400,
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
                    colors: ["#fcb92c", "#1cbb8c", "#0f9cf3", "#f46a6a"],
                    xaxis: {
                        categories: getmun
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
                            // position: 'bottom'
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
                    console.error('Error fetching data:', error);
                }
            });
        }

    });
    </script>

    @include('layouts.script')
    @include('layouts.footer')

@endsection
