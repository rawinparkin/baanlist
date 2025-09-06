@extends('frontend.frontend_dashboard')


@section('meta')
<title>บทความทั้งหมด - baanlist</title>

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
                    <h2>บทความทั้งหมด</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>บทความทั้งหมด</li>
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
                        <i class="sl sl-icon-magnifier"></i> ค้นหาบทความ
                    </button>
                </form>


                <div id="searchResult" class="dashboard-list-box with-icons margin-top-10 " style="display: none;">
                    <h4>บทความที่พบ</h4>
                    <ul id="searchContent">
                        <!-- Result will be injected here -->
                    </ul>
                </div>
            </div>


            <!-- All Types -->
            <div class="col-lg-12 col-md-12 margin-top-30">
                <div class="dashboard-list-box with-icons margin-top-10">
                    <h4>บทความทั้งหมด</h4>
                    <ul>
                        @foreach($bpost as $item)
                            @php
                                $original = $item->post_image;
                                $thumbnail = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                            @endphp 
                        <li>
                            <img src="{{asset($thumbnail) }}" class="all-prop-img" />
                            {{$item->post_title}}
                            <div class="buttons-to-right">
                                <a href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"
                                    class="button gray">
                                    <i class="sl sl-icon-eye"></i> ดู
                                </a>
                                <a href="{{route('edit.blog.post', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                <a href="{{ route('delete.blog.post', $item->id) }}" class="button gray delete-link"
                                    data-id="{{ $item->id }}">
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
                <a href="{{route('add.blog.post')}}" class="button border"><i class="sl sl-icon-plus"></i>
                    เพิ่มบทความ</a>
            </div>
        </div>

        {{-- <div class="row margin-top-30">
            <div class="col-md-12 text-center">
                <a href="{{route('make.blog.thumbnail')}}" class="button border"><i class="sl sl-icon-plus"></i>
                    Thumbnail</a>
            </div>
        </div> --}}

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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent the default link behavior

                const href = this.getAttribute('href');

                Swal.fire({
                    title: "คุณแน่ใจหรือไม่?",
                    text: "คุณต้องการลบบทความนี้",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#aaa",
                    confirmButtonText: "ลบเลย",
                    cancelButtonText: "ยกเลิก",
                    customClass: {
                        popup: "swal-wide"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect manually to the delete URL
                        window.location.href = href;
                    }
                });
            });
        });
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

            axios.post("{{ route('admin.search.blog.id') }}", {
                id: id,
                _token: "{{ csrf_token() }}"
            }).then(function(response) {
                const data = response.data;

                if (data.success) {
                    resultList.innerHTML = `
                    <li>
                        <img src="${assetBaseUrl}${data.item.img}" class="all-prop-img" alt="${data.item.title}" />
                        ${data.item.title}
                        <div class="buttons-to-right">
                        <a href="/blog-details/${data.item.id}/${data.item.slug}"
                                    class="button gray">
                                    <i class="sl sl-icon-eye"></i> ดู
                                </a>
                            <a href="/edit/blog/post/${data.item.id}" class="button gray"><i class="sl sl-icon-note"></i> แก้ไข</a>
                            <a href="/delete/blog/post/${data.item.id}" class="button gray delete-link" data-id="${data.item.id}">
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
                        text: "ไม่พบบทความนี้",
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