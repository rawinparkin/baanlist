@php
$blog = App\Models\BlogPost::latest()->limit(3)->get();
@endphp

<section class="fullwidth margin-top-0 padding-top-75 padding-bottom-75" data-background-color="#fff">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h3 class="headline margin-bottom-55">
                    <strong class="headline-with-separator">บทความ</strong>
                </h3>
            </div>
        </div>

        <div class="row">


            @foreach($blog as $item)

                @php
                    $original = $item->post_image;
                    $thumbnail = preg_replace('#(upload/blog/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                @endphp 

            <!-- Blog Post Item -->
            <div class="col-md-4">
                <a href="{{route('blog.details', ['id' => $item->id, 'slug' => $item->post_slug]) }}"
                    class="blog-compact-item-container">
                    <div class="blog-compact-item">
                        <img src="{{ asset($thumbnail) }}" alt="{{$item->post_title}}" loading="lazy">
                        <span class="blog-item-tag">{{$item->type->category_name}}</span>
                        <div class="blog-compact-item-content">
                            <ul class="blog-post-tags">
                                <li>{{ thaiDate($item->created_at) }}</li>
                            </ul>
                            <h3>{{$item->post_title}}</h3>
                            <p>{{$item->short_descp}}</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Blog post Item / End -->
            @endforeach





            <div class="col-md-12 centered-content">
                <a href="{{route('blog.list')}}" class="button border margin-top-10">อ่านบทความอื่นๆ</a>
            </div>

        </div>

    </div>
</section>