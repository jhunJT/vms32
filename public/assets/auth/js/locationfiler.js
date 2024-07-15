$(document).ready(function() {
    var muncitList = [
        {District: 'District I', muncit:'ALMAGRO',code:'000101', long:'11.916663', lat:'124.2833322' ,tbname:'almagro'},
        {District: 'District I', muncit:'CALBAYOG CITY',code:'000201', long:'12.07028972', lat:'124.5403862' ,tbname:''},
        {District: 'District I', muncit:'GANDARA',code:'000301', long:'12.0166666', lat:'124.8166634' ,tbname:''},
        {District: 'District I', muncit:'MATUGUINAO',code:'000401', long:'12.1499994', lat:'124.8833298' ,tbname:''},
        {District: 'District I', muncit:'PAGSANGHAN',code:'000501', long:'11.9666628', lat:'124.7999968' ,tbname:''},
        {District: 'District I', muncit:'SAN JORGE',code:'000601', long:'11.9833294', lat:'124.8166634' ,tbname:'sanjorge'},
        {District: 'District I', muncit:'SANTA MARGARITA',code:'000701', long:'12.038', lat:'124.658' ,tbname:'stamargaritas'},
        {District: 'District I', muncit:'STO NIÑO',code:'000801', long:'11.8999964', lat:'124.4333316' ,tbname:''},
        {District: 'District I', muncit:'TAGAPULAN',code:'000901', long:'12.0499998', lat:'124.1499994' ,tbname:''},
        {District: 'District I', muncit:'TARANGNAN',code:'001001', long:'11.8999964', lat:'124.749997' ,tbname:''},
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
        {District: 'District II', muncit:'ZUMARRAGA',code:'002702', coordinates:'',tbname:''},
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

            });

        // console.log(coordselected);
    });

});
