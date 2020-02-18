<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>laravel 6  Ajax CRUD Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
 
    <style>
        .container{
        padding: 0.5%;
        }
        .error {
        color:red !important;
        } 
    </style>
</head>
<body>
 
    <div class="container">
        <div class="row">
            <div class="col-12">
            <a href="javascript:void(0)" class="btn btn-success mb-2" id="create-new-category">Add Category</a> 
            <table class="table table-bordered" id="laravel_crud">
                <thead>
                    <tr>
                    <th>Id</th>
                    <th>Category Description</th>
                    <th>Category Sequence</th>
                    <td colspan="2">Action</td>
                    </tr>
                </thead>
                <tbody id="categories-crud">
                    @foreach($categories as $p_info)
                        <tr id="category_id_{{ $p_info->id }}">
                        <td>{{ $p_info->id  }}</td>
                        <td>{{ $p_info->description }}</td>
                        <td>{{ $p_info->sequence }}</td>
                        <td colspan="2">
                            <a href="javascript:void(0)" id="edit-category" data-id="{{ $p_info->id }}" class="btn btn-info mr-2">Edit</a>
                            <a href="javascript:void(0)" id="delete-category" data-id="{{ $p_info->id }}" class="btn btn-danger delete-category">Delete</a>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->links() }}
            </div> 
        </div>
    </div>

    <!--Modal-->
    <div class="modal fade" id="ajax-crud-modal" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="categoryCrudModal"></h4>
                </div>
                <form id="categoryForm" name="categoryForm" class="form-horizontal"  >
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="category_id" id="category_id">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" name="description" class="form-control" id="description" placeholder="Category Description">
                            </div>
                            <div class="form-group">
                                <label for="sequence">Sequence</label>
                                <input type="text" name="sequence" class="form-control" id="sequence" placeholder="Category Sequence">
                            </div>      
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btn-save" value="create">
                        Save changes
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

<!--Ajax CRUD Logic-->
<script >
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /*  When user click add  button */
        $('#create-new-category').click(function () {
            $('#btn-save').val("create-category");
            $('#categoryForm').trigger("reset");
            $('#categoryCrudModal').html("Add New Category");
            $('#ajax-crud-modal').modal('show');

            //console.log($('meta[name="csrf-token"]').attr('content')); 


        });
 
        /* When click edit  */
        $('body').on('click', '#edit-category', function () {
            var category_id = $(this).data('id');
                $.get('categories/' + category_id +'/edit', function (data) {
                $('#categoryCrudModal').html("Edit Category");
                $('#btn-save').val("edit-category");
                $('#ajax-crud-modal').modal('show');
                $('#category_id').val(data.id);
                $('#description').val(data.description);
                $('#sequence').val(data.sequence);
            })
        });
   
        /* When click delete  */
        $('body').on('click', '.delete-category', function () {
            var category_id = $(this).data("id");
            if(confirm("Are You sure want to delete !")) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('categories')}}"+'/'+category_id,
                    success: function (data) {
                        $("#category_id_" + category_id).remove();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });   
    });

////// Logic for Add and Update
    if ($("#categoryForm").length > 0) {
        $("#categoryForm").validate({

    //Client-side validations
            rules: {
                description: {
                    required: true,
                    maxlength: 50
                },
                sequence: {
                    required: true,
                    digits:true,
                    maxlength:12,
                }, 
            },
            messages: {
                description: {
                    required: "Please Enter the Description",
                    maxlength: "Description should not exceed 50 characters."
                },
                sequence: {
                    required: "Please Enter Sequence",
                    digits: "Please enter only numbers",
                    maxlength: "The sequence should not be exceed 12 digits",
                },        
            },

            submitHandler: function(form) {
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                $.ajax({
                    data: $('#categoryForm').serialize(),
                    url: "{{ route('categories.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        var category = '<tr id="category_id_' + data.id + '"><td>' + data.id + '</td><td>' + data.description + '</td><td>' + data.sequence + '</td>';
                        category += '<td colspan="2"><a href="javascript:void(0)" id="edit-category" data-id="' + data.id + '" class="btn btn-info mr-2">Edit</a>';
                        category += '<a href="javascript:void(0)" id="delete-category" data-id="' + data.id + '" class="btn btn-danger delete-category ml-1">Delete</a></td></tr>';
                        if (actionType == "create-category") {
                            $('#categories-crud').prepend(category);
                        } 
                        else {
                            $("#category_id_" + data.id).replaceWith(category);
                        }
                        $('#categoryForm').trigger("reset");
                        $('#ajax-crud-modal').modal('hide');
                        $('#btn-save').html('Save Changes');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                    }
                });
            }
        })
    }
</script>
</html>