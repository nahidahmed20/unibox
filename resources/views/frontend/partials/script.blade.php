<script src="{{ asset('frontend/assets/js/vendor/jquary-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/bootstrap-bundle.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/imagesloaded-pkgd.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/waypoints.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/venobox.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/odometer.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/meanmenu.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/smooth-scroll.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/jquery.isotope.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/countdown.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/wow.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/vendor/swiper.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
<script src="{{ asset('frontend/assets/js/contact.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
    });
</script>
@endif


<script>
    $(document).ready(function () {
        // Open / Close Category Dropdown
        $('.nice-select').on('click', function (e) {
            e.stopPropagation();
            $(this).toggleClass('open');
        });

        // Category Select
        $('.list .option').on('click', function (e) {
            e.stopPropagation();
            let id = $(this).data('value');
            let name = $(this).text();
            $('#category_id').val(id);
            $('.nice-select .current').text(name);
            $('.list .option').removeClass('selected focus');
            $(this).addClass('selected focus');
            $('.nice-select').removeClass('open');
        });
        // Click Outside
        $(document).on('click', function () {
            $('.nice-select').removeClass('open');
        });
        // AJAX search
        // $('#searchForm').on('submit', function(e){
        //     e.preventDefault();
        //     let search = $('input[name="search"]').val();
        //     let category_id = $('#category_id').val();
        //     $.ajax({
        //         url: "{{ route('search') }}",
        //         type: "GET",
        //         data: {
        //             search: search,
        //             category_id: category_id
        //         },
        //         success: function(response){
        //             $('#searchResult').html(response);
        //         },
        //         error: function(error){
        //             console.log(error);
        //         }
        //     });
        // });

    $('input[name="search"]').on('keyup', function () {
            let search = $(this).val();
            let currentSuggestionBox = $(this).closest('.category-form-wrap').find('#searchSuggestion, .searchSuggestion');
            if (search.length < 1) {
                currentSuggestionBox.html('').hide();
                return;
            }
            $.ajax({
                url: "{{ route('search.suggestion') }}",
                type: "GET",
                data: {
                    search: search
                },
                success: function(res) {
                    currentSuggestionBox.html(res).show();
                }
            });
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.category-form-wrap').length) {
                $('#searchSuggestion, .searchSuggestion').hide();
            }
        });
    });
</script>
<script>

    $('.update-btn').on('click', function(e){
        e.preventDefault();
        let btn = $(this);
        let data = {};
        $('.qty-input').each(function(){
            let id = $(this).data('id');
            let qty = $(this).val();
            data[id] = qty;
        });
        btn.prop('disabled', true);
        $.ajax({
            url: "{{ route('cart.update') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                quantities: data
            },
            success: function(res){
                $('#cart-table-wrapper').html(res.table);
                if (res.cart_count !== undefined) {
                    $('.cart-item-count-render').not('.cart-badge').text(res.cart_count + ' items');
                    $('.cart-badge').text(res.cart_count);
                }
                $('#cart-section').html(res.html);
                $('.subtotal-value').text(
                    '৳' + parseFloat(res.subtotal).toFixed(2)
                );
                $('.shipping-value').text(
                    '৳' + parseFloat(res.shipping).toFixed(2)
                );
                $('.total-value').text(
                    '৳' + parseFloat(res.cart_total).toFixed(2)
                );
                Swal.fire({
                    icon: 'success',
                    title: 'আপডেট সম্পন্ন',
                    text: 'কার্ট সফলভাবে আপডেট হয়েছে',
                    timer: 1200,
                    showConfirmButton: false
                });
            },

            error: function(xhr){
                let message = 'কিছু সমস্যা হয়েছে';
                if(xhr.responseJSON && xhr.responseJSON.message){
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'স্টক সীমা অতিক্রম করেছে',
                    text: message
                });
            },

            complete: function(){
                btn.prop('disabled', false);
            }
        });
    });

     $(document).on('click', '.remove-item', function () {
        let id = $(this).data('id');
        $.ajax({
            url: '/cart/remove/' + id,
            type: 'GET',
            success: function (res) {
                $('#cart-section').html(res.html);
                $('#cart-table-wrapper').html(res.table);
                Swal.fire({
                    icon: 'success',
                    title: 'Removed!',
                    text: 'Item has been removed from cart',
                    timer: 1500,
                    showConfirmButton: false
                });
                if (res.cart_count !== undefined) {
                    $('.cart-item-count-render').not('.cart-badge').text(res.cart_count);
                    $('.cart-badge').text(res.cart_count);
                }
                $('#cart-total').text('৳' + res.cart_total);
                $('.subtotal-value').text('৳' + parseFloat(res.subtotal).toFixed(2));
                $('.shipping-value').text('৳' + parseFloat(res.shipping).toFixed(2));
                $('.total-value').text('৳' + parseFloat(res.cart_total).toFixed(2));
            }

        });
    });


    function goToCheckout(){
        window.location.href = "/cart/checkout";
    }

    $(document).ajaxComplete(function() {
        let updatedCount = $('#cart-count').text(); 
        
        $('.cart-item-count-render').text(updatedCount);
    })
</script>


@stack('javascript')

