@extends('frontend.layouts.app')
@section('title','All Products')

@section('contant')
@push('css')
    <style>
      .shop-sidebar input[type="checkbox"] {
          width: 18px;
          height: 18px;
          accent-color: #222;
          transform: scale(1.1);
      }
      .shop-sidebar label {
          display: flex;
          align-items: center;
          gap: 8px;
          cursor: pointer;
      }
    </style>
@endpush
<main>
  <section class="shop-main container d-flex" style="padding:108px 18.17px">

  <div class="shop-sidebar side-sticky bg-body" id="shopFilter">

    {{-- CATEGORY --}}
    <div class="accordion" id="categories-list">
      <div class="accordion-item mb-4 pb-3">
        <h5 class="accordion-header">
          <button class="accordion-button p-0 border-0 fs-5 text-uppercase"
            data-bs-toggle="collapse"
            data-bs-target="#categoryFilter">
            Product Categories
          </button>
        </h5>

        <div id="categoryFilter" class="accordion-collapse collapse show">
          <div class="accordion-body px-0 pt-3">
            <ul class="list list-inline mb-0">
              @foreach($categories as $category)
              <li class="list-item">
                <label class="menu-link py-1">
                  <input type="checkbox"
                        class="filter-category me-1"
                        value="{{ $category->id }}">
                  {{ $category->name }}
                </label>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    {{-- BRAND --}}
    <div class="accordion" id="brand-filters">
      <div class="accordion-item mb-4 pb-3">
        <h5 class="accordion-header">
          <button class="accordion-button p-0 border-0 fs-5 text-uppercase"
            data-bs-toggle="collapse"
            data-bs-target="#brandFilter">
            Brands
          </button>
        </h5>

        <div id="brandFilter" class="accordion-collapse collapse show">
          <div class="accordion-body px-0">

            <input type="text"
                  id="brandSearch"
                  class="form-control form-control-sm mb-3"
                  placeholder="Search Brand">

            <ul class="list-unstyled" id="brandList">
              @foreach($brands as $brand)
              <li>
                <label class="d-flex align-items-center">
                  <input type="checkbox"
                        class="filter-brand me-2"
                        value="{{ $brand->id }}">
                  {{ $brand->name }}
                </label>
              </li>
              @endforeach
            </ul>

          </div>
        </div>
      </div>
    </div>

    {{-- PRICE --}}
    <div class="accordion" id="price-filters">
      <div class="accordion-item mb-4">
        <h5 class="accordion-header">
          <button class="accordion-button p-0 border-0 fs-5 text-uppercase"
            data-bs-toggle="collapse"
            data-bs-target="#priceFilter">
            Price
          </button>
        </h5>

        <div id="priceFilter" class="accordion-collapse collapse show">
          <div class="accordion-body px-0">
            <input type="number" id="minPrice" class="form-control mb-2" placeholder="Min Price">
            <input type="number" id="maxPrice" class="form-control" placeholder="Max Price">
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="shop-list flex-grow-1">
    <div id="products-wrapper">
      @include('frontend.home.partials.product_list', ['products' => $products])
    </div>

  </div>

  </section>
</main>
@endsection

