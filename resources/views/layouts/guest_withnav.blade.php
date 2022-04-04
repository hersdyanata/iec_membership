<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ env('APP_NAME') }}</title>

	<!-- Global stylesheets -->
	{{-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css"> --}}
	<link href="{{ asset('assets/global/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/global/css/icons/material/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/global/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/material/css/all.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('assets/global/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/global/js/main/bootstrap.bundle.min.js') }}"></script>
	<!-- /core JS files -->




	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('assets/global/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/global/js/plugins/extensions/jquery_ui/widgets.min.js') }}"></script>
	<script src="{{ asset('assets/global/js/main/bootstrap.bundle.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('assets/global/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script src="{{ asset('assets/global/js/plugins/uploaders/dropzone.min.js') }}"></script>

	<script src="{{ asset('assets/global/js/demo_pages/widgets_content.js') }}"></script>

	<script src="{{ asset('assets/global/js/plugins/ui/fullcalendar/main.min.js') }}"></script>
	<script src="{{ asset('assets/global/js/demo_pages/fullcalendar_advanced.js') }}"></script>


	@yield('page_resources')

	<script src="{{ asset('assets/material/js/app.js') }}"></script>
	<script src="{{ asset('js/custom.js') }}"></script>
	<!-- /theme JS files -->
</head>

<body>
    @include('includes.front_navbar')

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">
			@yield('content')
		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
	
</body>
</html>
