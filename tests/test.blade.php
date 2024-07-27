
$('#gggrant').on('select2:select', function(e) {
    var selectedData = e.params.data;
    var parentId = selectedData.parentId; // Assuming you have parentId or similar field in data structure
    console.log(selectedData);
    Find parent based on parentId
    var parent = data.find(function(group) {
        return group.children.some(function(child) {
            return child.id === selectedData.id;
        });
    });

    if (parent) {
        console.log('Selected group:', parent.text);
    }
});


$('#gggrant').on('change', function(){
     var selectedData = $(this).select2('data')[0];
     if (selectedData) {
         $('#grnt_type').val(selectedData.text);
         $('#gdate').val(selectedData.date);
         $('#gamount').val(selectedData.gtype);
         $('#gremarks').val(selectedData.gremarks);
     } else {
         $('#grnt_type').val('');
         $('#gdate').val('');
         $('#gamount').val('');
         $('#gremarks').val('');
     }
 });

 $('#agname').on('select2:unselecting', function(e) {
     $('#vuid').val('');
 });

 // "drawCallback": function(settings) {
    //     var api = this.api();
    //     var startIndex = api.context[0]._iDisplayStart; // Get the index of the first row displayed on the current page
    //     api.column(0, {order:'current'}).nodes().each(function(cell, i) {
    //         cell.innerHTML = startIndex + i + 1; // Update the row number
    //     });
    // },
