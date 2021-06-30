<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>

    <div class="container mt-5">
        <form method="post" id="add_product">
            @csrf
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="product_name" id="product_name" placeholder="Product Name" class="form-control">
            </div>
            <div class="form-group">
                <label>Quantity in Stock</label>
                <input type="text" name="quantity" id="quantity" placeholder="Quantity in Stock" class="form-control">
            </div>
            <div class="form-group">
                <label>Price Per Item</label>
                <input type="text" name="price" id="price" placeholder="Price Per Item" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" name="add_product_btn" disabled id="add_product_btn" class="btn btn-success" value="Add Product">
            </div>
        </form>

        <hr>

        <h3>Products</h3>
        <table class="table">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity in Stock</th>
                <th>Price per item</th>
                <th>Date Submitted</th>
                <th>Total Value Number</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="product_list">
            </tbody>
        </table>


        <!-- edit modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="update_product">
                            @csrf
                            <input type="hidden" id="id_edit">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" id="product_name_edit" placeholder="Product Name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Quantity in Stock</label>
                                <input type="text" name="quantity" id="quantity_edit" placeholder="Quantity in Stock" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Price Per Item</label>
                                <input type="text" name="price" id="price_edit" placeholder="Price Per Item" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="update_product_btn" disabled id="update_product_btn" class="btn btn-success" value="Update Product">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            // activate submit button on js loaded
            $('#add_product_btn').removeAttr('disabled')

            // populate table rows
            loadProducts()

            // on form submit
            $('#add_product').on('submit', function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('product.store') }}",
                    method: 'post',
                    data: {
                        product_name: $('#product_name').val(),
                        quantity: $('#quantity').val(),
                        price: $('#price').val()
                    },
                    success: function(result){
                        //refresh table row
                        $("#product_list").html('')
                        loadProducts()
                    }});
            });

            function loadProducts() {
                $.get("{{ route('products') }}", function (res) {
                    let total = 0;
                    $.each(res, function(key, value) {
                        $("#product_list").append(
                            '<tr>' +
                            `<td>${value['product_name']}</td>` +
                            `<td>${value['quantity']}</td>` +
                            `<td>${formatCurrency(value['price'])}</td>` +
                            `<td>${value['date']}</td>` +
                            `<td>${formatCurrency(value['quantity'] * value['price'])}</td>` +
                            '<td><button onclick="' + getSingleProduct(value['id']) + '" class="mr-3">Edit</button></td>' +
                            '</tr>'
                        );
                        total += (value['quantity'] * value['price'])
                    });
                    total > 0 ? $('#product_list').append('<tr><td style="font-weight: bold">Total</td><td></td><td></td><td></td><td style="font-weight: bold">'+ formatCurrency(total) +'</td></tr>') : null
                })
            }

            function getSingleProduct(id) {
               $.get(`product/edit/${id}`, function (res) {
                    $('#id_edit').val(res['id'])
                    $('#product_name_edit').val(res['product_name'])
                    $('#quantity_edit').val(res['quantity'])
                    $('#price_edit').val(res['price'])

                    $('#editModal').modal('toggle')
                })
            }

            function formatCurrency(value) {
                let formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',

                    // These options are needed to round to whole numbers if that's what you want.
                    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                });

                return formatter.format(value)
            }
        });
    </script>
    </body>
</html> 
