<style>
        .pos-wrapper {
            display: grid;
            grid-template-columns: 90px 1fr 420px;
            height: 92vh;
            overflow: hidden;
        }

        /* Sidebar */

        .pos-sidebar {
            background: #fff;
            border-right: 1px solid #eee;
            padding: 15px 10px;
            overflow-y: auto;
        }

        .category-item {
            width: 100%;
            height: 90px;
            border: 1px solid #eee;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            cursor: pointer;
            transition: .3s;
            background: #fff;
        }

        .category-item:hover {
            border-color: #f39c12;
        }

        .category-item.active {
            border: 2px solid #f39c12;
        }

        .category-item i {
            font-size: 25px;
            margin-bottom: 8px;
        }

        .category-item span {
            font-size: 13px;
        }

        /* Product Area */

        .product-area {
            padding: 20px;
            overflow-y: auto;
        }

        .pos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-action {
            width: 320px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px; 
        }

        .product-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
            height: 280px; 
            overflow: hidden;
        }

        .product-image {
            height: 150px; 
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
        }

        .product-image img {
            max-height: 100%;
            object-fit: contain;
        }

        .product-body {
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-body h6 {
            font-size: 13px;
            margin: 0;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-body h5 {
            font-size: 14px;
            margin: 5px 0 0 0;
            color: #000;
        }
        .currency-symbol {
            font-family: 'Hind Siliguri', sans-serif;
            font-weight: 600;
            margin-right: 2px;
        }

        /* Cart Area */

        .cart-area {
            background: #fff;
            border-left: 1px solid #eee;
            padding: 20px;
            overflow-y: auto;
        }

        .cart-area h3 {
            margin-bottom: 20px;
        }

        #cartBody tr td {
            vertical-align: middle;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .summary-row input {
            width: 130px;
        }

        .qty-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 50%;
            background: #eee;
        }

        .remove-item {
            color: red;
            cursor: pointer;
        }

        .btn-success {
            height: 50px;
            font-size: 18px;
        }

        /* Scroll */

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 20px;
        }

        /* Responsive */

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

        }

        @media (max-width: 1400px) {
            .product-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        /* Desktop */
        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Tablet */
        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Large Mobile */
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        /* Small Mobile */
        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: repeat(1, 1fr);
                gap: 10px;
            }
        }
        .custom-select-style {
            background-color: #f8f9fa;
            border: 2px solid transparent;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-select-style:focus {
            background-color: #fff;
            border-color: #000;
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }

        #addVariantCart:hover {
            transform: translateY(-2px);
            background-color: #333;
        }

        .modal-backdrop.show {
            backdrop-filter: blur(3px);
        }
        .form-select-sm, .form-control-sm {
            font-size: 0.85rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .card {
            border-radius: 16px !important;
        }
        .select2-container {
            width: 100% !important;
        }

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
        }
    </style>