@extends('frontend.frontend_dashboard')
@section('meta')
<title>404 - baanlist</title>
<meta name="description" content="baanlist - ขายบ้าน หาบ้าน คอนโด ที่ดิน">
<meta name="keywords" content="บ้าน, คอนโด, ที่ดิน, ขายบ้าน, หาบ้าน">

@endsection

@section('main')

<!-- Header Container
================================================== -->
@include('frontend.home.header')
<!-- Titlebar
================================================== -->
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>404 ไม่พบหน้า</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs">
					<ul>
						<li><a href="{{route('home.index')}}">หน้าหลัก</a></li>
						<li>404 ไม่พบหน้า</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</div>

<!-- Container -->
<div class="container">

	<div class="row">
		<div class="col-md-12">

			<section id="not-found" class="center">
				<h2>404 <i class="fa fa-question-circle"></i></h2>
				<p>ขออภัย ไม่พบหน้าที่คุณกำลังค้นหา</p>
                <div class="text-align-center">
                    <a href="{{ route('home.index') }}" class="button">กลับไปหน้าหลัก</a>
                </div>
			</section>

		</div>
	</div>

</div>
<!-- Container / End -->



<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->







@endsection
