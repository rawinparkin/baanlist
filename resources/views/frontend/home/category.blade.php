@php
    $ptype = App\Models\PropertyType::all();
@endphp


<section class="fullwidth margin-top-0 padding-top-0 padding-bottom-20" data-background-color="#fcfcfc">
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="categories-boxes-container-alt margin-top-5 margin-bottom-10">


                    @foreach($ptype as $item)
                    <!-- Box -->
                    {{-- <a href="{{ route('search.for.cat', ['cid' => $item->id,'poid' => '0','diid' => '0','sdid' => '0', 'slug' => 'ค้นหา'.$item->type_name]) }}" class="category-small-box-alt"> --}}
                    <a href="{{ route('search.property', ['lat' => '13.7563309','lon' => '100.5017651','label' => 'กรุงเทพมหานคร','purpose' => 1,'category' => $item->id]) }}" class="category-small-box-alt">
                        <i class="{{$item->type_icon}}"></i>
                        <h4>{{$item->type_name}}</h4>

                    </a>
                    @endforeach



                </div>
            </div>
        </div>
    </div>
</section>