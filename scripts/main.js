window.onload = function () {
  console.info(window.location.pathname);
  if (location.pathname == "/pages/classadvise.html") {
    //load dropdown menu items for majors
    var courses = ["COSC", "CIS", "ITEC", "SCIA"];
    var dropdown = document.getElementById('courses');
      
    //Loop through array and append it to the drop down menu
    courses.forEach(function(object) {
        var option = document.createElement('option');
        option.innerHTML = '<option value="' + object + '">' + object + '</option>';
        dropdown.appendChild(option);
    });
  }

  if (location.pathname == "/pages/moneymanagement.html") {

    //load dropdown menu items for majors
    var student_types = ["Residental", "Commuter & Edgewood"];
    var dropdown_student_type = document.getElementById('studenttype');
      
    //Loop through array and append it to the drop down menu
    student_types.forEach(function(object) {
      var option = document.createElement('option');
      option.innerHTML = '<option value="' + object + '">' + object + '</option>';
      dropdown_student_type.appendChild(option);
    });

    $('#book_table').DataTable({
      'processing': true,
      "ordering": false,
      'language': {
        'loadingRecords': 'Attempting to load records...',
        'processing': 'Loaded records, displaying...',
        "emptyTable": "Waiting for your input!"
    },
      "searching": false,
      "paging": false,
      "pagingType": "full_numbers",
      "lengthMenu": [[4], [4]],
      //"order": [[ 1, "asc" ]],
    });

    $('#meal_plans').DataTable({
      'processing': true,
      "ordering": false,
      'language': {
        'loadingRecords': 'Attempting to load records...',
        'processing': 'Loaded records, displaying...',
        "emptyTable": "Waiting for your input!"
    },
      "searching": false,
      "paging": false,
      "pagingType": "full_numbers",
      "lengthMenu": [[4], [4]],
      //"order": [[ 1, "asc" ]],
    });
  }
}

$(document).ready(function() {
      //DataTables can query the site for us and fill the table!
      //Table1 = All Courses
      $('#table1').DataTable({
        'processing': true,
        'language': {
          'loadingRecords': 'Attempting to load records...',
          'processing': 'Loaded records, displaying...'
      },
        "pagingType": "full_numbers",
        "lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
        "order": [[ 1, "asc" ]],
        "ajax": {url:"php/query.php?query=all_courses", type: "post"}
      });

      //Table2 = Major Electives
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
});

//Get meal plans
$("#studenttype").change(function() {
  //Get what select the user picks
  var userRequest = $("#studenttype").val();
  console.log(userRequest);

  if (userRequest) {
      //Destroy the table so we can create a new one
    $('#meal_plans').DataTable().destroy();
    $('#meal_plans').DataTable({
      'processing': true,
      "ordering": false,
      'language': {
        'loadingRecords': 'Attempting to load records...',
        'processing': 'Loaded records, displaying...',
        "emptyTable": "Sorry, we couldn't find any meal plans!"
    },
      "pagingType": "full_numbers",
      "lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
      //"order": [[ 1, "asc" ]],
      "ajax": {url:"php/query.php?query=meal_plan&plan=" + userRequest, type: "post"}
    });
    $('.dataTables_length').addClass('bs-select');
  }
});

//Get best electives for major
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
        "ajax": {url:"php/query.php?query=electives&major=" + userRequest, type: "post"}
      });
      $('.dataTables_length').addClass('bs-select');
    }
});

//Display either all professors or the best ones for majors
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
    "ajax": {url:"php/query.php?query=" + userRequest, type: "post"}
  });
  $('.dataTables_length').addClass('bs-select');
  //Disable message box displaying when there is an error
  $.fn.dataTable.ext.errMode = 'none';
});


var maxChars;
$("#isbn13").click(function() {
  document.getElementById("clearInput").click();
  document.getElementById("books").cols = "14";
  maxChars = document.getElementById("books").cols;
});

$("#isbn10").click(function() {
  document.getElementById("clearInput").click();
  document.getElementById("books").cols = "13";
  maxChars = document.getElementById("books").cols;
});

$("#clearInput").click(function() {
  document.getElementById("books").value = "";
});

//TextArea Limits
$(document).ready(function(){
  var textArea = $('#books');
  var maxRows = textArea.attr('rows');
  maxChars = textArea.attr('cols');
  textArea.keypress(function(e){
      var text = textArea.val();
      var lines = text.split('\n');
      if (e.keyCode == 13){
          return lines.length < maxRows;
      }
      //Should check for backspace/del/etc.
      else{ 
          var caret = textArea.get(0).selectionStart;
          
          var line = 0;
          var charCount = 0;
          $.each(lines, function(i,e){
              charCount += e.length;
              if (caret <= charCount){
                  line = i;
                  return false;
              }
              //\n count for 1 char;
              charCount += 1;
          });
                
          var theLine = lines[line];
          return theLine.length < maxChars;
      }
  });
});

function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode != 45  && charCode > 31 && (charCode < 48 || charCode > 57))
      return false;

  return true;
}

//Display Books
$("#getBooks").click(function() {
  var data = document.getElementById("books");
  var books = data.value.replace(/\r\n/g,"\n").split("\n").filter(Boolean);
  if (books.length == 0) {
    return false;
  }
  books = "'" + books.join("','") + "'";
  var method = $("input[type='radio'][name='isbnRadio']:checked").val();

  console.log(method + ": " + books);
  //Destroy the table so we can create a new one
  $('#book_table').DataTable().destroy();
  //Create a new table with the users request
  $('#book_table').DataTable({
    'processing': true,
    'language': {
      'loadingRecords': 'Attempting to load records...',
      'processing': 'Loaded records, displaying...',
      "emptyTable": "Your books were not found!"
  },
    "searching": false,
    "paging": false,
    "pagingType": "full_numbers",
    "lengthMenu": [[4], [4]],
    "order": [[ 2, "asc" ]],
    "ajax": {url:"php/query.php?query=get_books&isbn=" + method + "&books=" + books, type: "post"}
  });
  $('.dataTables_length').addClass('bs-select');
  //Disable message box displaying when there is an error
  $.fn.dataTable.ext.errMode = 'none';
});
