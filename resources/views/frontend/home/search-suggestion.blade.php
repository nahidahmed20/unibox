<style>
    .search-suggestion-list{
    margin: 0;
    padding: 0;
    list-style: none;
}

.search-suggestion-list li{
    border-bottom: 1px solid #eee;
}

.search-item{
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    text-decoration: none;
    color: #222;
}

.search-item:hover{
    background: #f8f8f8;
}

.search-item img{
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
    flex-shrink: 0;
}

.search-content{
    flex: 1;
}

.search-content h6{
    margin: 0;
    font-size: 14px;
    font-weight: 500;
}

.search-price{
    font-size: 14px;
    font-weight: 600;
    color: #ff6a00;
    white-space: nowrap;
}
</style>
@if ($products->count() > 0)
    <ul class="search-suggestion-list">
        @foreach ($products as $product)
            <li>
                <a href="{{ route('product.show', $product->slug) }}" class="search-item">

                    <img src="{{ asset($product->image) }}"
                         width="50"
                         height="50"
                         alt="{{ $product->name }}">

                    <div class="search-content">
                        <h6>{{ $product->name }}</h6>
                    </div>

                    <div class="search-price">
                        ৳{{ $product->selling_price }}
                    </div>

                </a>
            </li>
        @endforeach
    </ul>
@else
    <div class="p-3 text-center">
        No products found.
    </div>
@endif