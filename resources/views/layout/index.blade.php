<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
    @yield('contain') 

    @include('partial.modal')
 
</body>
      
<script type="text/javascript">
  $(function () {
      
    /*------------------------------------------
     --------------------------------------------
     Pass Header Token
     --------------------------------------------
     --------------------------------------------*/ 
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
      
    /*------------------------------------------
    --------------------------------------------
    Render DataTable
    --------------------------------------------
    --------------------------------------------*/
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('student.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'class', name: 'class'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
      
    /*------------------------------------------
    --------------------------------------------
    Click to Button
    --------------------------------------------
    --------------------------------------------*/
    $('#createNewStudent').click(function () {
        $('#saveBtn').val("create-product");
        $('#student_id').val('');
        $('#studentForm').trigger("reset");
        $('#modelHeading').html("Add New Student");
        $('#ajaxModel').modal('show');
    });
      
    /*------------------------------------------
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.editStudent', function () {
      var student_id = $(this).data('id');
      $.get("{{ route('student.index') }}" +'/' + student_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Student");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#student_id').val(data.id);
          $('#name').val(data.name);
          $('#class').val(data.class);
          $('#status').val(data.status);
      })
    });
      
    /*------------------------------------------
    --------------------------------------------
    Create Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
      
        $.ajax({
          data: $('#studentForm').serialize(),
          url: "{{ route('student.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
       
              $('#studentForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
           
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });
      
    /*------------------------------------------
    --------------------------------------------
    Delete Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deleteStudent', function () {
     
        var student_id = $(this).data("id");

        
        if (confirm("Are you sure you want to delete?")) {
        $.ajax({
            type: "DELETE",
            url: "{{ route('student.store') }}"+'/'+student_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
    });

    // edit status
    $('body').on('change', '.status-checkbox', function() {
        var siswaId = $(this).data('id');
        var newStatus = $(this).is(':checked');

        $.ajax({
            url: '/status/' + siswaId,
            type: 'PUT',
            data: { status: newStatus, _token: '{{ csrf_token() }}' },
            success: function(response) {
                alert(response.message);
                table.ajax.reload();
            }
        });
    });
       
  });
</script>
</html>