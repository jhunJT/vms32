$(document).ready(function() {
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        var tbuser =  $('.tbUserView').DataTable({
            ajax: dashboardAdminView,
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'contno', name: 'contno'},
                {data: 'role', name: 'role' },
                {data: 'status', name: 'status', class:'text-center'},
                {data: 'last_seen_minutes_ago', name: 'last_seen_minutes_ago'},
                {data: 'online_status', name: 'online_status', class:'text-center' },
                {data: 'action', name: 'action', orderable: false, searchable: false, class:'text-center'},
            ],
            columnDefs: [
                {className: 'align-middle', targets: '_all'},
                {className: 'dt-center', targets: '-1'},
                {targets: 5, render: function(data,type, row, meta){
                    if (data === 'Active') {
                        return '<button class="btn btn-success btn-rounded waves-effect waves-light">Active</button>';
                    } else {
                        return '<button class="btn btn-danger btn-rounded waves-effect waves-light">Inactive</button>';
                    }

                    // var options = {
                    //     'active': 'Active',
                    //     'inactive': 'Inactive'
                    // };
                    // var select = '<select class="form-select">';
                    // for (var key in options) {
                    //     var selected = (key === data) ? 'selected' : '';
                    //     select += '<option value="' + key + '" ' + selected + '>' + options[key] + '</option>';
                    // }
                    // select += '</select>';
                    // return select;
                }}
            ]

        });

        $('#createNewUser').click(function () {
            $('#savedata').html('Save New User');
            $('#id').val('');
            $('#postForm').trigger("reset");
            $('#modelHeading').html("Register New User");
            $('#postmodal').modal('show');
            $('#error').html('');
        });

        $('body').on('click', '.userEdit', function () {
            $('#savedata').html('Update User');
            var ids = $(this).data("id");

            $.ajax({
                url: dashboardAdminEdit + '/'+ids,
                method:'GET',
                dataType: 'json',
                success: function(data){
                    // alert(data.status);
                    $('#modelHeading').html("Update User");
                    $('#postmodal').modal('show');
                    $('#id').val(data.id);
                    $('#username').val(data.username);
                    $('#ustatus').val(data.status).change();
                    $('#level').val(data.role).change();
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#birthday').val(data.birthday);
                    $('#district').val(data.district).change();
                    $('#muncit').val(data.muncit).change();
                    $('#contno').val(data.contno);
                    $('#tbname').val(data.tbname);
                    $('#error').html('');
                },
                    error: function(error){
                        toastr.error(data['responseJSON']['message']);
                }
            });
        });

        $('#savedata').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
            $.ajax({
                data: $('#postForm').serialize(),
                url: dashboardAdminStore,
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#postForm').trigger("reset");
                    $('#postmodal').modal('hide');
                    toastr.success('Data saved successfully','Success');
                    tbuser.ajax.reload(null, false);
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#error').html("<div class='alert alert-danger alert-dismissible fade show'>"+data['responseJSON']['message'] + "</div>");
                    $('#savedata').html('Save/Update User');
                }
            });
        });

        $('body').on('click', '.userDelete', function () {
            var id = $(this).data("id");
            var name = $(this).data("name");
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
                    url: dashboardAdminDelete +'/' + id,
                    data: {name:name},
                    method:'GET',
                    dataType: 'json',
                    success: function (data) {
                        tbuser.ajax.reload(null, false);
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

        var muncitList = [
            {District: 'District I', muncit:'ALMAGRO',code:'000101', long:'11.916663', lat:'124.2833322' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'CITY OF CALBAYOG',code:'000201', long:'12.07028972', lat:'124.5403862' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'GANDARA',code:'000301', long:'12.0166666', lat:'124.8166634' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'MATUGUINAO',code:'000401', long:'12.1499994', lat:'124.8833298' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'PAGSANGHAN',code:'000501', long:'11.9666628', lat:'124.7999968' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'SAN JORGE',code:'000601', long:'11.9833294', lat:'124.8166634' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'SANTA MARGARITA',code:'000701', long:'12.038', lat:'124.658' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'STO NIÑO',code:'000801', long:'11.8999964', lat:'124.4333316' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'TAGAPULAN',code:'000901', long:'12.0499998', lat:'124.1499994' ,tbname:'d1nle2023s'},
            {District: 'District I', muncit:'TARANGNAN',code:'001001', long:'11.8999964', lat:'124.749997' ,tbname:'d1nle2023s'},
            {District: 'District II', muncit:'BASEY',code:'001102', coordinates:'',tbname:''},
            {District: 'District II', muncit:'CALBIGA',code:'001202', coordinates:'',tbname:''},
            {District: 'District II', muncit:'CITY OF CATBALOGAN',code:'001302', coordinates:'',tbname:''},
            {District: 'District II', muncit:'DARAM',code:'001402', coordinates:'',tbname:''},
            {District: 'District II', muncit:'HINABANGAN',code:'001502', coordinates:'',tbname:''},
            {District: 'District II', muncit:'JIABONG',code:'001602', coordinates:'',tbname:''},
            {District: 'District II', muncit:'MARABUT',code:'001702', coordinates:'',tbname:''},
            {District: 'District II', muncit:'MOTIONG',code:'001802', coordinates:'',tbname:''},
            {District: 'District II', muncit:'PARANAS',code:'001902', coordinates:'',tbname:''},
            {District: 'District II', muncit:'PINABACDAO',code:'002002', coordinates:'',tbname:''},
            {District: 'District II', muncit:'SAN JOSE DE BUAN',code:'002102', coordinates:'',tbname:''},
            {District: 'District II', muncit:'SAN SEBASTIAN',code:'002202', coordinates:'',tbname:''},
            {District: 'District II', muncit:'SANTA RITA',code:'002302', coordinates:'',tbname:''},
            {District: 'District II', muncit:'SAN SEBASTIAN',code:'002402', coordinates:'',tbname:''},
            {District: 'District II', muncit:'TALALORA',code:'002502', coordinates:'',tbname:''},
            {District: 'District II', muncit:'VILLAREAL',code:'002602', coordinates:'',tbname:''},
            {District: 'District II', muncit:'ZUMARRAGA',code:'002702', coordinates:'',tbname:''}
        ]

        $('#district').change(function() {
            $('#coordinates').val('');
            $('#muncit').html("<option disabled selected>Choose Municipality/City</option>");
            const muncitselected = muncitList.filter(m=>m.District == $("#district").val());
                muncitselected.forEach(element => {
                const option = "<option val='"+element.muncit+"'>"+element.muncit+"</option>";
                $("#muncit").append(option);
            });
        });

        function findMunicipalityByCode(codeToFind) {
            for (var i = 0; i < muncitList.length; i++) {
                if (muncitList[i].code === codeToFind) {
                    return muncitList[i].muncit; // Return the municipality if code matches
                }
            }
            return null; // Return null if code is not found
        }

        $('#muncit').change(function() {
            // alert();
            const coordselected = muncitList.filter(m=>m.muncit == $("#muncit").val());
            coordselected.forEach(element => {
                $('#tbname').val(element.tbname);
                $('#u_lat').val(element.lat);
                $('#u_long').val(element.long);

            });

        });
});
