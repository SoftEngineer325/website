window.onload = function () {

var courses = ["COSC", "ITEC", "SCIA"];
var dropdown = document.getElementById('courses');
  
//Loop through json and append it to the table
courses.forEach(function(object) {
    var option = document.createElement('option');
    option.innerHTML = '<option value="' + object + '">' + object + '</option>';
    dropdown.appendChild(option);
});

  console.info(window.location.pathname);
  if (location.pathname == "/website/pages/classadvise.html") {
      //DataTables can query the site for us and fill the table!
        $('#table1').DataTable({
          'processing': true,
          'language': {
            'loadingRecords': 'Attempting to load records...',
            'processing': 'Loaded records, displaying...'
        },
          "pagingType": "full_numbers",
          "lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
          "order": [[ 1, "asc" ]],
          "ajax": {url:"php/query.php?all_courses", type: "post"}
        });
        $('#table2').DataTable({
          'processing': true,
          "ordering": false,
          'language': {
            'loadingRecords': 'Attempting to load records...',
            'processing': 'Loaded records, displaying...',
            "emptyTable": "Please select a major to show best electives!"
        },
          "pagingType": "full_numbers",
          "lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
          //"order": [[ 1, "asc" ]],
        });
        $('.dataTables_length').addClass('bs-select');
        //Disable message box displaying when there is an error
        $.fn.dataTable.ext.errMode = 'none';
  }
}

$("#courses").change(function() {
    //Get what select the user picks
    var userRequest = $("#courses").val();
    console.log(userRequest);

    if (userRequest) {
        //Destroy the table so we can create a new one
      $('#table2').DataTable().destroy();
      $('#table2').DataTable({
        'processing': true,
        "ordering": false,
        'language': {
          'loadingRecords': 'Attempting to load records...',
          'processing': 'Loaded records, displaying...',
          "emptyTable": "Sorry, we couldn't find good electives for this major!"
      },
        "pagingType": "full_numbers",
        "lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
        //"order": [[ 1, "asc" ]],
        "ajax": {url:"php/query.php?courses=" + userRequest, type: "post"}
      });
      $('.dataTables_length').addClass('bs-select');
    }

});

$("#change_table").change(function() {
  //Destroy the table so we can create a new one
  $('#table1').DataTable().destroy();
  //Get what select the user picks
  var userRequest = $("#change_table").val();
  //Create a new table with the users request
  $('#table1').DataTable({
    'processing': true,
    "pagingType": "full_numbers",
    "lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
    "order": [[ 1, "asc" ]],
    "ajax": {url:"php/query.php?" + userRequest, type: "post"}
  });
  $('.dataTables_length').addClass('bs-select');
  //Disable message box displaying when there is an error
  $.fn.dataTable.ext.errMode = 'none';
});