@section('javascript')
<script>

    $(document).on('click', '.js-quick-view', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id');

        // ---- Reset modal (important) ----
        $('.product-single__name').text('');
        $('.current-price').text('');
        $('.product-single__short-desc p').text('');
        $('.product-single__meta-info span').text('N/A');
        $('.product-single__media .swiper-wrapper').html('');
        $('.text-swatches .swatch-list').empty();
        $('.color-swatches .swatch-list').empty();
        $('.text-swatches, .color-swatches').hide();

        // Destroy swiper if exists
        

        $.ajax({
            url: `/product/quick-view/${productId}`,
            type: 'GET',
            beforeSend: function () {
            },
            success: function (res) {
                const product = res.product;
                $('#qv_product_id').val(product.id);
                $('.product-single__name').text(product.name);
                $('.current-price').text('৳ ' + parseFloat(product.selling_price).toFixed(2));
                $('.product-single__short-desc p').text(product.short_description ?? '');

                $('.product-single__meta-info .meta-item:nth-child(1) span')
                    .text(product.sku ?? 'N/A');
                $('.product-single__meta-info .meta-item:nth-child(2) span')
                    .text(res.category?.name ?? 'N/A');
                let imagesHtml = '';

                if (product.image) {
                    imagesHtml += `
                        <div class="swiper-slide product-single__image-item">
                            <img src="${product.image}" class="img-fluid" alt="${product.name}">
                        </div>`;
                }

                if (res.images && res.images.length) {
                    res.images.forEach(img => {
                        imagesHtml += `
                            <div class="swiper-slide product-single__image-item">
                                <img src="${img}" class="img-fluid" alt="${product.name}">
                            </div>`;
                    });
                }

                if (!imagesHtml) {
                    imagesHtml = `
                        <div class="swiper-slide product-single__image-item">
                            <img src="/assets/images/no-image.png" class="img-fluid">
                        </div>`;
                }

                $('.product-single__media .swiper-wrapper').html(imagesHtml);
                qvSwiper = new Swiper('.js-swiper-slider', {
                    slidesPerView: 1,
                    loop: false,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    }
                });
                // ---------- SIZES ----------
                $('.text-swatches .swatch-list').empty();

                if (res.sizes && res.sizes.length) {
                    res.sizes.forEach((size, i) => {
                        $('.text-swatches .swatch-list').append(`
                            <input type="radio"
                                  name="size_id"
                                  id="qv-size-${size.id}"
                                  value="${size.id}"
                                  ${i === 0 ? 'checked' : ''}>

                            <label for="qv-size-${size.id}" class="swatch">
                                ${size.size}
                            </label>
                        `);
                    });

                    $('.text-swatches').show();
                } else {
                    $('.text-swatches').hide();
                }


                function getColorCode(name) {
                  const map = {
                      black:'#000', white:'#fff', red:'#f00',
                      green:'#008000', blue:'#00f',
                      yellow:'#ff0', gray:'#808080', grey:'#808080',
                      pink:'#ffc0cb', orange:'#ffa500',
                      purple:'#800080', brown:'#a52a2a'
                  };
                  return map[name?.toLowerCase()] || '#000';
              }

              // ---------- COLORS ----------
              $('.color-swatches .swatch-list').empty();

              if (res.colors && res.colors.length) {
                  res.colors.forEach((color, i) => {
                      const code = getColorCode(color.name);

                      $('.color-swatches .swatch-list').append(`
                          <input type="radio" name="color_id" id="qv-color-${color.id}" value="${color.id}" ${i === 0 ? 'checked' : ''}>
                          <label for="qv-color-${color.id}" class="swatch swatch-color"  title="${color.name}"> </label>
                      `);
                  });

                  $('.color-swatches').show();
              } else {
                  $('.color-swatches').hide();
              }


                $('#quickView').modal('show');
            },
            error: function () {
                toastr.error('Failed to load product');
            }
        });
    });

    // ---------------- ADD TO CART ----------------
    $(document).on('submit', 'form[name="addtocart-form"]', function (e) {
      e.preventDefault();

      const form = $(this);

      let colorInput = form.find('input[name="color_id"]:checked');
      let sizeInput  = form.find('input[name="size_id"]:checked');

      let data = {
          _token: $('meta[name="csrf-token"]').attr('content'),
          product_id: form.find('#qv_product_id').val(),
          qty: form.find('input[name="quantity"]').val()
      };

      if (colorInput.length) {
          data.color_id = colorInput.val();
      }

      if (sizeInput.length) {
          data.size_id = sizeInput.val();
      }

      $.ajax({
          url: '/cart/add',
          type: 'POST',
          data: data,
          success: function(res) {
              if (res.success) {
                  $('.js-cart-items-count').text(res.cartCount);
                  $('#quickView').modal('hide');
                  toastr.success(res.message);
                  $('#cartDrawerBody').load(location.href + ' #cartDrawerBody > *');

              } else {
                  toastr.error(res.message);
              }
          },
          error: function() {
              toastr.error('Something went wrong');
          }
      });
  });

  function fetchProducts() {
      let categories = [];
      let brands = [];

      $('.filter-category:checked').each(function(){
          categories.push($(this).val());
      });

      $('.filter-brand:checked').each(function(){
          brands.push($(this).val());
      });

      $.ajax({
          url: "{{ route('featured.products.all') }}",
          data: {
              category: categories,
              brand: brands,
              min_price: $('#minPrice').val(),
              max_price: $('#maxPrice').val(),
              query: $('#searchProduct').val(),
          },
          success: function(res) {
              $('#products-wrapper').html(res.html);
          }
      });
  }

  // EVENTS
  $(document).on('change', '.filter-category, .filter-brand', fetchProducts);
  $(document).on('keyup', '#searchProduct', fetchProducts);
  $(document).on('change', '#minPrice, #maxPrice', fetchProducts);

  // BRAND SEARCH
  $('#brandSearch').on('keyup', function () {
      let value = $(this).val().toLowerCase();
      $('#brandList li').filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
  });
</script>
@endsection






