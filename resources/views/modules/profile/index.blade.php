@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
			<!-- Inner content -->
			<div class="content-inner">
				<!-- Content area -->
				<div class="content pt-0">

					<!-- Inner container -->
					<div class="d-lg-flex align-items-lg-start">
						<div class="tab-content flex-1">
							<div class="tab-pane fade active show" id="profile">
								<!-- Profile info -->
								<div class="card">
									<div class="card-header">
										<h6 class="card-title">Profile information</h6>
									</div>

                                    {{-- <div class="col-xl-3 col-sm-6">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <div class="card-img-actions d-inline-block mb-3">
                                                    <img class="img-fluid rounded-circle" src="{{ asset('assets/global/images/placeholders/placeholder.jpg') }}" width="170" height="170" alt="">
                                                    <div class="card-img-actions-overlay card-img rounded-circle">
                                                        <a href="#" class="btn btn-outline-white border-2 btn-icon rounded-pill">
                                                            <i class="icon-plus3"></i>
                                                        </a>
                                                        <a href="user_pages_profile.html" class="btn btn-outline-white border-2 btn-icon rounded-pill ml-2">
                                                            <i class="icon-link"></i>
                                                        </a>
                                                    </div>
                                                </div>
            
                                                <h6 class="font-weight-semibold mb-0">James Alexander</h6>
                                                <span class="d-block text-muted">Lead developer</span>
            
                                                <div class="list-icons list-icons-extended mt-3">
                                                    <a href="#" class="list-icons-item" data-popup="tooltip" title="Google Drive" data-container="body"><i class="icon-google-drive"></i></a>
                                                    <a href="#" class="list-icons-item" data-popup="tooltip" title="Twitter" data-container="body"><i class="icon-twitter"></i></a>
                                                    <a href="#" class="list-icons-item" data-popup="tooltip" title="Github" data-container="body"><i class="icon-github"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

									<div class="card-body">
										<form action="#">
											<div class="form-group">
												<div class="row">
													<div class="col-lg-6">
														<label>Username</label>
														<input type="text" value="Eugene" class="form-control">
													</div>
													<div class="col-lg-6">
														<label>Full name</label>
														<input type="text" value="Kopyov" class="form-control">
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row">
													<div class="col-lg-6">
														<label>Address line 1</label>
														<input type="text" value="Ring street 12" class="form-control">
													</div>
													<div class="col-lg-6">
														<label>Address line 2</label>
														<input type="text" value="building D, flat #67" class="form-control">
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row">
													<div class="col-lg-4">
														<label>City</label>
														<input type="text" value="Munich" class="form-control">
													</div>
													<div class="col-lg-4">
														<label>State/Province</label>
														<input type="text" value="Bayern" class="form-control">
													</div>
													<div class="col-lg-4">
														<label>ZIP code</label>
														<input type="text" value="1031" class="form-control">
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row">
													<div class="col-lg-6">
														<label>Email</label>
														<input type="text" readonly="readonly" value="eugene@kopyov.com" class="form-control">
													</div>
													<div class="col-lg-6">
							                            <label>Your country</label>
							                            <select class="custom-select">
							                                <option value="germany" selected>Germany</option> 
							                                <option value="france">France</option> 
							                                <option value="spain">Spain</option> 
							                                <option value="netherlands">Netherlands</option> 
							                                <option value="other">...</option> 
							                                <option value="uk">United Kingdom</option> 
							                            </select>
													</div>
												</div>
											</div>

					                        <div class="form-group">
					                        	<div class="row">
					                        		<div class="col-lg-6">
														<label>Phone #</label>
														<input type="text" value="+99-99-9999-9999" class="form-control">
														<span class="form-text text-muted">+99-99-9999-9999</span>
					                        		</div>

													<div class="col-lg-6">
														<label>Upload profile image</label>
														<div class="custom-file">
															<input type="file" class="custom-file-input" id="customFile">
															<label class="custom-file-label" for="customFile">Choose file</label>
														</div>
					                                    <span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
													</div>
					                        	</div>
					                        </div>

					                        <div class="text-right">
					                        	<button type="submit" class="btn btn-primary">Save changes</button>
					                        </div>
										</form>
									</div>
								</div>
								<!-- /profile info -->
						    </div>
						</div>

					</div>
					<!-- /inner container -->

				</div>
				<!-- /content area -->


			</div>
			<!-- /inner content -->
@endsection

@section('page_js')
<script>
    
</script>
@endsection