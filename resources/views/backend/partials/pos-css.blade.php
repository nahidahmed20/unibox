<style>
    /* ================= General Setup ================= */
    .currency-symbol { font-weight: 600; color: #1e293b; }
    
    .pos-wrapper {
        display: grid;
        grid-template-columns: 90px 1fr 420px; /* Cart width 420px */
        height: 92vh;
        overflow: hidden;
        background-color: #f4f6f9; 
    }

    /* ================= Sidebar ================= */
    .pos-sidebar {
        background: #fff;
        border-right: 1px solid #eee;
        padding: 15px 10px;
        overflow-y: auto;
    }

    .category-item {
        width: 100%;
        height: 85px;
        border: 1px solid #eee;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
    }

    .category-item:hover {
        border-color: #f39c12;
        box-shadow: 0 4px 10px rgba(243, 156, 18, 0.1);
    }

    .category-item.active {
        border: 2px solid #f39c12;
        background-color: #fffaf0;
    }

    .category-item i {
        font-size: 22px;
        margin-bottom: 6px;
        color: #555;
    }
    
    .category-item.active i {
        color: #f39c12;
    }

    .category-item span {
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        line-height: 1.2;
    }

    /* ================= Product Area ================= */
    .product-area {
        padding: 20px;
        overflow-y: auto;
    }

    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background: #fff;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .header-action {
        width: 350px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 18px; 
    }

    .product-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eaeaea;
        display: flex;
        flex-direction: column;
        height: auto; 
        min-height: 270px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #ddd;
    }

    .product-image {
        height: 140px; 
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: 1px solid #f5f5f5;
    }

    .product-image img {
        max-height: 100%;
        object-fit: contain;
    }

    .product-body {
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }

    .product-body small {
        font-size: 11px;
    }

    .product-body h5.text-truncate {
        font-size: 14px;
        margin: 6px 0;
        font-weight: 600;
        color: #2c3e50;
    }

    .currency-symbol {
        font-family: 'Hind Siliguri', sans-serif;
        font-weight: 700;
        color: #e74c3c;
        font-size: 16px;
    }

    /* ================= Cart Area & Table Fixes ================= */
    .cart-area {
        background: #fff;
        border-left: 1px solid #eee;
        padding: 15px;
        overflow-y: auto;
        box-shadow: -5px 0 15px rgba(0,0,0,0.02);
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px dashed #eee;
    }

    .cart-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .cart-table table {
        font-size: 12px; 
    }
    
    .cart-table th {
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
    }

    #cartBody td {
        vertical-align: middle;
        padding: 8px 4px;
    }

    #cartBody .d-flex {
        flex-wrap: nowrap !important; 
        justify-content: center;
        align-items: center;
    }

    #cartBody .btn-danger.minus, #cartBody .btn-success.plus {
        padding: 2px 8px;
        height: 28px;
        font-size: 14px;
        line-height: 1;
    }

    #cartBody input[name="quantity[]"] {
        width: 35px !important;
        height: 28px;
        padding: 2px;
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        border: 1px solid #ddd;
        margin: 0 3px;
    }

    #cartBody .btn-danger.remove {
        padding: 4px 8px;
        font-size: 12px;
    }

    /* ================= Summary Section ================= */
    .cart-summary {
        font-size: 14px;
    }

    .form-select-sm, .form-control-sm {
        font-size: 13px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 5px 10px;
    }

    .payment-btn {
        height: 48px;
        font-size: 16px !important;
        border-radius: 10px;
        letter-spacing: 0.5px;
    }

    /* ================= Scrollbar ================= */
    ::-webkit-scrollbar {
        width: 5px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8; 
    }

    /* ================= Select2 Customization ================= */
    .select2-container { width: 100% !important; }
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 4px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 10px;
    }
    .select2-selection__rendered {
        line-height: 28px !important;
        color: #495057 !important;
        font-size: 14px;
    }

    /* ================= Responsive Breakpoints ================= */
    
    @media (min-width: 1600px) {
        .product-grid { grid-template-columns: repeat(5, 1fr); }
    }

    @media(max-width:1200px) {
        .pos-wrapper {
            grid-template-columns: 80px 1fr;
        }
        .cart-area {
            position: fixed;
            top: 0;
            right: -420px;
            width: 420px;
            height: 100vh;
            z-index: 999;
            transition: .4s;
        }
        .cart-area.active {
            right: 0;
        }
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .product-grid {
            grid-template-columns: repeat(1, 1fr);
            gap: 12px;
        }
    }

    /* ================= Variant Modal Custom Styles ================= */
    .tracking-wide {
        letter-spacing: 0.5px;
    }

    .custom-select-style {
        height: 48px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02) !important;
        transition: all 0.3s ease;
    }

    .custom-select-style:focus {
        border-color: #f39c12;
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(243, 156, 18, 0.15) !important;
    }

    .variant-btn {
        font-size: 16px;
        letter-spacing: 0.5px;
        background: #1e293b;
        border: none;
        transition: all 0.3s ease;
    }

    .variant-btn:hover {
        background: #0f172a; 
        transform: translateY(-2px); 
        box-shadow: 0 6px 15px rgba(0,0,0,0.15) !important;
    }
</style>