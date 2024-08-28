
$(document).ready(function(e) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // document.querySelector('.topnav').style.display = 'none';

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    var table = $('#calbcity2025').DataTable({
        "lengthMenu": [10, 15,30, 50, 100],
        "ordering": true,
        "order": [[10,'asc'],[3,'asc'],[2,'asc'],[7,'asc'],[8,'asc']],
        "autoWidth" : true,
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": encoderDataIndex,
        "columns":[
            { "data": "checkbox", 'targets': 0, 'checkboxes': {'selectRow': true} }, //0
            { "data": "id" }, //1
            { "data": "Name" , className:"tleft"}, //2
            { "data": "Barangay" }, //3
            { "data": "Precinct_no" }, //4
            { "data": "PL" }, //5
            { "data": "HL" }, //6
            { "data": "purok_rv"}, //7
            { "data": "sqn"}, //8
            { "data": "action", orderable:false, searchable: false, className:"tright"}, //9
            { "data": "survey_stat" }, //10
            { "data": "man_add"}, //11
            { "data": "Municipality" }, //12
        ],
        "columnDefs": [
            {"className": "align-middle", "targets": "_all"},
            {"className": "text-center", "targets": [7,8,9]},
            {"targets": [0], "visible": true, "bSortable": false, "width": '5%'},
            {"targets": [7,8,], "width": '5%'},
            {"targets": [1,10,11,12,5], "visible": false, "searchable": true },
        ],
        "rowCallback": function( row, data, index ) {

            if ( (data.survey_stat  &&  data.man_add === 1) ) {$(row).addClass('orange');}
                else if ( data.survey_stat == 1 ) {$(row).addClass('green');}

            if(data.man_add != 1){
                $(row).addClass('hideDel');
            }
        }
    });

    $('.cvsumm').on('click', function(){

        var cvsummary = $('#cvsumm').dataTable({
        "dom": "rtip",
        // "dom": 'Bfrtip',
        "ajax": "{{ route('calbayog2025.cvsumm') }}",
        "columns":[
                    { "data": "Barangay"}, //1
                    { "data": "RV"}, //2
                    { "data": "CV"}, //3
                    { "data": "IB"}, //4
                    { "data": "OBWC"}, //5
                    { "data": "TRANSFER"}, //5
                ],
        "buttons":[
                {
                    text: 'copy',
                        extend: 'copyHtml5',
                        title: 'CV SUMMARY',
                        orientation:'portrait',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [0,1,2,3,4,5]
                        },
                        className: 'btn btn-success waves-effect waves-light'
                },
                {
                    text: 'pdf',
                        extend: 'pdfHtml5',
                        title: 'CV SUMMARY',
                        orientation:'portrait',
                        pageSize: 'LEGAL',
                        customize: function (doc) {
                            doc.styles.tableHeader.alignment = 'center';
                            doc.defaultStyle.alignment = 'center';
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        },
                        exportOptions: {
                            columns: [0,1,2,3,4,5]
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
                            columns: [0,1,2,3,4,5]
                        },
                        className: 'btn btn-success waves-effect waves-light',
                            messageTop: function () {
                                muncit = $('#filterMuncit').val();
                            return '<h1 style="text-align:center;">CV SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                        }
                }
            ],
        "columnDefs": [
                {"className": "text-center", "targets": [1,2,3,4,5]},
                {"className": "dt-center", "targets": "_all"},
            ],
        });

        $('#cvsumm-modal-lg').modal('show');

    });

    $('#cvsumm-modal-lg').on('hidden.bs.modal', function () {
        $('#cvsumm').dataTable().fnDestroy();
    });

    $('.hlsumm').on('click',function(){

        var hlsummary = $('#hlsumm').dataTable({
        "dom": "rtip",
        // "dom": 'Bfrtip',
        "ajax": "{{ route('calbayog2025.hlsumm') }}",
        "columns":[
                    { "data": "Barangay"}, //1
                    { "data": "HL"}, //2
                    { "data": "Members"}],
        "buttons":[
            {
                text: 'copy',
                    extend: 'copyHtml5',
                    title: 'HOUSELEADER SUMMARY',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: [0,1,2]
                    },
                    className: 'btn btn-success waves-effect waves-light'
            },
            {
                text: 'pdf',
                    extend: 'pdfHtml5',
                    title: 'HOUSELEADER SUMMARY',
                    orientation:'portrait',
                    pageSize: 'LEGAL',
                    customize: function (doc) {
                        doc.styles.tableHeader.alignment = 'center';
                        doc.defaultStyle.alignment = 'center';
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    },
                    exportOptions: {
                        columns: [0,1,2]
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
                        columns: [0,1,2]
                    },
                    className: 'btn btn-success waves-effect waves-light',
                        messageTop: function () {
                            muncit = $('#filterMuncit').val();
                        return '<h1 style="text-align:center;">HOUSELEADER SUMMARY</h1><h2 style="text-align:center;">'+muncit+'</h2>';
                    }
            }
        ],
        "columnDefs": [
                {"className": "text-center", "targets": [1,2]},
                {"className": "dt-center", "targets": "_all"},
            ],
        });
        $('#hlsumm-modal-lg').modal('show');
    });

    $('#hlsumm-modal-lg').on('hidden.bs.modal', function () {
        $('#hlsumm').dataTable().fnDestroy();
    });

    $('.filter-checkbox').on('change', function(){
        var searchTerms = []
        $.each($('.filter-checkbox'), function(i,elem){
            if($(elem).prop('checked')){
            searchTerms.push($(this).val() )
            }
        })
        table.column(10).search(searchTerms, true, false, true).draw();
    });

    $('.filter-checkboxManual').on('change', function(){
        var searchTerms = []
        $.each($('.filter-checkboxManual'), function(i,elem){
            if($(elem).prop('checked')){
            searchTerms.push($(this).val() )
            }
        })
        table.column(11).search(searchTerms,true).draw();
    });

    $(document).on('change','#filterMuncit', function(){
        var selectMuncit = []
        $.each($('#filterMuncit'), function(i,elem){
            selectMuncit.push($(this).val())
        })
        table.column(12).search(selectMuncit).draw();
    });

    $('#brgySelect').on('change', function(){
        var selectBrgy = []
        $.each($('#brgySelect'), function(i,elem){
            selectBrgy.push($(this).val())
        })
        table.order([3,'asc']).column(3).search(selectBrgy).draw();
    });

    $('#filterBrgy').on('change', function(){
        $('#houseleaderSelect, #purokLeader').val('').trigger('change');
        $('#setPurok, #seqno').val('');

        var selectBrgy = []
        $.each($('#filterBrgy'), function(i,elem){
            selectBrgy.push($(this).val())
        })
        table.column(3).search(selectBrgy).draw();
    });

    var form = $('#ajaxForm')[0];
    $('#saveBtn').click(function(){

        $('.error-message').html('');
        var formData = new FormData(form);

        $.ajax({
            url: encoderDataUpdate ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,

            success: function(response) {
                table.draw();
                $('#vid').val('');
                $('#name').val('');
                $('#sip').val('');
                $('#district').val('');
                $('#contno').val('');
                $('#muncit').val('');
                $('#barangay').val('');
                $('#purok').val('');
                $('#precno').val('');
                //$('#survey_stat').val('');
                $('#hl').val('');
                $('#pl').val('');
                $('#remarks').val('');
                $('#vstat').val('');
                $('#contno').val('');
                $('#dob').val('');
                $('input:checkbox').removeAttr('checked');
                $('#grant').val('');
                $('#gdate').val('');
                $('#amount').val('');
                $('#gremarks').val('');

                // $('.ajaxForm').trigger('click');
                $('#formModal').modal('toggle');

                if(response) {
                    toastr.success(response.success);
                }
            },
            error: function(xhr, status, error){
                //console.log(error);
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });
    });

    $('#filterMuncit').select2({
        placeholder: "Filter Municipality",
        minimumResultsForSearch: -1,
    });

    $('body').on('click', '.gview', function(){
        var id = $(this).data('id');

        $.ajax({
            url: encoderDataViewGrant +'/'+ id ,
            method:'GET',
            success: function(response){
                $('#formViewModal').modal('show');
                $('.modal-title').html('Voters Record ');

                $('#vname').val(response.Name);
                $('#voccup').val(response.occupation);
                $('#vsip').val(response.SIP);
                $('#vcontno').val(response.contact_no);
                $('#vvid').val(response.id);
            },
            error: function(xhr, status, error){
                //console.log(error);
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });

        var vtrsInfo =  $('#vtrs_info').DataTable({
            "paging": true,
            "bLengthChange": false,
            "pageLength": 5,
            "searching": true,
            "ajax": {
                url: encoderDataViewGrantDetails,
                data: {
                    id : id
                }
            },
            "columns":[
                { "data": "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                } }, //1
                { "data": "grant" ,}, //2
                { "data": "date" }, //3
                { "data": "amount" }, //3
                { "data": "remarks" }, //4
            ]
        });

        $('#formViewModal').on('hidden.bs.modal', function (e){
            vtrsInfo.destroy();
        })
    });

    $('#addMan').click(function(){

        var checkBrgy = $('#filterBrgy').val();
        var getMuncit = $('#getMuncit').val();
        var getDistrict = $('#getDistrict').val();
        var brgy = $('#filterBrgy').val();

        if( checkBrgy === null){
            Swal.fire({
                title: "Message",
                text: "Please Select Barangay First!",
                icon: "warning"
            });
        }else{
            $('#formModal').modal('show');
            $('#ajaxForm')[0].reset();
            $('.modal-title').html('Manual Register Voter');
            $("[id='survey_stat']").prop({'checked':true});
            $('#name').attr('readonly', false);
            $('#barangay').attr('readonly', true);
            $('#precno').attr('readonly', false);
            $('#muncit').attr('readonly', true);
            $('#district').attr('readonly', true);

            $('#grant_check').attr('disabled', true);
            $('#grant').attr('disabled', true);
            $('#gdate').attr('readonly', true);
            $('#amount').attr('readonly', true);
            $('#gremarks').attr('readonly', true);

            $('#district').val(getDistrict);
            $('#muncit').val(getMuncit);
            $('#barangay').val(brgy).text();
            $('#saveBtn').html('Save');
            $('#plss').val(null).trigger('change');
            $('#hls').select2('destroy').val(null).trigger('change').select2({
                dropdownParent: $('#ajaxForm'),
                placeholder: "Select House Leader",
                ajax:{
                    url:encoderDataHL,
                    type:"POST",
                    dataType:"json",
                    delay:250,
                    quietMillis: 100,
                    data: function(params){
                        hlsmuncit = $('#muncit').val();
                        hlsbrgy = $('#barangay').val();
                        return{
                            search: params.term,
                            hlsmuncit:hlsmuncit,
                            hlsbrgy:hlsbrgy
                        };
                    },
                    processResults: function(data){
                        var houseleaders = data.items.map(function(item) {
                            // console.log(item);

                            return {
                                id: item.id,
                                text: item.houseleader,
                                purok: item.purok,
                                sqn: item.sqn
                            }
                        });

                        return {
                            results: houseleaders

                        };

                    },

                    cache: true
                }
            });
        }
    });

    $('body').on('click', '.vedit' ,function(){
        var id = $(this).data('id');
        $('#saveBtn').html('Update');
        $('#grant_check').prop('checked',false);
        $('#formModal').modal({backdrop: 'static', keyboard: false});

        $.ajax({
            url: encoderDataView + '/' + id,
            method:'GET',
            success: function(response){

                $('.ajaxForm').modal('show');
                $('.modal-title').html('Voter Information Update');

                var ifchecked = response.survey_stat;
                if (ifchecked == 1){
                    $("[id='survey_stat']").prop({'checked':true});
                } else if(ifchecked == 0 ){
                    $("[id='survey_stat']").prop({'checked':false});
                } else if(ifchecked == null){
                    $("[id='survey_stat']").prop({'checked':false});
                }

                $('#survey_stat').val(ifchecked);
                $('#name').val(response.Name);
                $('#occup').val(response.occupation).change();
                $('#sip').val(response.SIP).change();
                $('#district').val(response.District);
                $('#contno').val(response.contno);
                $('#muncit').val(response.Municipality);
                $('#barangay').val(response.Barangay);
                $('#precno').val(response.Precinct_no);
                $('#vstat2').val(response.vstatus).change();
                $('#remarks').val(response.remarks);
                $('#vstat').val(response.vstatus).change();
                $('#contno').val(response.contact_no);
                $('#dob').val(response.dob);
                $('#vid').val(response.id);
                $('#hlids').val(response.hlids);
                $('#plids').val(response.plids);
                $('#hlnameeditmodal').val(response.HL);

                $('#purok').val(response.purok_rv);
                $('#sqn').val(response.sqn);

                var newOptionsHL = new Option(response.HL, response.HL, true, true);
                $('#hls').append(newOptionsHL).trigger('change');

                var newOptionPL = new Option(response.PL, response.PL, true, true);
                $('#plss').append(newOptionPL).trigger('change');

                // $('#name').attr('readonly', true);
                $('#barangay').attr('readonly', true);
                // $('#precno').attr('readonly', true);
                $('#muncit').attr('readonly', true);
                $('#district').attr('readonly', true);

                if(response.man_add == 1){
                    $('#precno').attr('readonly', false);
                    $('#name').attr('readonly', false);
                }else{
                    $('#precno').attr('readonly', true);
                    $('#name').attr('readonly', true);
                }

                $('#grant_check').attr('disabled', false);
                $('#grant').attr('disabled', false);
                $('#gdate').attr('readonly', false);
                $('#amount').attr('readonly', false);
                $('#gremarks').attr('readonly', false);
            },
            error: function(xhr, status, error){
                //console.log(error);
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });

    });

    $('#formModal').on('shown.bs.modal', function(){
            $('#hls').on('change', function(){
                var selectedData = $(this).select2('data')[0];
                var id = selectedData.id;
                var text = selectedData.text;
                var sqn = selectedData.sqn;
                var purok = selectedData.purok;

                $('#hlContainer').val(id);
                $('#hlId').val(id);
                $('#hlnameeditmodal').val(text);
                $('#sqn').val(sqn);
                $('#purok').val(purok);
            });
        });

        $('#formModal').on('hidden.bs.modal', function () {
        $('#hls').off('select2:select').off('select2:unselect').off('change');
    });

    $('#survey_stat').on('change', function(event){
        const checked = $(this).is(':checked');
        if(checked == true){
            $('#survey_stat').val('1');
        }else if(checked == false){
            $('#survey_stat').val('0');
        }
    });

    $('#purokLeader').select2({
        placeholder: "Select PB/PC",
        allowClear: true,
        maximumSelectionLength: 1,
        ajax:{
            url:encoderDataPBPC,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                fltbrgy = $('#filterBrgy').val();
                muncit = $('#filterMuncit').val();
                return{
                    search: params.term,
                    fltbrgy: fltbrgy
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
            }
        }
    });

    $('#hlBrgy').select2({
        // placeholder: "Select Barangay",
        readonly: true,
        disabled: true,
        dropdownParent: $('#addHLModal'),
        // allowClear: true,
        ajax:{
            url:"{{ route('stamargarita.brgy3') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                muncitss = $('#filterMuncit').val();
                return{
                    search: params.term,
                    muncitss:muncitss
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
            }
        }
    });

    $('#plName').select2({
        placeholder: "Select PB/PC",
        dropdownParent: $('#addPLModal'),
        allowClear: true,
        ajax:{
            url:encoderDataVName,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            allowClear: true,
            data: function(params){
                brgypl = $('#plBrgy2').val();
                return{
                    search: params.term,
                    brgypl:brgypl,
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
            }
        }
    });

    $('#hlName').select2({
        placeholder: "Select Houseleader",
        allowClear: true,
        dropdownParent: $('#addHLModal'),
        ajax:{
            url:encoderDataVlHL,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                brgyhl = $('#hlBrgy').val();
                return{
                    search: params.term,
                    brgyhl:brgyhl
                };
            },
            processResults: function (data) {
                var houseleaders = data.items.map(function(item) {
                    return {
                        id: item.id,
                        text: item.Name,
                        purok: item.hlids
                    }
                });
                return {
                    results: houseleaders
                };
            },
            cache: true
        }
    });

    $('#hlName').on('change', function(){
        var selectedData = $(this).select2('data')[0];
        var id = selectedData.id;
        var text = selectedData.text;
        $('#hlId').val(id);
        $('#hlnamemodal').val(text);
        // console.log('Selected ID:', id);
        // console.log('Selected Text:', text);
    });

    $('.plss').select2({
        dropdownParent: $('#ajaxForm'),
        placeholder: "Select PB/PCs",
        ajax:{
            url:encoderDataPBPC,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                fltbrgy = $('#barangay').val();
                muncit = $('#muncit').val();
                return{
                    search: params.term,
                    fltbrgy: fltbrgy,
                    muncit:muncit
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
            }
        }
    });

    $('#hls').select2({
        dropdownParent: $('#ajaxForm'),
        placeholder: "Select House Leader",
        ajax:{
            url:encoderDataHL,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                hlsmuncit = $('#muncit').val();
                hlsbrgy = $('#barangay').val();
                return{
                    search: params.term,
                    hlsmuncit:hlsmuncit,
                    hlsbrgy:hlsbrgy
                };
            },
            processResults: function(data){
                var houseleaders = data.items.map(function(item) {
                    // console.log(item);

                    return {
                        id: item.id,
                        text: item.houseleader,
                        purok: item.purok,
                        sqn: item.sqn
                    }
                });

                return {
                    results: houseleaders

                };

            },

            cache: true
        }
    });

    $('#brgySelect').select2({
        placeholder: "Select Barangay",
        ajax:{
            url:"{{ route('stamargarita.brgy3') }}",
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            allowClear: true,
            maximumSelectionLength: 1,
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
            }
        }
    });

    $('#filterBrgy').select2({
        placeholder: "Filter Barangay",
        allowClear: true,
        ajax:{
            url: encoderDataBrgy,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                // var muncitss = $('#filterMuncit').val();
                return{
                    search: params.term
                    // muncitss:muncitss
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
            }
        }
    });

    $('#filterMuncit').on('change', function(){
        $('#houseleaderSelect, #purokLeader, #filterBrgy').val('').trigger('change');
    });

    $('#houseleaderSelect').on('change', function(){
        $('#setPurok, #seqno').val('');
        var selectedData = $(this).select2('data')[0];
        $.each(selectedData, function(key, value){
            $('#houseleaderSelectName').val(selectedData.text);
            $('#setPurok').val(selectedData.purok);
            $('#seqno').val(selectedData.sqn);
            $('#hlvid').val(selectedData.vid);
            // console.log(selectedData.sqn);
            return false;
        })
    });

    $('#houseleaderSelect').select2({
        placeholder: "Select Houseleader",
        allowClear: true,
        maximumSelectionLength: 1,
        ajax:{
            url:encoderDataHL,
            type:"POST",
            dataType:"json",
            delay:250,
            quietMillis: 100,
            data: function(params){
                hlsmuncit = $('#filterMuncit').val();
                hlsbrgy = $('#filterBrgy').val();
                return{
                    search: params.term,
                    hlsmuncit:hlsmuncit,
                    hlsbrgy:hlsbrgy
                };
            },
            processResults: function (data) {

                var houseleaders = data.items.map(function(item) {
                    return {
                        id: item.id,
                        text: item.houseleader,
                        purok: item.purok,
                        sqn: item.sqn,
                        vid: item.vid
                    }
                });

                return {
                    results: houseleaders
                };
            },
            cache: true
        }
    });

    $('#plList2').on('click','#plulnames li', function(){
        // alert($('.pid').attr('p-data'));
        $('#plids').val($('.pids').attr("pis-data"));
        $('#purok').val($('.pid').attr("ps-data"));
        $('#pl').val($(this).text());
        $('#plList2').fadeOut();
    });

    // pllist2

    $('.mbHL').on('click', function(){
            $('#hlBrgy').on('change', function(){
                hlmuncitmodal = $('#filterMuncit').val();
                $('#hlmuncitmodal').val(hlmuncitmodal);
            });

            var hlbrgyssss = $('#filterBrgy').val();
            $('#hl_brgy').val(hlbrgyssss);

            if( hlbrgyssss === null){
                Swal.fire({
                    title: "Message",
                    text: "Please Select Barangay First!",
                    icon: "warning"
                });
            }else{

                $.ajax({
                    url: encoderDataGetHLId,
                    method:'GET',
                    data:{ hlbrgyssss:hlbrgyssss},
                    success: function(response){
                        $('.modal-title').html('Add House Leader');
                        $('#h_id').val(response);
                        $('#seqNum').val(Number(response)+1);
                    },
                    error: function(xhr, status, error){
                        //console.log(error);
                        if(error) {
                        }
                    }
                });

                $('#addHLModal').modal({backdrop: 'static', keyboard: false});
                $('#addHLModal').modal('show');
            }

            var newOptionsPL = new Option(hlbrgyssss , hlbrgyssss, true, true);
            $('#hlBrgy').append(newOptionsPL).trigger('change');

    });

    //save hl to db
    var formHL = $('#hlForm')[0];
    $('#bthHLSave').click(function(){
        hls = $('#hlnamemodal').val();
        hlspurok = $('#hlPurok').val();
        hlssqn = $('#seqNum').val();
        hlvid = $('#hlId').val();

        var formHlData = new FormData(formHL);

        $.ajax({
            url:encoderDataDsaveHL ,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formHlData,
            success: function(response) {
                table.draw();
                $('#hlForm')[0].reset();
                $('#addHLModal').modal('toggle');

                var newOptionsHL = new Option(hls, hls, true, true);
                $('#houseleaderSelect').append(newOptionsHL).trigger('change');

                $('#setPurok').val(hlspurok);
                $('#seqno').val(hlssqn);
                $('#hlvid').val(hlvid);

                if(response) {
                    toastr.success(response.success);
                }
                $('#hlName, #hlBrgy').val('').trigger('change');
            },
            error: function(xhr, status, error){
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });

    });

    //save pl to db

    $('.formModalPL').on('click', function(){
        var muncitssss = $('#filterMuncit').val()
        $('#plmuncit').val(muncitssss);
    });

    var formPL = $('#plForm')[0];
    $('#btnPLSave').click(function(){

        var nwPL = $('#plName').val();
        var formPlData = new FormData(formPL);

        $.ajax({
            url: encoderDataSavePL,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formPlData,
            success: function(response) {
                table.draw();
                $('.formModalPL').trigger('click');
                $('#plForm')[0].reset();
                if(response) {
                    toastr.success(response.success);
                }
                $('#plBrgy2, #plName').val('').trigger('change');
                $('#addPLModal').modal('toggle');
            },
            error: function(xhr, status, error){
                //console.log(error);
                if(error) {
                    var err = eval("(" + xhr.responseText + ")");
                    toastr.error(err.message);
                }
            }
        });

    });

    $('#mbPL').on('click', function(){

        var plbrgy = $('#filterBrgy').val();

        if(plbrgy === null ){
            Swal.fire({
                title: "Message",
                text: "Please Select Barangay First!",
                icon: "warning"
            });
        }else{
            $('#addPLModal').modal({backdrop: 'static', keyboard: false});
            $('#addPLModal').modal('show');
        }

        var newOptionsPL = new Option(plbrgy , plbrgy, true, true);
        $('#plBrgy2').append(newOptionsPL).trigger('change');

        $('#plBrgy2').select2({
        placeholder: "Select Barangay",
        dropdownParent: $('#addPLModal'),
        minimumResultsForSearch: -1
        // ajax:{
        //     url:"{{ route('calbayog2025.brgy3') }}",
        //     type:"POST",
        //     dataType:"json",
        //     delay:250,
        //     quietMillis: 100,
        //     allowClear: true,
        //     data: function(params){
        //         muncitss = $('#filterMuncit').val();
        //         return{
        //             search: params.term,
        //             muncitss:muncitss
        //         };
        //     },
        //     processResults: function(data){
        //         return{
        //             results: $.map(data.items, function(obj,i) {
        //                 return {
        //                         id:obj, text:obj
        //                     };
        //                 })
        //             }
        //         }
        //     }
        });
    });

    $('#clearText').on('click', function(){
        // purokLeader setPurok seqno houseleaderSelect
        // alert("1");
        $('#purokLeader').text("");
        $('#houseleaderSelect').text("");
        $('#seqno').val("");
        $('#setPurok').val("");
    });

    $('#newList').on('click', function(){
        const id = [];
        var userid = $('#userid').val();
        var brgy = $('#filterBrgy').val();
        var pl = $('#purokLeader').val();
        var hl = $('#houseleaderSelectName').val();
        var hlsel = $('#houseleaderSelect').val();
        var purok = $('#setPurok').val();
        var seqno = $('#seqno').val();
        var hlvid = $('#hlvid').val();
        var checkboxes = $('#calbcity2025').find('input[type="checkbox"]');
        var atLeastOneChecked = false;

        checkboxes.each(function() {
            if ($(this).is(':checked')) {
                atLeastOneChecked = true;
                return false; // Break out of the loop if at least one checkbox is checked
            }
        });

        if(brgy === null){
            Swal.fire({
                title: "Message",
                text: "Please select Barangay!",
                icon: "warning"
            });
        }else if(hlsel === null){
            Swal.fire({
                title: "Message",
                text: "Please select Houseleader!",
                icon: "warning"
            });
        }else{
            if (atLeastOneChecked) {

                $('.newSildaCheckBox:checked').each(function(){
                    id.push($(this).val());
                });

            } else {
                Swal.fire({
                    title: "Message",
                    text: "Nothing is Selected!",
                    icon: "warning"
                });s
            }

        }

        $.ajax({
        url:encoderDataSaveSelda,
        method:"post",
        data: {id:id,pl:pl,hl:hl, purok:purok,seqno:seqno,userid:userid,hlvid:hlvid },
        success:function(response){
            if(response.success){
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Your work has been saved",
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload();

            }else
                {
                    toastr.error('Something went wrong. Please try again!');
                }
            }
        });

    });

    $('.dataTables_filter input[type="search"]').css(
        {'width':'350px','display':'inline-block'}
    );

    $(document).on('click','.vmdelete', function(){

        var id = $(this).data("id");
        // alert(id);
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#1cbb8c",
        cancelButtonColor: "#f32f53",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                url: encoderDataVMdelete +'/'+id,
                method:'GET',
                dataType: 'json',
                success: function (data) {
                    table.ajax.reload(null, false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
                    Swal.fire(
                        'Deleted',
                        'User has been deleted!',
                        'success'
                    )
            }
        })

    });

    // sq muncit code - brgy code - numbering er calbayog: 0201-1
    // sq muncit code - brgy code - numbering er calbayog: 0201-1
});
