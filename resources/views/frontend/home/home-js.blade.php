<script>
    // --- Carousel Swipers ---
    $(document).ready(function () {
        if ($('.category-carousel').length) {
            new Swiper(".category-carousel", {
                slidesPerView: 4, 
                spaceBetween: 10, 
                loop: true,
                speed: 700,
                navigation: {
                    nextEl: ".category-section .swiper-prev",
                    prevEl: ".category-section .swiper-next",
                },
                breakpoints: {
                    320: { slidesPerView: 4, spaceBetween: 10 },
                    576: { slidesPerView: 4, spaceBetween: 15 },
                    768: { slidesPerView: 5, spaceBetween: 20 },
                    992: { slidesPerView: 6, spaceBetween: 20 },
                    1200: { slidesPerView: 6, spaceBetween: 20 }
                }
            });
        }

        if ($('.service-carousel').length) {
            new Swiper(".service-carousel", {
                loop: true,
                speed: 700,
                autoplay: { delay: 3000, disableOnInteraction: false },
                pagination: { el: ".service-carousel .swiper-pagination", clickable: true },
                breakpoints: {
                    320: { slidesPerView: 1, spaceBetween: 15 },
                    576: { slidesPerView: 2, spaceBetween: 20 },
                    768: { slidesPerView: 3, spaceBetween: 20 },
                    992: { slidesPerView: 4, spaceBetween: 24 },
                    1200: { slidesPerView: 4, spaceBetween: 24 }
                }
            });
        }

        if ($('.material-carousel').length) {
            new Swiper(".material-carousel", {
                loop: true,
                speed: 800,
                autoplay: { delay: 3500, disableOnInteraction: false },
                pagination: { el: ".material-carousel .swiper-pagination", clickable: true },
                spaceBetween: 24, 
                breakpoints: {
                    320: { slidesPerView: 1 },
                    768: { slidesPerView: 1 },
                    992: { slidesPerView: 2 },
                    1200: { slidesPerView: 2 }
                }
            });
        }

        if ($('.client-carousel').length) {
            new Swiper(".client-carousel", {
                slidesPerView: 2, 
                spaceBetween: 15, 
                loop: true,
                speed: 700,
                autoplay: { delay: 3000, disableOnInteraction: false },
                breakpoints: {
                    320: { slidesPerView: 2, spaceBetween: 15 },
                    576: { slidesPerView: 3, spaceBetween: 20 },
                    768: { slidesPerView: 4, spaceBetween: 20 },
                    992: { slidesPerView: 5, spaceBetween: 20 },
                    1200: { slidesPerView: 6, spaceBetween: 24 }
                }
            });
        }
    });

    // --- Isotope Filter + Load More Combined Fix ---
    $(document).ready(function () {
        var isMobile = $(window).width() <= 767;
        var initialItems = isMobile ? 10 : 15; 
        var loadItems = isMobile ? 4 : 5;      

        var currentFilter = '*';
        var currentLimit = initialItems;

        // Isotope Init
        var $grid = $('.filter-items').isotope({
            itemSelector: '.single-item',
            layoutMode: 'fitRows'
        });

        function updateFilterAndPagination() {
            var $allItems = $('.filter-items .product-box');
            
            var $matchingItems = (currentFilter === '*') 
                ? $allItems 
                : $allItems.filter(currentFilter);

            $allItems.hide();
            $matchingItems.slice(0, currentLimit).show();

            $grid.isotope('layout');

            if ($matchingItems.length > currentLimit) {
                $('#loadMoreWrapper').show();
            } else {
                $('#loadMoreWrapper').hide();
            }
        }

        updateFilterAndPagination();

        $('#loadMore').on('click', function (e) {
            e.preventDefault();
            currentLimit += loadItems;
            updateFilterAndPagination();
        });

        $('.project-filter li').on('click', function () {
            $('.project-filter li').removeClass('active');
            $(this).addClass('active');

            currentFilter = $(this).attr('data-filter');
            currentLimit = initialItems; 

            updateFilterAndPagination();
        });
    });

    // --- Add to Cart Modal JavaScript ---
    $(document).ready(function() {
        let modalSizesCount = 0;
        let modalColorsCount = 0;
        let modalTotalStock = 0;
        let productStocks = [];
        let basePrice = 0; 

        // Open Product Modal
        $(document).on('click', '.openCartModal', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            basePrice = $(this).data('price'); 
            let image = $(this).data('image');

            // Reset Modal
            $('#modalProductName').text(name);
            $('#modalProductPrice').text(parseFloat(basePrice).toFixed(2));
            $('#modalProductImage').attr('src', image);
            $('#modalQty').val(1);
            $('#modalSizeWrap').html('');
            $('#modalColorWrap').html('');
            $('#sizeSection').hide();
            $('#colorSection').hide();

            $('#finalAddToCart')
                .data('id', id)
                .prop('disabled', false)
                .html('Add To Cart');

            $.ajax({
                url: '/product/modal-data/' + id,
                type: 'GET',
                beforeSend: function() {
                    $('.openCartModal').prop('disabled', true);
                },
                success: function(res) {
                    modalTotalStock = res.total_stock || 0;
                    productStocks = res.variants || res.stocks || []; 

                    if (res.sizes && res.sizes.length > 0) {
                        let sizeHtml = '';
                        res.sizes.forEach(size => {
                            let stock = size.stock ?? 0;
                            let sizeName = size.name || size.size || 'N/A'; 
                            if (sizeName.trim() !== '') {
                                sizeHtml += `
                                    <div class="size-box ${stock <= 0 ? 'disabled' : ''}"
                                        data-id="${size.id}"
                                        data-stock="${stock}">
                                        ${sizeName}
                                    </div>
                                `;
                            }
                        });
                        if (sizeHtml) {
                            $('#modalSizeWrap').html(sizeHtml);
                            $('#sizeSection').show();
                        }
                    }
                    
                    if (res.colors && res.colors.length > 0) {
                        let colorHtml = '';
                        res.colors.forEach(color => {
                            let code = color.code || '#ccc';
                            colorHtml += `
                            <div class="color-box-modal ${color.stock <= 0 ? 'stock-out' : ''}"
                                style="background:${code}"
                                data-id="${color.id}"
                                data-stock="${color.stock ?? 0}"
                                title="${color.name || ''}">
                            </div>`;
                        });
                        if (colorHtml) {
                            $('#modalColorWrap').html(colorHtml);
                            $('#colorSection').show();
                        }
                    }

                    if (modalTotalStock <= 0) {
                        $('#finalAddToCart').prop('disabled', true).html('Stock Out');
                    }

                    const modal = new bootstrap.Modal(document.getElementById('cartModal'));
                    modal.show();
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load product data.' });
                },
                complete: function() {
                    $('.openCartModal').prop('disabled', false);
                }
            });
        });

        function updateModalPriceAndStock() {
            let sizeId = $('#cartModal .size-box.active').data('id') || null;
            let colorId = $('#cartModal .color-box-modal.active').data('id') || null;

            if (sizeId || colorId) {
                let variant = productStocks.find(v => 
                    (v.size_id == sizeId || (!sizeId)) && 
                    (v.color_id == colorId || (!colorId))
                );

                if (variant) {
                    if (variant.stock <= 0) {
                        $('#finalAddToCart').prop('disabled', true).html('Stock Out');
                    } else {
                        $('#finalAddToCart').prop('disabled', false).html('Add To Cart');
                    }

                    if (variant.selling_price && variant.selling_price > 0) {
                        $('#modalProductPrice').text(parseFloat(variant.selling_price).toFixed(2));
                    } else {
                        $('#modalProductPrice').text(parseFloat(basePrice).toFixed(2));
                    }
                    return variant.stock;
                }
            }
            
            $('#modalProductPrice').text(parseFloat(basePrice).toFixed(2));
            return modalTotalStock;
        }

        $(document).on('click', '#cartModal .size-box:not(.disabled)', function() {
            $('#cartModal .size-box').removeClass('active');
            $(this).addClass('active');
            updateModalPriceAndStock();
        });

        $(document).on('click', '#cartModal .color-box-modal:not(.stock-out)', function() {
            $('#cartModal .color-box-modal').removeClass('active');
            $(this).addClass('active');
            updateModalPriceAndStock();
        });

        function validateModalStock() {
            let hasSelectableSizes = $('#modalSizeWrap .size-box:not(.disabled)').length > 0;
            if (hasSelectableSizes && $('#cartModal .size-box.active').length === 0) {
                Swal.fire({ icon: 'warning', title: 'Select Size', text: 'Please select a size' });
                return false;
            }

            let hasSelectableColors = $('#modalColorWrap .color-box-modal:not(.stock-out)').length > 0;
            if (hasSelectableColors && $('#cartModal .color-box-modal.active').length === 0) {
                Swal.fire({ icon: 'warning', title: 'Select Color', text: 'Please select a color' });
                return false;
            }

            let qty = parseInt($('#modalQty').val()) || 1;
            let stock = updateModalPriceAndStock(); 
            
            if (qty > stock) {
                Swal.fire({ icon: 'error', title: 'স্টক সীমা অতিক্রম করেছে', text: 'আপনার চাহিদাকৃত পরিমাণ স্টকে নেই' });
                return false;
            }
            return true;
        }

        $('#finalAddToCart').on('click', function() {
            let btn = $(this);
            if (btn.prop('disabled')) return;
            if (!validateModalStock()) return;

            let product_id = btn.data('id');
            let size_id = $('#cartModal .size-box.active').data('id') || null;
            let color_id = $('#cartModal .color-box-modal.active').data('id') || null;
            let qty = parseInt($('#modalQty').val()) || 1;

            btn.prop('disabled', true).html('Adding...');

            $.ajax({
                url: '/cart/add',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    qty: qty,
                    size_id: size_id,
                    color_id: color_id
                },
                success: function(res) {
                    if (res.success) {
                        if (res.html) $('#cart-section').html(res.html);
                        
                        if (res.cart_count !== undefined) {
                            $('.cart-item-count-render').not('.cart-badge').text(res.cart_count);
                            $('.cart-badge').text(res.cart_count);
                            $('#cart-count').text(res.cart_count);
                        }

                        if (res.cart_total) $('.total-value').text('৳' + parseFloat(res.cart_total).toFixed(2));
                        if (res.shipping) $('.shipping-value').text('৳' + res.shipping);

                        $('#cart-overlay, #cart-drawer').addClass('active');
                        bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
                        
                        Swal.fire({ icon: 'success', title: 'Added To Cart', timer: 1200, showConfirmButton: false });
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong' });
                },
                complete: function() {
                    btn.prop('disabled', false).html('Add To Cart');
                }
            });
        });

        $(document).on('click', '#qtyPlus', function() {
            let currentQty = parseInt($('#modalQty').val()) || 1;
            let maxStock = updateModalPriceAndStock(); 

            if (currentQty < maxStock) {
                $('#modalQty').val(currentQty + 1);
            } else {
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'স্টক লিমিট', 
                    text: 'এর চেয়ে বেশি পরিমাণ স্টকে নেই!', 
                    timer: 1500, 
                    showConfirmButton: false 
                });
            }
        });

        $(document).on('click', '#qtyMinus', function() {
            let currentQty = parseInt($('#modalQty').val()) || 1;
            if (currentQty > 1) {
                $('#modalQty').val(currentQty - 1);
            }
        });
    });
</script>