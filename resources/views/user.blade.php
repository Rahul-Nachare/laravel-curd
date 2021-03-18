<!DOCTYPE html>
<html>
<head>
  <title>curd</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<style>
  .err-msg{
    color: red;
    font-size: 11px;
  }
</style>
<body>
<div class="container">
    <h1>Laravel Crud with Ajax</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Create New User</a><br>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>City</th>
                <th width="170px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="userForm" name="userForm" class="form-horizontal">
                   <input type="hidden" name="user_id" id="user_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-12 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                            <span class="err-msg" id="name_error"></span>
                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Email Address</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email Address" value="" maxlength="50" required="">
                            <span class="err-msg" id="email_error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label">Phone Number</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="number" name="number" placeholder="Enter Phone Number" value="" maxlength="10" required="">
                            <span class="err-msg" id="number_error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label">Gender</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="gender" name="gender">
                              <option value="">Select Gender</option>
                              <option value="male">Male</option>
                              <option value="female">Female</option>
                            </select>
                            <span class="err-msg" id="gender_error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label">City</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="" maxlength="10" required="">
                            <span class="err-msg" id="city_error"></span>
                        </div>
                    </div>

      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>  

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'gender', name: 'gender'},
                    {data: 'city', name: 'city'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });


            // model popup
             $('#createNewUser').click(function () {
                $('.err-msg').text('');
                $('#saveBtn').val("create-user");
                $('#user_id').val('');
                $('#userForm').trigger("reset");
                $('#modelHeading').html("Create New User");
                $('#ajaxModel').modal('show');
            });


             // edit action
             $('body').on('click', '.editUser', function () {
                var user_id = $(this).data('id');
                $('.err-msg').text('');
                $.get("{{ route('users.index') }}" +'/' + user_id +'/edit', function (data) {
                    $('#modelHeading').html("Edit User");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');
                    $('#user_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#city').val(data.city);
                    $('#gender').val(data.gender);
                    $('#number').val(data.phone);
                })
             });

             $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Save');

                 $('.err-msg').text('');
                  var is_error = false;

                  var name = $.trim($('#name').val());
                  var email = $.trim($('#email').val());
                  var phone = $.trim($('#number').val());
                  var city = $.trim($('#city').val());
                  var gender = $.trim($('#gender').val());

                  var email_reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
                  if (!email_reg.test(email)) {
                      scroll_validate("email", "Please Enter Valid Email Address");
                      is_error = true;
                  }
                  
                  var name_reg = /^[a-z d]{1,50}$/i;
                  if (!name_reg.test(name)) {
                      scroll_validate('name', "Please Enter Name");
                      is_error = true;
                  }

           
                 if (!phone.match('[0-9]{10}')) {
                    scroll_validate('number', 'Please Enter Valid Number');
                    is_error = true;
                  }

                  if(city == ""){
                      scroll_validate("city", "Please Enter City");
                      is_error = true;                
                  }

                  if(gender == ""){
                      scroll_validate("gender", "Please Select Gender");
                      is_error = true;                
                  }
              
                  if (is_error == true) {
                      return false;
                  }
            
                $.ajax({
                  data: $('#userForm').serialize(),
                  url: "{{ route('users.store') }}",
                  type: "POST",
                  dataType: 'json',
                  success: function (data) {
             
                      $('#userForm').trigger("reset");
                      $('#ajaxModel').modal('hide');
                      table.draw();
                 
                  },
                  error: function (data) {
                      console.log('Error:', data);
                      $('#saveBtn').html('Save Changes');
                  }
              });
            });


            // delete action
             $('body').on('click', '.deleteUser', function () {
              var user_id = $(this).data("id");
              var con = confirm("Are You sure want to delete !");
              if (con){
                  $.ajax({
                    type: "DELETE",
                    url: "{{ route('users.store') }}"+'/'+user_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
              }else{

              } 
            });

              function scroll_validate(id, message) {
                  $('#' + id + "_error").text(message);
                  $('#' + id + "_error").css('display', 'block');
                  return true;
              }
        });
  </script>

</body>
</html>