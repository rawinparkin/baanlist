@extends('frontend.frontend_dashboard')


@section('meta')
<title>ลงบทความ - baanlist</title>
<style>
    .blog-post-img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        aspect-ratio: 1 / 1;
        border-radius: 10px;
    }
</style>
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
                    <h2>ลงบทความ</h2>
                    <!-- Breadcrumbs -->
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">กรอกข้อมูล</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('blog.post.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Row -->
                            <div class="row with-forms">

                                <!-- Keywords -->
                                <div class="col-md-6">
                                    <h5>ชื่อบทความ</h5>
                                    <input type="text" name="post_title">
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <h5>ประเภท</h5>
                                    <select class="chosen-select" name="blogcat_id" id="blogcat_id">
                                        <option value="">เลือกประเภท</option> <!-- no 'disabled' -->
                                        @foreach($blogcat as $btype)
                                        <option value="{{ $btype->id }}">{{ $btype->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <!-- Row / End -->

                            <!-- Description -->
                            <div class="form">
                                <h5>คำบรรยาย</h5>
                                <textarea class="WYSIWYG" name="long_descp" cols="40" rows="3" id="summary"
                                    spellcheck="true" placeholder=""></textarea>
                            </div>

                            <div class="row margin-top-10">
                                <div class="col-md-6">
                                    <h5>ภาพปกบทความ</h5>

                                    <input type="file" name="photo" id="image" />
                                    <img id="showImage" src="{{ url('upload/blog/no-image.jpg') }}" alt="profile"
                                        class="blog-post-img">
                                </div>
                                <div class="col-md-6">
                                    <h5>วิดีโอ</h5>

                                    <input type="file" name="video" id="video" />

                                </div>

                                <div class="col-md-6">
                                    <h5>วิดีโอลิ้งค์</h5>
                                    <input type="input" name="video_link" />

                                </div>
                            </div>



                            <div class="row margin-top-30">
                                <div class="col-lg-2">
                                    <button class="button margin-top-15" type="submit"><i class="sl sl-icon-plus"></i>
                                        ลงบทความ</button>
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


<script>
    document.getElementById('typeForm').addEventListener('submit', function(e) {
        const name = document.querySelector('input[name="post_title"]').value.trim();
        const type = document.querySelector('select[name="blogcat_id"]').value.trim();
        const desc = tinymce.get("summary").getContent({
            format: "text"
        }).trim();
        const photo = document.querySelector('input[name="photo"]').files[0];

        let errorMessage = '';

        if (!name) {
            errorMessage = "กรุณากรอกชื่อบทความ";
        } else if (!type) {
            errorMessage = "กรุณาเลือกประเภท";
        } else if (!desc) {
            errorMessage = "กรุณากรอกคำบรรยาย";
        } else if (!photo) {
            errorMessage = "กรุณาอัปโหลดภาพปกบทความ";
        }

        if (errorMessage) {
            e.preventDefault(); // Stop form submission
            Toastify({
                text: errorMessage,
                duration: 3000,

                gravity: "top",
                position: "right",
                style: {
                    background: "#f44336"
                },
            }).showToast();
        }
    });
    $('#image').change(function(e) {
        const file = e.target.files[0];
        const fileType = file.type;

        if (fileType === 'image/heic' || file.name.endsWith('.heic')) {
            $('#showImage').attr('src', '/images/heic-placeholder.png'); // Custom icon or message
            alert('ไม่สามารถแสดงตัวอย่างไฟล์ HEIC ในเบราว์เซอร์ได้ ไฟล์จะถูกแปลงหลังจากอัปโหลดแล้ว');
        } else {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#showImage').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
</script>


<!-- Tinymce Text Editor  -->
<script type="text/javascript" src="{{ asset('frontend/vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea.WYSIWYG',
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        license_key: 'gpl',
        statusbar: false,
        content_css: [
            '//www.tiny.cloud/css/codepen.min.css'
        ],

        valid_elements: 'a[href|target=_blank],strong/b,em/i,br,ul,ol,li,p,span[style],img[src|alt|width|height]',

        setup: function(editor) {
            editor.on('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault(); // stop default Enter behavior
                    editor.execCommand('InsertLineBreak'); // insert <br>
                }
            });
        }
    });
</script>



@endsection