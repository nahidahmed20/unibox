<link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/venobox.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/odometer.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>

    /* =========================
    HEADER
    ========================= */
    .cart-header{
        padding:18px 20px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#008a7a;
        color:#fff;
        border-bottom:1px solid rgba(255,255,255,0.08);
    }

    .cart-header h4{
        margin:0;
        font-size:18px;
        font-weight:700;
        letter-spacing:.3px;
        display:flex;
        align-items:center;
        gap:8px;
        color:#fff;
    }

    #cart-close {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background: #fff; 
        color: #333;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0; 
    }

    #cart-close:hover {
        background: #f5f5f5;
        color: #008a7a; 
        transform: scale(1.1);
        border-color: #008a7a;
    }


    .cart-body{
        flex: 1;
        overflow-y: auto;
        padding: 0px 4px;
    }

    .cart-body::-webkit-scrollbar{
        width:5px;
    }
    .cart-body::-webkit-scrollbar-thumb{
        background:#ccc;
        border-radius:10px;
    }

    .cart-item{
        display:flex;
        align-items:center;
        gap:12px;
        padding:10px;
        border-bottom:1px solid #f1f1f1;
        transition:0.2s ease;
    }

    .cart-item:hover{
        background:#f9f9f9;
        border-radius:8px;
    }

    /* IMAGE */
    .cart-item img{
        /* width:70px; */
        height:70px;
        object-fit:cover;
        border-radius:8px;
        border:1px solid #eee;
    }
    .cart-info{
        flex:1;
        min-width:0;
        display:flex;
        flex-direction:column;
        justify-content:center;
    }

    /* TITLE */
    .cart-info h5{
        font-size:14px;
        font-weight:600;
        margin:0 0 0px;
        color:#222;
        line-height:1.3;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* TEXT */
    .cart-info p{
        margin:0 0 0px;
        font-size:13px;
        color:#777;
    }

    /* PRICE */
    .cart-info .price{
        font-weight:600;
        color:#000;
    }

    /* REMOVE BUTTON */
    .remove-item{
        margin-left:auto;
        font-size:16px;
        color:#999;
        cursor:pointer;
        padding:5px;
        transition:0.2s;
    }

    .remove-item:hover{
        color:008a7a;
        transform:scale(1.2);
    }

    /* =========================
    FOOTER
    ========================= */
    .cart-footer{
        padding:15px;
        border-top:1px solid #eee;
        background:#fff;
        position:sticky;
        bottom:0;
    }

    .checkout-button{
        width:100%;
        padding:12px;
        background:#008a7a;
        color:#fff;
        border:none;
        cursor:pointer;
        border-radius:6px;
        font-size:16px;
        font-weight:600;
        transition:0.2s;
        border-radius: 3px;
    }

    .checkout-button:hover{
        background:#59b8ad;
    }

    /* =========================
    EMPTY CART
    ========================= */
    .cart-empty{
        text-align:center;
        padding:40px 10px;
        color:#777;
    }

    .cart-empty i{
        font-size:40px;
        margin-bottom:10px;
    }

    /* =========================
    CART COUNT TEXT
    ========================= */
    #cart-count{
        font-size: 16px;
        margin-left: -16px;
        color: #008a7a;
        margin-bottom: 14px;
    }
    .cart-summary{
    padding:15px 10px;
    border-top:1px solid #eee;
    background:#fafafa;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    font-size:14px;
    margin-bottom:8px;
    color:#555;
}

.summary-row.total{
    font-weight:600;
    font-size:15px;
    color:#000;
}
.cart-info .qty{
    font-size:12px;
    color:#888;
}

.cart-info .item-total{
    font-size:12px;
    color:#444;
}
.cart-top{
    padding:5px 8px 0px;
    font-size:13px;
    color:#555;
}
.cart-img{
    flex-shrink:0;
}
</style>
@stack('css')
