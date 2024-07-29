
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

    /* Center the buttons container */
 .dataTables_wrapper .dt-buttons {
    text-align: center;
    margin: 10px 0;
}

Customize the ColVis button
.dt-button.colvis {
    background-color: #007bff; /* Background color */
    color: white;              /* Text color */
    border: none;              /* Remove border */
    border-radius: 4px;        /* Rounded corners */
    padding: 6px 12px;         /* Padding */
    font-size: 14px;           /* Font size */
    position: relative;        /* For positioning dropdown */
}

.dt-button.colvis:hover {
    background-color: #0056b3; /* Background color on hover */
    color: white;              /* Text color on hover */
}

/* Customize the dropdown menu */
.dt-button-collection {
    background-color: #f8f9fa; /* Background color */
    border: 1px solid #dee2e6; /* Border */
    border-radius: 4px;        /* Rounded corners */
    display: block;            /* Block display for vertical alignment */
    position: absolute;        /* Positioning for dropdown */
    top: 100%;                 /* Position the dropdown below the button */
    left: 0;                   /* Align to the left of the button */
    width: 100%;               /* Ensure full width alignment */
    transform: none;           /* Remove any transformation */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Add shadow for visibility */
    z-index: 1000;             /* Ensure dropdown is above other content */
}

.dt-button-collection .dt-button {
    border: none;              /* Remove border */
    padding: 8px 12px;        /* Padding */
    font-size: 14px;          /* Font size */
    color: #212529;           /* Text color */
    display: block;           /* Ensure block display for vertical layout */
    text-align: left;         /* Align text to the left */
}

.dt-button-collection .dt-button:hover {
    background-color: #e9ecef; /* Background color on hover */
    color: #212529;            /* Text color on hover */
}
