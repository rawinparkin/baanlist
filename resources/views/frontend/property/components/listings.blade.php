<!-- Listings -->
<div class="row fs-listings-inner">
    @foreach($props as $item)

    
    <!-- Listing Item -->
    <div class="col-lg-6 col-md-6">
        <a href="{{ route('property.details', ['id' => $item->id, 'slug' => $item->detail->property_slug]) }}"
            class="listing-item-container compact" data-marker-id="3">
            <div class="listing-item swiper mySwiper">
                <div class="swiper-wrapper">
                    @forelse($item->galleries as $gallery)
                    <div class="swiper-slide">
                        <img src="{{ asset('upload/property/' . $gallery->property_id . '/thumbnails/' . $gallery->filename) }}" loading="lazy"
                            alt="property image">
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/images/popular-location-04.jpg') }}" alt="no image available" loading="lazy">
                    </div>
                    @endforelse
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
                <span class="like-icon" data-id="{{ $item->id }}"></span>
            </div>

            <div class="listing-item-inner padding-left-10">
                @if($item->property_status=='2')
                <span class="price-listing-number">฿{{ addCommas($item->price->rent_price) }} / เดือน</span>
                @else
                <span class="price-listing-number">฿{{ addCommas($item->price->sell_price) }}</span>
                @endif
                <div class="clear"></div>
                <span class="bed-bath-number">
                    @if (!empty($item->detail->bedrooms))
                        {{ $item->detail->bedrooms }} ห้องนอน
                    @endif

                    @if (!empty($item->detail->bathrooms))
                        @if (!empty($item->detail->bedrooms)) | @endif
                         {{ $item->detail->bathrooms }} ห้องน้ำ
                    @endif

                    @if (!empty($item->detail->land_size))
                        @if (!empty($item->detail->bedrooms) || !empty($item->detail->bathrooms)) | @endif
                        {{ $item->detail->land_size }} ตร.ม.
                    @endif
                </span>
                <p class="listing-location-number">{{ $item->location->subdistrict->name_th }},
                    {{ $item->location->province->name_th }}
            </div>
        </a>
    </div>
    <!-- Listing Item / End -->
    @endforeach

    <!-- Pagination -->
    <div class="col-md-12">
        <div class="pagination-container margin-top-15 margin-bottom-40">
            <nav class="pagination">
                {{ $props->appends(request()->query())->links('vendor.pagination.custom') }}
            </nav>
        </div>
        <div class="copyrights margin-top-0">{{$setting->copyright}}</div>
    </div>
</div>