<!DOCTYPE html>
<html lang="en">
<head>
<!-- Primary Meta Tags -->
<title>Feed-Deck | Primer sistema con Inteligencia Artifical</title>
<meta name="title" content="Feed-Deck | Primer sistema con Inteligencia Artifical">
<meta name="description" content="FeedDeck, es el primer sistema con Inteligencia Artifical desarrollado para la gestion de cuentas | Actualizando constantemente para mejorar. ">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.feed-deck.com/login">
<meta property="og:title" content="Feed-Deck | Primer sistema con Inteligencia Artificial">
<meta property="og:description" content="FeedDeck, es el primer sistema con Inteligencia Artificial desarrollado para la gestion de cuentas | Actualizando constantemente para mejorar. ">
<meta property="og:image" content="https://www.feed-deck.com/image.jpg">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://www.feed-deck.com/login">
<meta property="twitter:title" content="Feed-Deck | Primer sistema con Inteligencia Artificial">
<meta property="twitter:description" content="FeedDeck, es el primer sistema con Inteligencia Artifical desarrollado para la gestion de cuentas | Actualizando constantemente para mejorar. ">
<meta property="twitter:image" content="https://www.feed-deck.com/image.jpg">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset("plantilla/login/images/icons/favicon.ico")}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/vendor/bootstrap/css/bootstrap.min.css")}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css")}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css")}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/vendor/animate/animate.css")}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/vendor/css-hamburgers/hamburgers.min.css")}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/vendor/select2/select2.min.css")}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/css/util.css")}}">
	<link rel="stylesheet" type="text/css" href="{{asset("plantilla/login/css/main.css")}}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-50 p-r-50 p-t-77 p-b-30">
            <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                @csrf
                @error('username')
                <div class="alert alert-danger">{{$message}}</div>                  

                @enderror

                @error('password')
                <div class="alert alert-danger">{{$message}}</div>                  

                        @enderror
                <span class="login100-form-title p-b-55">
						Login.
						<p>Feed | Deck . com</p>
					</span>

					<div class="wrap-input100  m-b-16">               
                    
						<input class="input100" type="username" name="username" placeholder="User">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-envelope"></span>
						</span>
                        
					</div>
                    
					<div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-lock"></span>
						</span>
                        
					</div>

					
					
					<div class="container-login100-form-btn p-t-25">
						<button class="login100-form-btn">
							Iniciar sesión
						</button>
					</div>

					<div class="text-center w-full p-t-20">
						<span class="txt1">
							¿No tienes una cuenta?
						</span>

						<a class="txt1 bo1 hov1" href="{{route('register')}}">
							Regístrate					
						</a>
                       

					</div>
                    <div class="text-center w-full ">
                    <span class="txt1">
							¿Mala memoria?
						</span>
                        <a class="txt1 bo1 hov1" href="{{route('password.request') }}">
							Recuperar contraseña					
						</a>

					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="{{asset("plantilla/login/vendor/jquery/jquery-3.2.1.min.js")}}></script>
<!--===============================================================================================-->
	<script src="{{asset("plantilla/login/vendor/bootstrap/js/popper.js")}}"></script>
	<script src="{{asset("plantilla/login/vendor/bootstrap/js/bootstrap.min.js")}}></script>
<!--===============================================================================================-->
	<script src="{{asset("plantilla/login/vendor/select2/select2.min.js")}}"></script>
<!--===============================================================================================-->
	<script src="{{asset("plantilla/login/js/main.js")}}"></script>

</body>
</html>