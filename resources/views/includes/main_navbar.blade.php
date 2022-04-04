<!-- Main navbar -->
@if (session('theme') == 'material' || session('theme') == 'light')
	<div class="navbar navbar-expand-lg navbar-dark bg-indigo navbar-static">
@else
	<div class="navbar navbar-expand-lg navbar-light navbar-static">
@endif
	<div class="d-flex flex-1 d-lg-none">
		<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
			<i class="icon-paragraph-justify3"></i>
		</button>

		<button data-target="#navbar-search" type="button" class="navbar-toggler" data-toggle="collapse">
			<i class="icon-search4"></i>
		</button>
	</div>

	<div class="navbar-brand text-center text-lg-left">
		{{-- <a href="index.html" class="d-inline-block"> --}}
			<img src="{{ asset('assets') }}/global/images/iec_logo_text.png" class="d-none d-sm-block" alt="" style="height: 40px;">
			<img src="{{ asset('assets') }}/global/images/iec_logo_text.png" class="d-sm-none" alt="">
		{{-- </a> --}}
	</div>

	<div class="navbar-collapse collapse flex-lg-1 mx-lg-3 order-2 order-lg-1" id="navbar-search">
		<div class="navbar-search d-flex align-items-center py-2 py-lg-0">
			<div class="form-group-feedback form-group-feedback-left flex-grow-1">
				{{-- <input type="text" class="form-control" placeholder="Search"> --}}
				{{-- <div class="form-control-feedback">
					<i class="icon-search4 text-white opacity-50"></i>
				</div> --}}
			</div>
		</div>
	</div>

	<div class="d-flex justify-content-end align-items-center flex-1 flex-lg-0 order-1 order-lg-2">
		<ul class="navbar-nav flex-row">
			<li class="nav-item nav-item-dropdown-lg dropdown">
				<a href="#" class="navbar-nav-link navbar-nav-link-toggler dropdown-toggle" data-toggle="dropdown">
					<i class="icon-make-group"></i>
					<span class="d-none d-lg-inline-block ml-2">Themes</span>
				</a>

				<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-lg-350">
					<div class="dropdown-content-body p-2">
						<div class="row no-gutters">
							<form id="theme-form" action="{{ route('switch_theme') }}" method="POST">
								@csrf
								<input type="hidden" name="theme" id="theme" readonly>
							</form>

							<div class="col-4 border-indigo">
								<a class="d-block text-body text-center ripple-dark rounded p-3 {{ (session('theme') == 'light') ? 'bg-indigo-100 border-indigo border-2' : '' }}" href="{{ route('switch_theme') }}"
								onclick="event.preventDefault();
											$('#theme').val('light');
											document.getElementById('theme-form').submit();">
									<i class="mi-brightness-low mi-2x"></i>
									<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Light</div>
								</a>
							</div>
							
							<div class="col-4">
								<a class="d-block text-body text-center ripple-dark rounded p-3 {{ (session('theme') == 'dark') ? 'bg-indigo-100 border-indigo border-2' : '' }}" href="{{ route('switch_theme') }}"
								onclick="event.preventDefault();
											$('#theme').val('dark');
											document.getElementById('theme-form').submit();">
									<i class="mi-brightness-3 mi-2x"></i>
									<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Dark</div>
								</a>
							</div>

							<div class="col-4">
								<a class="d-block text-body text-center ripple-dark rounded p-3 {{ (session('theme') == 'material') ? 'bg-indigo-100 border-indigo border-2' : '' }}" href="{{ route('switch_theme') }}"
								onclick="event.preventDefault();
											$('#theme').val('material');
											document.getElementById('theme-form').submit();">
									<i class="icon-google icon-2x"></i>
									<div class="font-size-sm font-weight-semibold text-uppercase mt-2">Material</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</li>

			{{-- <li class="nav-item nav-item-dropdown-lg dropdown">
				<a href="#" class="navbar-nav-link navbar-nav-link-toggler dropdown-toggle" data-toggle="dropdown">
					<i class="icon-pulse2"></i>
					<span class="d-none d-lg-inline-block ml-2">Activity</span>
				</a>
				
				<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-lg-350">
					<div class="dropdown-content-header">
						<span class="font-size-sm line-height-sm text-uppercase font-weight-semibold">Latest activity</span>
						<a href="#" class="text-body"><i class="icon-search4 font-size-base"></i></a>
					</div>

					<div class="dropdown-content-body dropdown-scrollable">
						<ul class="media-list">
							<li class="media">
								<div class="mr-3">
									<a href="#" class="btn btn-success rounded-pill btn-icon"><i class="icon-mention"></i></a>
								</div>

								<div class="media-body">
									<a href="#">Taylor Swift</a> mentioned you in a post "Angular JS. Tips and tricks"
									<div class="font-size-sm text-muted mt-1">4 minutes ago</div>
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<a href="#" class="btn btn-pink rounded-pill btn-icon"><i class="icon-paperplane"></i></a>
								</div>
								
								<div class="media-body">
									Special offers have been sent to subscribed users by <a href="#">Donna Gordon</a>
									<div class="font-size-sm text-muted mt-1">36 minutes ago</div>
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<a href="#" class="btn btn-primary rounded-pill btn-icon"><i class="icon-plus3"></i></a>
								</div>
								
								<div class="media-body">
									<a href="#">Chris Arney</a> created a new <span class="font-weight-semibold">Design</span> branch in <span class="font-weight-semibold">Limitless</span> repository
									<div class="font-size-sm text-muted mt-1">2 hours ago</div>
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<a href="#" class="btn btn-purple rounded-pill btn-icon"><i class="icon-truck"></i></a>
								</div>
								
								<div class="media-body">
									Shipping cost to the Netherlands has been reduced, database updated
									<div class="font-size-sm text-muted mt-1">Feb 8, 11:30</div>
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<a href="#" class="btn btn-warning rounded-pill btn-icon"><i class="icon-comment"></i></a>
								</div>
								
								<div class="media-body">
									New review received on <a href="#">Server side integration</a> services
									<div class="font-size-sm text-muted mt-1">Feb 2, 10:20</div>
								</div>
							</li>

							<li class="media">
								<div class="mr-3">
									<a href="#" class="btn btn-teal rounded-pill btn-icon"><i class="icon-spinner11"></i></a>
								</div>
								
								<div class="media-body">
									<strong>January, 2018</strong> - 1320 new users, 3284 orders, $49,390 revenue
									<div class="font-size-sm text-muted mt-1">Feb 1, 05:46</div>
								</div>
							</li>
						</ul>
					</div>

					<div class="dropdown-content-footer bg-light">
						<a href="#" class="font-size-sm line-height-sm text-uppercase font-weight-semibold text-body mr-auto">All activity</a>
						<div>
							<a href="#" class="text-body" data-popup="tooltip" title="Clear list"><i class="icon-checkmark3"></i></a>
							<a href="#" class="text-body ml-2" data-popup="tooltip" title="Settings"><i class="icon-gear"></i></a>
						</div>
					</div>
				</div>
			</li> --}}

			<li class="nav-item">
				{{-- <a href="#" class="navbar-nav-link navbar-nav-link-toggler">
					<i class="icon-switch2"></i>
				</a> --}}

				<a class="navbar-nav-link navbar-nav-link-toggler" href="{{ route('logout') }}"
					onclick="event.preventDefault();
								document.getElementById('logout-form').submit();">
					<i class="icon-switch2"></i>
					{{-- {{ __('Logout') }} --}}
				</a>

				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
					@csrf
				</form>
			</li>
		</ul>
	</div>
</div>
<!-- /main navbar -->