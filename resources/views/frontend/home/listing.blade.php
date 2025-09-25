
<div class="container margin-top-30">
    <div class="row">

        <div class="col-md-12">
            <h4 class="headline margin-bottom-35">
                <strong class="headline-with-separator">อสังหาฯ เพิ่มใหม่</strong>
            </h4>
        </div>



        <div class="col-md-12">
            <div class="row">


                @foreach($property as $item)

                @php
                    $original = $item->detail->cover_photo;
                    $thumbnail = preg_replace('#(upload/property/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                @endphp 

                    <!-- Listing Item -->
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('property.details', ['id' => $item->id, 'slug' => $item->detail->property_slug]) }}"
                            class="listing-item-container compact">
                            <div class="listing-item">
                                <img 
                                    src="{{ asset($thumbnail) }}" 
                                    onerror="this.onerror=null;this.src='{{ asset($original) }}';" 
                                    alt="{{ $item->detail->property_name }}" 
                                    loading="lazy">
                                <span class="like-icon" data-id="{{ $item->id }}"></span>
                            </div>
                            <div class="listing-item-inner">
                                @if($item->property_status == '1' && $item->price->sell_price != null &&
                                $item->price->sell_price != '0')
                                <span class="price-listing-number">฿{{ addCommas($item->price->sell_price) }}</span>
                                @elseif($item->property_status == '2' && $item->price->rent_price != null &&
                                $item->price->rent_price != '0')
                                <span class="price-listing-number">฿{{ addCommas($item->price->rent_price) }}</span>
                                @elseif($item->property_status == '3' && $item->price->sell_price != null &&
                                $item->price->sell_price != '0')
                                <span class="price-listing-number">฿{{ addCommas($item->price->sell_price) }} |
                                    ฿{{ addCommas($item->price->rent_price) }}</span>
                                @endif
                                <div class="clear"></div>

                                @if($item->detail->bedrooms != null && $item->detail->bedrooms != '0')
                                <span class="bed-bath-number">{{ $item->detail->bedrooms }} ห้องนอน |
                                    {{ $item->detail->bathrooms }} ห้องน้ำ</span>
                                @elseif($item->detail->land_size != null)
                                <span class="bed-bath-number">{{ $item->detail->land_size }} ไร่</span>
                                @endif
                                <p class="listing-location-number">{{ $item->location->subdistrict->name_th }},
                                    {{ $item->location->province->name_th }}
                                </p>
                            </div>
                        </a>
                    </div>
                    <!-- Listing Item / End -->
                    @if(($loop->iteration % 4) == 0)
                        <div class="clear"></div>
                    @endif

                @endforeach





            </div>
        </div>



    </div>
</div>