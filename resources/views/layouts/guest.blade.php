<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ env('APP_NAME') }}</title>

	<!-- Global stylesheets -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> -->
	<link href="{{ asset('assets/global/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/material/css/all.min.css') }}" rel="stylesheet" type="text/css">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/global/images/favicon/favicon-32x32.png') }}">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('assets/global/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/global/js/main/bootstrap.bundle.min.js') }}"></script>
	<!-- /core JS files -->
	<script src="{{ asset('js/app.js') }}" defer></script>

	<!-- Theme JS files -->
	<script src="{{ asset('assets/material/js/app.js') }}"></script>
	<!-- /theme JS files -->

</head>

<body class="@yield('bgcolor')">

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				@yield('content')

			</div>
			<!-- /content area -->
		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>