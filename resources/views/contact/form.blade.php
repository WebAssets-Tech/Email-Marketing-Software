@section('name')
	<!-- Section 1 -->
	<section class="space-5 pb-5">
		<div class="container text-center">
			<h1 class="font-weight-bold display-4">Let's Talk</h1>
			<p class="lead w-lg-75 mx-auto">Let's talk about everything to make the products you love most and succeed your business. We will reply within 1 business day 100% of the time!</p>
		</div>
	</section>
	<!-- End Section 1 -->

	
	<!-- Section 2 -->
	<section class="space-3 pt-0">
		<div class="container">
			<div class="card card-body px-md-5 py-md-5">
				<h6 class="font-weight-bold">Your Information</h6>
				<form class="mt-4" id="contact" action="smtp.php" method="POST">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Full name</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email address</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Your working email" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="company">Company</label>
								<input type="text" class="form-control" id="company" name="company" placeholder="Your company" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="subject">Subject</label>
								<input type="text" class="form-control" id="subject" name="subject" placeholder="Your subject" required>
							</div>
						</div>
						<div class="col-12 mt-4">
							<div class="form-group">
								<label for="message">Message</label>
								<textarea class="form-control" id="message" rows="10" name="message" placeholder="Your message" required></textarea>
							</div>
						</div>
					</div>
					<button id="contactBtnSubmit" type="submit" class="btn btn-primary btn-block">Send Message</button>
					<button id="contactBtnSending" class="btn btn-primary btn-block d-none" type="button" disabled>
						<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
						<span class="sr-only">Loading...</span>
						<span class="ml-1">Sending</span>
					</button>
				</form>
				<div id="contactAlertSuccess" class="alert alert-success mt-4 d-none" role="alert">
					Thank you for contacting us! our team will be reply your message shortly.
				</div>
				<div id="contactAlertError" class="alert alert-danger mt-4 d-none" role="alert">
					Failed to send message, try again!
				</div>
			</div>
		</div>
	</section>
	<!-- End Section 2 -->

	<!-- Section 3 -->
	<section class="space-3 pt-0">
		<div class="container">
			<div class="row text-center justify-content-around">
				<div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="zoom-in-up" data-aos-delay="100">
					<div class="px-4 py-4 hover-translate-y h-100">
						<i class="ri-phone-fill ri-3x text-primary"></i>
						<h5 class="mt-4 mb-2">Phone</h5>
						<p>We will reply within 1 business day 100% of the time.</p>
						<a href="tel:+1234567890">+1 234-567-890</a>
					</div>
				</div>
				<div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="zoom-in-up" data-aos-delay="200">
					<div class="px-4 py-4 hover-translate-y h-100">
						<i class="ri-mail-fill ri-3x text-primary"></i>
						<h5 class="mt-4 mb-2">Email</h5>
						<p>We will reply within 1 business day 100% of the time.</p>
						<a href="/cdn-cgi/l/email-protection#87efe2ebebe8a9f6e8e8f5e6f4e6c7e0eae6eeeba9e4e8ea"><span class="__cf_email__" data-cfemail="731b161f1f1c5d021c1c0112001233141e121a1f5d101c1e">[email&#160;protected]</span></a>
					</div>
				</div>
				<div class="col-md-6 col-lg-3 mb-4 mb-lg-0" data-aos="zoom-in-up" data-aos-delay="300">
					<div class="px-4 py-4 hover-translate-y h-100">
						<i class="ri-map-pin-fill ri-3x text-primary"></i>
						<h5 class="mt-4 mb-2">Location</h5>
						<p>We will reply within 1 business day 100% of the time.</p>
						<a href="#">Get Directions</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Section 3 -->
@endsection
