@extends('frontend.frontend_dashboard')
@section('meta')

<title>บทความ | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">
{!! $seo->adsense_headtag !!}

@php
    $original = $blog[0]->post_image;
    $thumbnail = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
@endphp 
<!-- Open Graph for Facebook, LINE -->
<meta property="og:title" content="บทความ | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{asset($thumbnail ?? 'frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="บทความ | baanlist" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{asset($thumbnail ?? 'frontend/images/banner.jpg') }}" />
<link rel="stylesheet" href="{{ asset('frontend/css/add2.css') }}">

<style>
    .google-ads {
        height: 150px
    }
</style>

@endsection
@section('main')



<!-- Header Container
================================================== -->
@include('frontend.property.body.header2')
<!-- Header Container / End -->



<!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2>บทความต่างๆ</h2><span>ล่าสุด</span>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs">
                    <ul>
                         <li><a href="{{route('home.index')}}">หน้าหลัก</a></li>
                        <li>บทความ</li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<!-- Content
================================================== -->

<div class="container">

    <!-- Blog Posts -->
    <div class="blog-page">
        <div class="row">
            <div class="col-lg-9 col-md-8 padding-right-30">


                @foreach($blog as $item)

                <!-- Blog Post -->
                <div class="blog-post">

                    <!-- Img -->
                    <a href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"
                        class="post-img">
                        <img src="{{asset($item->post_image)}}" alt="{{$item->post_title}}" loading="lazy">
                    </a>

                    <!-- Content -->
                    <div class="post-content">
                        <h3><a
                                href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}">{{$item->post_title}}</a>
                        </h3>

                        <ul class="post-meta">
                            <li>{{ thaiDate($item->created_at) }}</li>
                            <li><a href="#">{{$item->type->category_name}}</a></li>
                        </ul>

                        <p>{{$item->short_descp}}</p>

                        <a href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"
                            class="read-more">อ่านต่อ <i class="fa fa-angle-right"></i></a>
                    </div>

                </div>
                <!-- Blog Post / End -->
                @endforeach




                <!-- Pagination -->
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Pagination -->
                        <div class="pagination-container margin-bottom-40">
                            <nav class="pagination">
                                {{ $blog->links('vendor.pagination.custom') }}
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Pagination / End -->

            </div>

            <!-- Blog Posts / End -->


            <!-- Widgets -->
            <div class="col-lg-3 col-md-4">
                <div class="sidebar right">

                    <!-- Widget -->
                    {{-- <div class="widget">
                        <h3 class="margin-top-0 margin-bottom-25">ค้นหาบทความ</h3>
                        <div class="search-blog-input">
                            <div class="input"><input class="search-field" type="text" placeholder="Type and hit enter"
                                    value="" /></div>
                        </div>
                        <div class="clearfix"></div>
                    </div> --}}
                    <!-- Widget / End -->


                    <!-- Widget -->
                    <div class="widget">

                        <h3>บทความตามประเภท</h3>
                        <ul class="widget-tabs">

                            @foreach($bcategory as $item)

                            <!-- Post #1 -->
                            <li>
                                <div class="widget-content">
                                    <div class="widget-text">
                                        <h5><a href="{{route('blog.category', $item->id)}}">• {{ $item->category_name }}
                                                ({{ $item->posts_count }})</a></h5>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            <!-- Post #2 -->

                        </ul>

                    </div>
                    <!-- Widget / End-->


                    <!-- Widget -->
                    <div class="widget margin-top-40">

                        <h3>บทความที่น่าสนใจ</h3>
                        <ul class="widget-tabs">

                            @foreach($latestBlog as $item)
                            @php
                                $original2 = $item->post_image;
                                $thumbnail2 = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original2);
                            @endphp
                            <!-- Post #1 -->
                            <li>
                                <div class="widget-content">
                                    <div class="widget-thumb">
                                        <a
                                            href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"><img
                                                src="{{asset($original2)}}" alt="{{$item->post_title}}" loading="lazy"></a>
                                    </div>

                                    <div class="widget-text">
                                        <h5><a href="pages-blog-post.html">{{$item->post_title}}</a></h5>
                                        <span>{{ thaiDate($item->created_at) }}</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            @endforeach
                            <!-- Post #2 -->

                        </ul>

                    </div>
                    <!-- Widget / End-->


                    <!-- Widget -->
                    <div class="widget margin-top-40" >
                        <div class="google-ads">{!! $seo->adsense !!}</div>
                    </div>
                    <!-- Widget / End -->

                    <div class="clearfix"></div>
                    <div class="margin-bottom-40"></div>
                </div>
            </div>
        </div>
        <!-- Sidebar / End -->


    </div>
</div>








<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>








@endsection

@section('scripts')
@vite(['resources/js/app.js'])





@endsection