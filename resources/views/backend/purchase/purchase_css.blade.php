<style>
        #searchResults {
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.03) !important;
            overflow: hidden;
            margin-top: 8px;
            background: #ffffff;
        }
        
        .search-item {
            padding: 14px 20px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .search-item:last-child {
            border-bottom: none !important;
        }
        
        .search-item:hover {
            background-color: #f8fafc !important;
            transform: translateX(6px); 
        }

        .product-name-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }

        .product-sku-text {
            font-size: 0.8rem;
            color: #64748b;
        }

        .modal-content {
            border: 1px solid rgba(0, 0, 0, 0.03) !important;
            border-radius: 24px !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08) !important;
            background: #ffffff;
        }

        .modal-header {
            border-bottom: none !important;
            padding: 24px 32px !important;
            background: #0f172a !important; 
            position: relative;
        }

        .modal-header-icon-box {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff !important;
        }

        .modal-header .btn-close {
            background-color: rgba(255, 255, 255, 0.1) !important;
            filter: invert(1) grayscale(1) brightness(2); 
            padding: 10px !important;
            border-radius: 50% !important;
            opacity: 0.8;
            transition: all 0.2s ease;
        }

        .modal-header .btn-close:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            transform: rotate(90deg) scale(1.05);
            opacity: 1;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 20px 32px !important;
            background: #f8fafc;
            border-bottom-left-radius: 24px !important;
            border-bottom-right-radius: 24px !important;
        }

        .table {
            border-color: #f1f5f9 !important;
        }

        .table thead th {
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            letter-spacing: 0.8px;
            background-color: #f8fafc !important;
            color: #64748b !important;
            padding: 14px 20px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .table tbody td {
            padding: 16px 20px !important;
            color: #334155;
            font-size: 0.9rem;
        }

        #variantMatrixTable tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        
        #variantMatrixTable tbody tr:hover {
            background-color: #f8fafc !important;
        }

        #variantMatrixTable tbody tr.row-active {
            background-color: rgba(15, 23, 42, 0.02) !important;
        }
        
        #variantMatrixTable tbody tr.row-active td {
            color: #0f172a !important;
        }

        .variant-title-text {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 600;
        }

        .modal-price, .modal-qty, .price, .qty, .premium-modal-input {
            border-radius: 10px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 8px 14px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            font-size: 0.9rem !important;
            text-align: center;
            background-color: #ffffff;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .modal-price:focus, .modal-qty:focus, .price:focus, .qty:focus, .premium-modal-input:focus {
            border-color: #0f172a !important; 
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08) !important;
            background-color: #ffffff !important;
            outline: none;
        }

        .premium-modal-input.has-value {
            border-color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.02) !important;
            color: #3b82f6 !important;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .badge {
            font-size: 0.78rem !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            letter-spacing: 0.3px;
        }
        
        .bg-light {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
        }

        .btn-sm {
            border-radius: 10px !important;
            padding: 8px 12px !important;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-danger:hover {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        #btnModalAddProducts:hover {
            background: #1e293b !important;
            border-color: #1e293b !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15) !important;
        }
        
        #btnModalAddProducts:active {
            transform: translateY(0px);
        }

        .btn-close {
            transition: all 0.2s;
        }
        .btn-close:hover {
            transform: rotate(90deg);
        }

        .premium-summary-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.03), 0 8px 10px -6px rgba(15, 23, 42, 0.03) !important;
            position: sticky;
            top: 20px; 
        }

        .summary-icon-box {
            width: 38px;
            height: 38px;
            background-color: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-input-label {
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            color: #475569 !important;
            margin-bottom: 6px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-dashboard-input {
            border-radius: 10px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
            color: #334155 !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s ease-in-out !important;
        }

        .modern-dashboard-input:focus {
            border-color: #0f172a !important;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08) !important;
            outline: none;
        }

        .btn-modern-action {
            background: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid #0f172a !important;
            border-radius: 10px !important;
            padding: 0 16px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-modern-action:hover {
            background: #1e293b !important;
            border-color: #1e293b !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important;
        }

        .btn-modern-action:active {
            transform: translateY(1px);
        }

        .dashed-separator {
            border-top: 1.5px dashed #e2e8f0;
            height: 0;
            width: 100%;
        }

        .total-display-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 16px 20px;
        }
    </style>