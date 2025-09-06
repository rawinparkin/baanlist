@extends('frontend.frontend_dashboard')
@section('meta')

<title>{{ $blog->post_title}} | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">
{!! $seo->adsense_headtag !!}


@php
    $original = $blog->post_image;
    $thumbnail = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
@endphp 

<!-- Open Graph for Facebook, LINE -->
<meta property="og:title" content="{{ $blog->post_title}} | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{asset($thumbnail ?? 'frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $blog->post_title}} | baanlist" />
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
<div id="titlebar" class="gradient" style="margin-bottom:20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 padding-top-30">

                <h2>{{$blog->post_title}}</h2><span></span>

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


            <!-- Post Content -->
            <div class="col-lg-9 col-md-8 padding-right-30">


                <!-- Blog Post -->
                <div class="blog-post single-post">

                    <!-- Img -->
                    <img class="post-img" src="{{asset($blog->post_image)}}" alt="{{$blog->post_title}}" loading="lazy">


                    <!-- Content -->
                    <div class="post-content">

                        <h3>{{$blog->post_title}}</h3>

                        <ul class="post-meta">
                            <li>{{ $blog->formatted_date_thai }}</li>
                            <li><a href="#">{{$blog->type->category_name}}</a></li>

                        </ul>

                        <p> {!! $blog->long_descp !!} </p>



                        <!-- Share Buttons -->
                        <ul class="share-buttons margin-top-40 margin-bottom-0">
                            <li>
                    <a class="fb-share" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" 
                        target="_blank" rel="noopener" >
                        <i class="bi bi-facebook move-up"></i> แชร์
                    </a>
                    </li>
                            <li><a class="twitter-share" href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text=ดูประกาศนี้สิ!" target="_blank"><i class="bi bi-twitter move-up"></i> X</a></li>
                            {{-- <li><a class="ig-share" href="#"><i class="fa fa-instagram"></i> Instagram</a></li> --}}
                            <li><a class="line-share" href="https://social-plugins.line.me/lineit/share?url={{ urlencode(Request::fullUrl()) }}" target="_blank"><i class="bi bi-line move-up"></i> LINE</a></li>
               

                        </ul>
                        <div class="clearfix"></div>

                    </div>
                </div>
                <!-- Blog Post / End -->







                <!-- Related Posts -->
                <div class="clearfix"></div>
                <h4 class="headline margin-top-25">บทความเกี่ยวข้อง</h4>
                <div class="row">

                    @foreach($bprelate as $item)

                    @php
                        $original2 = $item->post_image;
                        $thumbnail2 = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original2);
                    @endphp 

                    <!-- Blog Post Item -->
                    <div class="col-md-6">
                        <a href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"
                            class="blog-compact-item-container">
                            <div class="blog-compact-item">
                                <img src="{{asset($thumbnail2)}}" alt="{{$item->post_title}}" loading="lazy">
                                <span class="blog-item-tag">{{$item->type->category_name}}</span>
                                <div class="blog-compact-item-content">
                                    <ul class="blog-post-tags">
                                        <li>{{ thaiDate($item->created_at) }}</li>
                                    </ul>
                                    <h3>{{$item->post_title}}</h3>
                                    <p>{!! $item->short_descp !!}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Blog post Item / End -->
                    @endforeach



                </div>
                <!-- Related Posts / End -->


                <div class="margin-top-50"></div>


                <div class="clearfix"></div>



            </div>
            <!-- Content / End -->



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

                        <h3>บทความล่าสุด</h3>
                        <ul class="widget-tabs">

                            @foreach($latestBlog as $item)
                            @php
                                $original3 = $item->post_image;
                                $thumbnail3 = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original3);
                            @endphp 

                            <!-- Post #1 -->
                            <li>
                                <div class="widget-content">
                                    <div class="widget-thumb">
                                        <a
                                            href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"><img
                                                src="{{asset($thumbnail3)}}" alt="{{$item->post_title}}" loading="lazy"></a>
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