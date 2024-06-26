<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

<?php include 'includes/navbar.php'; ?>

<div class="content-wrapper">
    <div class="container">

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-sm-9">
                    <h1 class="page-header">YOUR CART</h1>
                    <div class="box box-solid">
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <th></th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th width="20%">Quantity</th>
                                    <th>Subtotal</th>
                                </thead>
                                <tbody id="tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <?php
                            if(isset($_SESSION['success'])){
                                echo "
                                    <div class='alert alert-success'>
                                        <p>".$_SESSION['success']."</p>
                                    </div>
                                ";
                                unset($_SESSION['success']);
                            }

                            if(isset($_SESSION['error'])){
                                echo "
                                    <div class='alert alert-danger'>
                                        <p>".$_SESSION['error']."</p>
                                    </div>
                                ";
                                unset($_SESSION['error']);
                            }
                        ?>
                    </div>
                    <div>
                        <h4>Checkout and upload your payment proof:</h4>
                        <form action="sales.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="payment_proof" id="payment_proof" required>
                            <button type="submit" name="checkout" class="btn btn-success" style="margin-top: 3rem;">Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>
<?php $pdo->close(); ?>
<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
var total = 0;
$(function(){
    $(document).on('click', '.cart_delete', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'cart_delete.php',
            data: {id:id},
            dataType: 'json',
            success: function(response){
                if(!response.error){
                    getDetails();
                    getCart();
                    getTotal();
                }
            }
        });
    });

    $(document).on('click', '.minus', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var qty = $('#qty_'+id).val();
        if(qty>1){
            qty--;
        }
        $('#qty_'+id).val(qty);
        $.ajax({
            type: 'POST',
            url: 'cart_update.php',
            data: {
                id: id,
                qty: qty,
            },
            dataType: 'json',
            success: function(response){
                if(!response.error){
                    getDetails();
                    getCart();
                    getTotal();
                }
            }
        });
    });

    $(document).on('click', '.add', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var qty = $('#qty_'+id).val();
        qty++;
        $('#qty_'+id).val(qty);
        $.ajax({
            type: 'POST',
            url: 'cart_update.php',
            data: {
                id: id,
                qty: qty,
            },
            dataType: 'json',
            success: function(response){
                if(!response.error){
                    getDetails();
                    getCart();
                    getTotal();
                }
            }
        });
    });

    getDetails();
    getTotal();

});

function getDetails(){
    $.ajax({
        type: 'POST',
        url: 'cart_details.php',
        dataType: 'json',
        success: function(response){
            $('#tbody').html(response);
            getCart();
        }
    });
}

function getTotal(){
    $.ajax({
        type: 'POST',
        url: 'cart_total.php',
        dataType: 'json',
        success:function(response){
            total = response;
        }
    });
}

$(document).on('click', '#checkout_button', function(e){
    e.preventDefault();
    // Redirect to checkout page or perform other checkout actions
    window.location = 'sales.php'; // Change to your checkout page URL
});
</script>
</body>
</html>
