<script>
        $("#searchProduct").on("keyup", function () {
            let value = $(this).val().toLowerCase();
            $(".product-card").filter(function () {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );
            });
        });

        function filterProducts() {
            let search = $("#searchProduct").val().toLowerCase();
            let category = $(".category-item.active").data("id");
            $(".product-card").each(function () {
                let text = $(this).text().toLowerCase();
                let cat = $(this).data("category");
                let okSearch = text.indexOf(search) > -1;
                let okCat = (category == "all" || cat == category);
                $(this).toggle(okSearch && okCat);
            });
        }

        $("#searchProduct").on("keyup", filterProducts);
        $(".category-item").click(function () {
            $(".category-item").removeClass("active");
            $(this).addClass("active");
            filterProducts();
        });


        let cart = [];
        // Global variable for variants
        window.currentVariants = [];

        $(document).on("click", ".product-card", function () {
            let product = {
                id: $(this).data("id"),
                name: $(this).data("name"),
                price: parseFloat($(this).data("price")),
                type: $(this).data("type"),
                stock: parseInt($(this).data("stock")),
                variants: $(this).data("variants"), // data-variants nicchhi
                qty: 1
            };

            if (product.stock <= 0) {
                toastr.error("Out Of Stock");
                return;
            }

            if (product.type === "single") {
                addToCart(product);
                return;
            }

            // Variants theke unique color ebong size ber korar array
            let uniqueColors = [];
            let uniqueSizes = [];
            let colorIds = [];
            let sizeIds = [];

            if (product.variants && product.variants.length > 0) {
                $.each(product.variants, function (i, v) {
                    // Unique Color collect kora
                    if (v.color && v.color.id && !colorIds.includes(v.color.id)) {
                        colorIds.push(v.color.id);
                        uniqueColors.push({ id: v.color.id, name: v.color.name });
                    }
                    // Unique Size collect kora (size table-er 'name' column ekhane map kora hoyeche)
                    if (v.size && v.size.id && !sizeIds.includes(v.size.id)) {
                        sizeIds.push(v.size.id);
                        uniqueSizes.push({ id: v.size.id, name: v.size.name }); 
                    }
                });
            }

            // Dropdown-e Color add kora
            if (uniqueColors.length > 0) {
                $("#modal_color").parent().show();
                let colorHtml = '<option value="">Select Color</option>';
                $.each(uniqueColors, function (i, c) {
                    colorHtml += '<option value="' + c.id + '">' + c.name + '</option>';
                });
                $("#modal_color").html(colorHtml);
            } else {
                $("#modal_color").parent().hide();
                $("#modal_color").val(""); 
            }

            // Dropdown-e Size add kora (Ekhon ar Unknown dekhabe na)
            if (uniqueSizes.length > 0) {
                $("#modal_size").parent().show();
                let sizeHtml = '<option value="">Select Size</option>';
                $.each(uniqueSizes, function (i, s) {
                    sizeHtml += '<option value="' + s.id + '">' + s.name + '</option>';
                });
                $("#modal_size").html(sizeHtml);
            } else {
                $("#modal_size").parent().hide();
                $("#modal_size").val(""); 
            }

            $("#modal_product_id").val(product.id);
            $("#modal_product_name").val(product.name);
            $("#modal_product_price").val(product.price);
            
            window.currentVariants = product.variants;

            $("#variantStock").html(0);
            let variantModal = new bootstrap.Modal(document.getElementById("variantModal"));
            variantModal.show();
        });
        
        // Update Variant Stock Function
        function updateVariantStock() {
            let color = $("#modal_color").val();
            let size = $("#modal_size").val();
            
            let isColorVisible = $("#modal_color").is(":visible");
            let isSizeVisible = $("#modal_size").is(":visible");

            let matchedVariant = window.currentVariants.find(function (item) {
                let matchColor = (!isColorVisible || color === "") ? true : (String(item.color_id) === String(color));
                let matchSize = (!isSizeVisible || size === "") ? true : (String(item.size_id) === String(size));
                
                return matchColor && matchSize;
            });

            $("#variantStock").html(matchedVariant ? matchedVariant.stock : 0);
        }

        $(document).on("change","#modal_color,#modal_size",
            function () {updateVariantStock();}
        );

        $("#addVariantCart").click(function () {
            let colorId = $("#modal_color").val();
            let sizeId = $("#modal_size").val();
            let isColorVisible = $("#modal_color").is(":visible");
            let isSizeVisible = $("#modal_size").is(":visible");

            if (isColorVisible && colorId == "") {
                toastr.error("Please Select Color");
                return;
            }
            if (isSizeVisible && sizeId == "") {
                toastr.error("Please Select Size");
                return;
            }

            let product = {
                id: $("#modal_product_id").val(),
                name: $("#modal_product_name").val(),
                price: parseFloat($("#modal_product_price").val()),
                stock: parseInt($("#variantStock").html()),
                qty: 1,
                color_id: colorId,
                color_name: isColorVisible ? $("#modal_color option:selected").text() : null,
                size_id: sizeId,
                size_name: isSizeVisible ? $("#modal_size option:selected").text() : null
            };

            if (product.stock <= 0) {
                toastr.error("Out Of Stock");
                return;
            }

            addToCart(product);
            bootstrap.Modal.getInstance(document.getElementById("variantModal")).hide();
        });

        function addToCart(product) {
            let existing = cart.findIndex(function (item) {
                let sameColor = (item.color_id || 0) == (product.color_id || 0);
                let sameSize = (item.size_id || 0) == (product.size_id || 0);
                
                return item.id == product.id && sameColor && sameSize;
            });

            if (existing > -1) {
                if (cart[existing].qty + 1 <= cart[existing].stock) {
                    cart[existing].qty++;
                } else {
                    toastr.error("Stock Limit");
                }
            } else {
                if (product.qty <= product.stock) {
                    cart.push(product);
                } else {
                    toastr.error("Stock Limit");
                }
            }
            renderCart();
        }

        function renderCart() {
            let html = "";
            $.each(cart, function (index, item) {
                let total = item.qty * item.price;
                html += `
                <tr>
                    <td>
                        <strong>${item.name}</strong>
                        ${item.color_name ?'<br><small>Color : ' +item.color_name +'</small>': ''}
                        ${item.size_name ?'<br><small>Size : ' + item.size_name +'</small>': ''}
                        <input type="hidden" name="product_id[]" value="${item.id}">
                        <input type="hidden" name="color_id[]" value="${item.color_id || ''}">
                        <input type="hidden" name="size_id[]" value="${item.size_id || ''}">
                        <input type="hidden" name="selling_price[]" value="${item.price}">
                        <input type="hidden" class="row-total" name="total_price[]" value="${total}">
                    </td>
                    <td>
                         <small> Stock : ${item.stock} </small>
                    </td>
                    <td>
                        <div class="d-flex">
                            <button type="button" class="btn btn-danger minus" data-index="${index}"> - </button>
                            <input readonly style="width:50px;text-align:center" class="form-control" type="text" name="quantity[]" value="${item.qty}">
                            <button type="button" class="btn btn-success plus" data-index="${index}"> + </button>
                        </div>
                    </td>
                    <td> ${total.toFixed(2)}  </td>
                    <td> 
                        <button type="button" class="btn btn-danger remove" data-index="${index}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
            });
            $("#cartBody").html(html);
            calculate();
        }

        function calculate() {
            let subtotal = 0;
            $(".row-total").each(function () {
                subtotal += parseFloat($(this).val() || 0);
            });
            // discount
            let discount = parseFloat($("#discount").val() || 0);
            let type = $("#discount_type").val();
            let discountAmount = 0;
            if (type === "percent") {
                discountAmount = (subtotal * discount) / 100;
            } else {
                discountAmount = discount;
            }
            let totalAfterDiscount = subtotal - discountAmount;
            // paid amount
            let paid = parseFloat($("#paid_amount").val() || 0);
            let due = totalAfterDiscount - paid;
            if (due < 0) due = 0;
            // update UI
            $("#grandTotal").text(totalAfterDiscount.toFixed(2));
            $("#dueAmount").text(due.toFixed(2));
            $("#grand_total").val(totalAfterDiscount);
            $("#due_amount").val(due);
        }

        $(document).on("keyup change", "#discount, #discount_type, #paid_amount", function () {
            calculate();
        });

        $(document).on(
            "click",
            ".plus",
            function () {
                let index = $(this).data("index");
                if ( cart[index].qty < cart[index].stock ) {
                    cart[index].qty++;
                } else {
                    toastr.error( "Stock Limit");
                }
                renderCart();
            }
        );

        $(document).on(
            "click",
            ".minus",
            function () {
                 let index = $(this).data("index");
                if (cart[index].qty > 1) {
                    cart[index].qty--;
                }
                renderCart();
            }
        );

        $(document).on( "click",".remove",
            function () {
                let index = $(this).data("index");
                cart.splice(index, 1);
                renderCart();
            }
        );
        $(document).ready(function() {
            $('.customer-select').select2({
                placeholder: "Select Customer",
                allowClear: true
            });
        });

        $("#addCustomerForm").on("submit", function (e) {
            e.preventDefault(); 
            let formData = new FormData(this); 

            $.ajax({
                url: "{{ route('customers.store') }}",
                method: "POST",
                data: formData,
                contentType: false, 
                processData: false, 
                success: function (response) {
                    if (response.success) {
                        $('#addCustomerModal').modal('hide');
                        $("#addCustomerForm")[0].reset();
                        toastr.success("Customer Added Successfully");
                        let newCustomer = response.customer;
                        let newOption = new Option(newCustomer.phone + ' - ' + newCustomer.name, newCustomer.id, true, true);
                        $('.customer-select').append(newOption).trigger('change');
                    }
                },
                error: function (xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error("Something went wrong!");
                    }
                }
            });
        });

        $("#saleForm").on("submit", function (e) {
            e.preventDefault();
            let customerId = $("select[name='customer_id']").val();
            if (!customerId) {
                toastr.error("Please select a customer!");
                $("select[name='customer_id']").focus();
                return false;
            }

            if (cart.length === 0) {
                toastr.error("Cart is empty! Add products first.");
                return false;
            }
            let formData = new FormData(this);

            formData.append('cart', JSON.stringify(cart));

            $.ajax({
                url: "{{ route('sales.store') }}", 
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message || "Sale completed successfully!");
                        cart = [];
                        renderCart();
                        $("#saleForm")[0].reset();
                        $('.customer-select').val(null).trigger('change');
                        window.open(response.invoice_url, '_blank'); 
                    } else {
                        toastr.error(response.message || "Failed to complete sale!");
                    }
                },
                error: function (xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error("Something went wrong with the payment!");
                    }
                }
            });
        });
    </script>