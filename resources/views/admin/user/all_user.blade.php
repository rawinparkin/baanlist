@extends('frontend.frontend_dashboard')


@section('meta')
<title>สมาชิก - baanlist</title>

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
                    <h2>สมาชิกทั้งหมด</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>สมาชิก</li>
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
                        <label>ค้นหาตาม UID, Email</label>
                        <input type="text" id="search_id" name="search_id"  />
                    </div>

                    <button type="submit" class="button border">
                        <i class="sl sl-icon-magnifier"></i> ค้นหาสมาชิก
                    </button>
                </form>


                <div id="searchResult" class="dashboard-list-box with-icons margin-top-10 " style="display: none;">
                    <h4>สมาชิกที่พบ</h4>
                    <ul id="searchContent">
                        <!-- Result will be injected here -->
                    </ul>
                </div>
            </div>



            <!-- All Types -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-40">
                    <h4>สมาชิกล่าสุด</h4>
                    <ul>
                        @foreach($users as $item)
                        <li>

                            <img src="{{asset('upload/users/'.$item->id.'/'.$item->photo) }}" class="all-prop-img" /> UID: {{$item->id}}
                            • ชื่อ: {{ $item->name}} •
                            {{$item->email}}
                         
                            <div class="buttons-to-right">
                               
                                <a href="{{route('admin.edit.user', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                <a href="{{ route('admin.delete.user', $item->id) }}"
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
    

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = input.value.trim();

        if (!id) {
            Toastify({
                text: "กรุณากรอก UID หรือ Email",
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: "#f44336"
                }
            }).showToast();
            return;
        }

        axios.post("{{ route('admin.search.user') }}", {
            id: id,
            _token: "{{ csrf_token() }}"
        }).then(function(response) {
            const data = response.data;

            if (data.success) {
                resultList.innerHTML = `
                    <li>
                        <img src="${assetBaseUrl}upload/users/${data.item.id}/${data.item.photo}" class="all-prop-img" />
                        
                        UID: ${data.item.id} • ชื่อ: ${data.item.name || ''} • ${data.item.email || ''}

                        <div class="buttons-to-right">
                            <a href="/admin/edit/user/${data.item.id}" class="button gray"><i class="sl sl-icon-note"></i> แก้ไข</a>
                            <a href="/admin/delete/user/${data.item.id}" class="button gray delete-link" data-id="${data.item.id}">
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
                    text: "ไม่พบสมาชิกนี้",
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