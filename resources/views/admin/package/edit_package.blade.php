@extends('frontend.frontend_dashboard')


@section('meta')
<title>แก้ไขแพ็คเกจ - baanlist</title>

@endsection
@section('main')

<!-- Header Container
================================================== -->
@include('admin.body.header')
<!-- Header Container / End -->

<!-- Dashboard -->
<div id="dashboard">

    <!-- Navigation
================================================== -->

    @include('admin.body.sidebar')

    <!-- Content
	================================================== -->
    <div class="dashboard-content">

        <!-- Titlebar -->
        <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <h2>แก้ไขแพ็คเกจ</h2>
                    <!-- Breadcrumbs -->
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">กรอกข้อมูล</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('admin.package.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{$package->id}}">

                            <!-- Details -->
                            <div class="my-profile">

                                <label>ชื่อแพ็คเกจ</label>
                                <input type="text" name="package_name" id="package_name" value="{{$package->package_name}}">

                                <label>ราคาแพ็คเกจ</label>
                                <input type="text" name="package_cost" id="package_cost" value="{{$package->package_cost}}">

                                <label>คำอธิบาย(ราคา)</label>
                                <input type="text" name="cost_desc" id="cost_desc" value="{{$package->cost_desc}}">

                                <label>ราคา</label>
                                <input type="number" name="price" id="price" value="{{$package->price}}">

                                <label>จำนวนเครดิต</label>
                                <input type="number" name="package_credits" id="package_credits" value="{{$package->package_credits}}">

                                <label for="billing_type">ประเภทการเรียกเก็บเงิน</label>
                                <select name="billing_type" id="billing_type" class="form-control">
                                    <option value="one_time" {{ $package->billing_type === 'one_time' ? 'selected' : '' }}>ชำระครั้งเดียว</option>
                                    <option value="monthly" {{ $package->billing_type === 'monthly' ? 'selected' : '' }}>รายเดือน</option>
                                    <option value="yearly" {{ $package->billing_type === 'yearly' ? 'selected' : '' }}>รายปี</option>
                                </select>

                                <label>จำนวนวันหมดอายุ</label>
                                <input type="number" name="validity_days" id="validity_days" value="{{$package->validity_days}}">

                                <label for="is_featured">Featured</label>
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="0" {{ !$package->is_featured ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $package->is_featured ? 'selected' : '' }}>Yes</option>
                                </select>

                                <label>คำอธิบาย(Featured)</label>
                                <input type="text" name="feature_desc" id="feature_desc" value="{{$package->feature_desc}}">

                                <label>คำอธิบาย(แพ็คเกจ)</label>
                                <textarea name="description" id="description">{{$package->description}}</textarea>

                             

                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit">
                                        <i class="sl sl-icon-note"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-lg-4">
                                    <a href="{{ route('admin.all.package') }}" class="button border margin-top-15">
                                        <i class="sl sl-icon-close"></i> ยกเลิก
                                    </a>
                                </div>
                            </div>



                        </form>
                    </div>
                </div>
            </div>


        </div>



    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')

@endsection