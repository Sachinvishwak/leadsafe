/*Add */
var data_list         = $('.dataTables-example-list');
var norecord          = $(data_list).data('no-record-found');
 var No_Record_found  = (norecord) ? norecord: 'No Record found';
data_list.DataTable({ 
    "processing"    : true, //Feature control the processing indicator.
    "serverSide"    : true, //Feature control DataTables' servermside processing mode.
    "order"         : [], //Initial no order.
    "lengthChange"  : false,
    "oLanguage"     : {
      "sEmptyTable" : '<center>'+No_Record_found+'</center>',
         "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>' 
    },
    "oLanguage": {
      "sZeroRecords" :  '<center>'+No_Record_found+'</center>',
      "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>' 
    },
    initComplete: function () {
      $('.dataTables_filter input[type="search"]').css({ 'height': '32px'});
    },
    // Sets the row-num-selection "Show %n entries" for the user
    "lengthMenu": [ 50, 100, 200, 500, 1000 ],     
    // Set the default no. of rows to display
    "oPageLength": 50 ,
    // Load data for the table's content from an Ajax source
    "ajax": {
      "url"     : base_url+$(data_list).data('list-url'),
      "type"    : "POST",
      "dataType": "json",
      "data"    : { 'id':$(data_list).data('id')},
      "headers" : { 'authToken':authToken},
      "dataSrc" : function (jsonData) {
        return jsonData.data;
      }
    },
    //Set column definition initialisation properties.
  "columnDefs": [
    { orderable: false, targets: -1 },    
  ]
  // ,dom: 'lBfrtip',
        //  buttons: [
             //  'excel',  'csv',
        //  ],
          //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
});

/*
  $('#unionName_1').change(function(){
   $('.dataTables-example-list').DataTable().ajax.reload();
  });*/