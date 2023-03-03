<?php 

session_start();

require_once('../models/database/database.php');
require_once('../models/user.php');

$user = getUser($_SESSION['user_id']);

if(isset($_SESSION['user_id'])){
$planrecibido= $_GET['plan'];
if ($planrecibido == 'basico'){
	$plan = 'Plan Básico SECODE_QR';
	$precio = '9900';
	$etiqueta=22;
}elseif($planrecibido == 'estandar'){
	$plan = 'Plan Estandar SECODE_QR';
	$precio = '26700';
	$etiqueta=56;
}elseif ($planrecibido =='premium'){
	$plan = 'Plan Premium SECODE_QR';
	$precio = '51000';
	$etiqueta=99;
}
}else{
	header('Location: ./iniciar.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos Secode_QR</title>
    <!-- LInk desde el panel de datos de Pypal, (SDK Javascript)-->
    <script src="https://www.paypal.com/sdk/js?client-id=AVhw-RveYQh4KiLBXWa8eXUIo0pAE3d0xrgq9VK9MHGvZ65eozHU62aKJYLGNqrqWXdT0gm-En9KYCX2&currency=USD"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
	<link rel="stylesheet" href="assets/css/service.css" />
  <!-- favicon -->
	<link rel="shortcut icon" type="image/png" href="./assets/img/logo.png">
	<!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">

	<?php include('./templates/header.php') ?>
	<?php  include('./templates/sweetalerts2.php')?>

    <link rel="stylesheet" href="./assets/css/Paypal.css" />
    
</head>
<body>
    <div class="container">
    <header>

	<?php include('./templates/navBar.php') ?>

    </header>
    </div>
<main>
			<div class="container">		
				<div class="row" style="margin:35vh auto;">
					<div class="col-lg-5 col-md-3 col-sm-12">
						<h4>Detalles de pago</h4>
                        <hr>
						<div lcass="row">
							<div class="col-10">
								<div id="paypal-button-container"></div>
							</div>
						</div>
						
						<div lcass="row">
							<div class="col-10 text-center">
								<div class="checkout-btn"></div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-7 col-md-7 col-sm-12">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Producto</th>
										<th>Valor</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
																				<tr>
												<td style="color:#4b0081"><b><?php echo $plan ?></b></td>
												<td style="color:#4ab7cf">$<b><?php echo $precio ?></b></td>
											</tr>
																				
										<tr>
											<td colspan="2">
												<p class="h3 text-end" id="total"><?php echo $precio ?></p>
											</td>
										</tr>
										
																		
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</main>
    <script>
		var myHeaders = new Headers();
			myHeaders.append("apikey", "dErSiffTvccdgLEGW8P8cRe6jMdYCPRL");

			var requestOptions = {
			method: 'GET',
			redirect: 'follow',
			headers: myHeaders
			};

			fetch("https://api.apilayer.com/fixer/convert?to=USD&from=COP&amount=<?= $precio ?>", requestOptions)
			.then(response => response.text())
			.then(result => {let data = JSON.parse(result); 
                console.log(`la moneda es :${data.result}`);
				var total = Math.round(data.result);

				paypal.Buttons({
            //Se modifican los botones importados de la libreria de Paypal
            style:{
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units:[{
                        amount:{
                            value: total
                        }
                    }]
                });
            },
            onApprove: function(data, actions){
                actions.order.capture().then(function(detalles){ 
					//time out utilizado para mostrar mensaje de aprobacion.
					Swal.fire(
							'Realizado correctamente',
							'En un momento sera redirigido a sus detalles de compra y se factura.',
							'success'
						)
					 setTimeout(() => {
						window.location.href="Finpago.php?plan=<?= $etiqueta?>";
					 }, 7000); // 7 segs 
                        
                });

            },

            onCancel: function(data,){
                Swal.fire({
					icon: 'error',
					title: 'Pago cancelado',
					text: 'Se ha cancelado el pago!',
					footer: '<a href="servicios.html">Intentar nuevamente?</a>'
					})
            }
        }).render('#paypal-button-container');
			})
			.catch(error => {
				Swal.fire({
					icon: 'error',
					title: 'No se puede realizar el pago',
					text: `Error interno!${error}`,
					footer: '<a href="servicios.html">Intentar nuevamente?</a>'
					})
			});
        //Users   email: sb-7cosb23375447@personal.example.com    Password: zX1[zA<f
    </script>
<footer style="position:fixed; bottom:0; width:100%;">
<div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<p>Copyrights &copy; 2022 - <a href="https://imransdesign.com/">SECØDE_QR</a>, Salud e información al instante.</p>
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-github"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end copyright 	-->
	

<?php include('./templates/footerWebUser.php') ?>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</footer>

</body>
</html>