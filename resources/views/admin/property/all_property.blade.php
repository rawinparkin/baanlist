@extends('frontend.frontend_dashboard')


@section('meta')
<title>ประกาศทั้งหมด - baanlist</title>

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
                    <h2>ประกาศทั้งหมด</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>ประกาศทั้งหมด</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">

            <!-- Search -->
            <div class="col-lg-12 col-md-12">

                <form id="searchForm">
                    @csrf
                    <div>
                        <label>ค้นหาตาม ID</label>
                        <input type="text" id="search_id" name="search_id" inputmode="numeric" pattern="[0-9]*" />
                    </div>

                    <button type="submit" class="button border">
                        <i class="sl sl-icon-magnifier"></i> ค้นหาประกาศ
                    </button>
                </form>


                <div id="searchResult" class="dashboard-list-box with-icons margin-top-10 " style="display: none;">
                    <h4>ประกาศที่พบ</h4>
                    <ul id="searchContent">
                        <!-- Result will be injected here -->
                    </ul>
                </div>
            </div>




            <!-- All Types -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-40">
                    <h4>ประกาศล่าสุด</h4>
                    <ul>
                        @foreach($prop as $item)
                            @php
                                $original = $item->detail->cover_photo;
                                $thumbnail = preg_replace('#(upload/property/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                            @endphp
                        <li class="{{ $item->status == 0 ? 'bg-hidden-listing' : '' }}">

                            <img 
                                    src="{{ asset($thumbnail) }}" 
                                    onerror="this.onerror=null;this.src='{{ asset($original) }}';" 
                                    alt="{{ $item->detail->property_name }}" class="all-prop-img"
                                    loading="lazy"> ID: {{$item->id}}
                            • Owner Id: {{ $item->owner->id}} •
                            {{$item->type->type_name}} • {{$item->purpose->purpose_name}} •
                            <strong>
                                    @if($item->status == 0)
                                    <span style="color:red;">หมดอายุแล้ว</span>
                                    @else
                                    หมดอายุ: {{ thaiDate($item->expire_date) }}
                                    @endif
                            </strong>


                            <div class="buttons-to-right">
                                <a href="{{route('property.details', ['id' => $item->id, 'slug' => $item->detail->property_slug]) }}"
                                    class="button gray"><i class="sl sl-icon-eye"></i> ดู</a>
                                <a href="{{route('admin.edit.property', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                <a href="{{ route('admin.delete.property', $item->id) }}"
                                    class="button gray delete-link" data-id="{{ $item->id }}">
                                    <i class="sl sl-icon-close"></i> ลบ
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>


        </div>

        <div class="row margin-top-30">
            <div class="col-md-12 text-center">
                <a href="{{route('admin.add.property')}}" class="button border"><i class="sl sl-icon-plus"></i>
                    เพิ่มประกาศ</a>
            </div>
        </div>

    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')

@if(session('success'))
<script>
Toastify({
    text: "{{ session('success') }}",
    duration: 3000,
    gravity: "top",
    position: "right",
    style: {
        background: "#84c015"
    },
}).showToast();
</script>
@endif

<script>
document.addEventListener('click', function(e) {
    const target = e.target.closest('.delete-link');
    if (target) {
        e.preventDefault();
        const href = target.getAttribute('href');

        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการลบประกาศนี้",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ff4f58",
            cancelButtonColor: "#aaa",
            confirmButtonText: "ลบเลย",
            cancelButtonText: "ยกเลิก",
            customClass: {
                popup: "swal-wide"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('searchForm');
    const input = document.getElementById('search_id');
    const resultBox = document.getElementById('searchResult');
    const resultList = document.getElementById('searchContent');
    const assetBaseUrl = "{{ asset('') }}";
    // Only allow numbers
    input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/g, '');
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = input.value.trim();

        if (!id) {
            Toastify({
                text: "กรุณากรอกเลข ID",
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: "#f44336"
                }
            }).showToast();
            return;
        }

        axios.post("{{ route('admin.search.by.id') }}", {
            id: id,
            _token: "{{ csrf_token() }}"
        }).then(function(response) {
            const data = response.data;

            if (data.success) {
                resultList.innerHTML = `
                    <li>
                        <img src="${assetBaseUrl}${data.item.img}" class="all-prop-img" />
                        
                        ID: ${data.item.id} • Owner_Id: ${data.item.owner} • ${data.item.type} • ${data.item.status || ''} •
                        <strong>${data.item.name || ''}</strong>

                        <div class="buttons-to-right">
                            <a href="/property-details/${data.item.id}/${data.item.slug}" class="button gray">
                                <i class="sl sl-icon-eye"></i> ดู
                            </a>
                            <a href="/admin/edit/property/${data.item.id}" class="button gray"><i class="sl sl-icon-note"></i> แก้ไข</a>
                            <a href="/admin/delete/property/${data.item.id}" class="button gray delete-link" data-id="${data.item.id}">
                                <i class="sl sl-icon-close"></i> ลบ
                            </a>
                        </div>
                    </li>
                `;
                $(resultBox).slideDown();
            } else {
                resultList.innerHTML = '';
                $(resultBox).slideUp();

                Toastify({
                    text: "ไม่พบประกาศนี้",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#f44336"
                    }
                }).showToast();
            }
        }).catch(function(error) {
            console.error(error);
            Toastify({
                text: "เกิดข้อผิดพลาดในการค้นหา",
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: "#f44336"
                }
            }).showToast();
        });
    });
});
</script>



@endsection