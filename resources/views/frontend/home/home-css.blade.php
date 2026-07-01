<style>
    .carousel-item {
        height: 400px;
    }

    .category-img {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .category-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .hero-list-wrap {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .hero-list-wrap::-webkit-scrollbar {
        width: 6px;
    }

    .hero-list-wrap::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    @media (max-width: 767px) {
        .carousel-item {
            height: 140px;
        }
    }


    .modal-product-image {
        max-height: 350px;
        object-fit: contain;
    }

    /* =========================
            PRODUCT CARD DESIGN
            ========================= */

    .product-box {
        display: flex;
    }

   .product-item.product-item-2 {
        width: 100%;
        background: #fff;
        border: 1px solid rgba(0, 138, 122, 0.3); 
        border-radius: 12px;
        overflow: hidden;
        transition: all .3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .product-item.product-item-2:hover {
        transform: translateY(0px);
        box-shadow: 0 10px 30px rgba(0, 138, 122, 0.15); 
        border-color: #008a7a; 
    }


    .product-thumb {
        height: 220px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        overflow: hidden;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: .4s;
    }

    .product-item:hover .product-thumb img {
        transform: scale(1.06);
    }

    /* =========================
            CONTENT
            ========================= */

    .product-content {
        flex: 1;
        padding: 15px;
        display: flex;
        flex-direction: column;
    }

    .product-content .category {
        font-size: 12px;
        font-weight: 600;
        color: #008a7a;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
    }

    /* Product Name Fixed Height */
    .product-content .title {
        min-height: 48px;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .product-content .title a {
        font-size: 14px;
        font-weight: 600;
        color: #222;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-content .title a:hover {
        color: #008a7a;
    }

    /* Stock */

    .product-content .quantity {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 10px;
        min-height: 20px;
    }

    /* Price */

    .product-content .price {
        font-size: 20px;
        font-weight: 700;
        color: #008a7a;
        display: block;
        margin-top: auto;
    }

    .product-content .price .offer {
        font-size: 14px;
        color: #9ca3af;
        text-decoration: line-through;
        margin-left: 5px;
        font-weight: 500;
    }

    /* =========================
            BUTTON
            ========================= */

    .product-bottom {
        padding: 0 15px 15px;
    }

    .product-bottom .rr-primary-btn {
        width: 100%;
        height: 45px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: .3s;
    }


    /* =========================
            SECTION TITLE
            ========================= */

    .product-top-content .section-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 0;
    }

    /* =========================
            FILTER BUTTON
            ========================= */

    .project-filter {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .project-filter li {
        border: 1px solid #ddd;
        border-radius: 30px;
        padding: 8px 18px;
        cursor: pointer;
        transition: .3s;
    }

    .project-filter li.active,
    .project-filter li:hover {
        background: #008a7a;
        border-color: #008a7a;
        color: #fff;
    }

    /* =========================
            MOBILE
            ========================= */

    @media (max-width: 767px) {

        .product-thumb {
            height: 150px;
            padding: 10px;
        }

        .product-content {
            padding: 10px;
        }

        .product-content .title {
            min-height: 42px;
        }

        .product-content .title a {
            font-size: 13px;
        }

        .product-content .price {
            font-size: 18px;
        }

        .product-bottom {
            padding: 0 10px 10px;
        }

        .product-bottom .rr-primary-btn {
            height: 40px;
            font-size: 13px;
        }
    }

    .size-box,
    .color-box-modal {
        width: 45px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #008a7a;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        position: relative;
    }

    .size-box.active {
        background: #008a7a;
        color: #fff;
        border-color: #008a7a;
    }

    .color-box-modal {
        width: 30px;
        height: 30px;
        display: inline-block;
        border-radius: 50%;
        border: 2px solid #ddd;
        cursor: pointer;
        position: relative;
        transition: transform 0.2s;
    }

    .color-box-modal.active {
        border: 3px solid #000;
    }

    .qty-modern {
        display: flex;
        align-items: center;
        width: fit-content;
        border: 1px solid #ddd;
        border-radius: 50px;
        overflow: hidden;
    }

    .qty-modern input {
        width: 60px;
        text-align: center;
        border: none;
        font-weight: 600;
    }

    .qty-btn {
        width: 45px;
        height: 45px;
        border: none;
        background: #f5f5f5;
        font-size: 20px;
        font-weight: bold;
    }

    .qty-btn:hover {
        background: #ff6600;
        color: #fff;
    }

    .size-box.disabled {
        opacity: .5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .color-box-modal.stock-out {
        opacity: .4;
        cursor: not-allowed;
        position: relative;
    }

    .color-box-modal.stock-out::after {
        /* content:'✕'; */
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
    }




    .product-btn {
        display: flex;
        align-items: center;
        gap: 20px;
        width: 100%;
    }

    /* Quantity Box */
    .qty-modern {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 130px;
        height: 52px;
        background: #008a7a;
        /* screenshot color */
        border-radius: 0;
        overflow: hidden;
    }

    .qty-btn {
        width: 40px;
        height: 52px;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 22px;
        font-weight: 600;
        cursor: pointer;
    }

    .qty-modern input {
        width: 50px;
        border: none;
        background: transparent;
        text-align: center;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
    }

    .qty-modern input:focus {
        outline: none;
    }

    /* Cart Button */
    .cart-btn {
        flex: 1;
        height: 52px;
        border: 2px solid #222;
        border-radius: 50px;
        background: #fff;
        color: #222;
        font-weight: 600;
        font-size: 16px;
        transition: .3s;
        display: flex;
        justify-content: center;
        /* horizontal */
        align-items: center;
    }

    .cart-btn:hover {
        background: #141414;
        color: #fff;
    }

    @media (min-width:1200px) {
        .product-box {
            width: 20%;
        }
    }

    /* Responsive */
    @media (max-width:576px) {

        .product-btn {
            gap: 12px;
        }

        .qty-modern {
            width: 100px;
            height: 48px;
        }

        .qty-btn {
            width: 30px;
            height: 48px;
            font-size: 18px;
        }

        .qty-modern input {
            width: 40px;
            font-size: 16px;
        }

        .cart-btn {
            height: 48px;
            font-size: 15px;
        }
    }

    /* About Section Styles */
    .about-image-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    .about-main-img {
        width: 100%;
        border-radius: 20px;
        transition: transform 0.5s ease;
    }

    .about-image-wrapper:hover .about-main-img {
        transform: scale(1.03);
    }

    .about-subtitle {
        color: var(--rr-color-theme-primary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        display: block;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .about-title {
        font-size: 42px;
        font-weight: 800;
        color: var(--rr-color-heading);
        line-height: 1.3;
    }

    .about-description {
        color: var(--rr-color-text-body);
        font-size: 16px;
        line-height: 1.8;
    }

    .modern-btn-primary {
        background: var(--rr-color-theme-primary);
        color: #ffffff;
        padding: 14px 35px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        border: none;
    }

    .modern-btn-primary:hover {
        background: var(--rr-color-heading, #222);
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .experience-badge {
        background: #fdfdfd;
        padding: 12px 24px;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    @media (max-width: 991px) {
        .about-title {
            font-size: 32px;
        }

        .experience-badge {
            padding: 10px 15px;
        }
    }


    .counter-section {
        background: linear-gradient(135deg, #fcfcfc 0%, #f0f3f5 100%);
        position: relative;
        z-index: 1;
    }

    .modern-counter-card {
        background: #ffffff;
        padding: 45px 25px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.6);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .modern-counter-card:hover {
        /* transform: translateY(-12px); */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: rgba(var(--rr-color-theme-primary-rgb, 103, 176, 46), 0.2);
    }

    .counter-icon-wrap {
        width: 85px;
        height: 85px;
        margin: 0 auto 25px auto;
        background: rgba(var(--rr-color-theme-primary-rgb, 103, 176, 46), 0.08);
        color: var(--rr-color-theme-primary, #67b02e);
        font-size: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
        position: relative;
    }

    .counter-icon-wrap::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 1px dashed rgba(var(--rr-color-theme-primary-rgb, 103, 176, 46), 0.3);
        border-radius: 50%;
        transition: all 0.4s ease;
    }

    .modern-counter-card:hover .counter-icon-wrap {
        background: var(--rr-color-theme-primary, #67b02e);
        color: #ffffff;
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 10px 25px rgba(var(--rr-color-theme-primary-rgb, 103, 176, 46), 0.4);
    }

    .modern-counter-card:hover .counter-icon-wrap::after {
        border-color: var(--rr-color-theme-primary, #67b02e);
        transform: scale(1.1);
        opacity: 0;
    }

    .counter-number {
        font-size: 48px;
        font-weight: 800;
        color: var(--rr-color-heading, #111);
        margin-bottom: 8px;
        display: flex;
        justify-content: center;
        align-items: baseline;
        line-height: 1;
    }

    .counter-suffix {
        font-size: 26px;
        font-weight: 700;
        color: var(--rr-color-theme-primary, #67b02e);
        margin-left: 4px;
    }

    .counter-title {
        font-size: 16px;
        color: var(--rr-color-text-body, #666);
        font-weight: 600;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    @media (max-width: 767px) {
        .modern-counter-card {
            padding: 30px 15px;
            border-radius: 16px;
        }

        .counter-icon-wrap {
            width: 65px;
            height: 65px;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .counter-number {
            font-size: 36px;
        }

        .counter-suffix {
            font-size: 20px;
        }

        .counter-title {
            font-size: 14px;
            letter-spacing: 1px;
        }

        .bestseller-product.pt-60 {
            padding-top: 20px !important;
        }

        .pb-100 {
            padding-bottom: 40px;
        }

        .pt-60 {
            padding-top: 30px;
        }

        .pb-60 {
            padding-bottom: 40px;
        }

        .section-heading {
            margin-bottom: 20px;
        }

        .service-section pb-60 {
            padding-bottom: 20px;
        }

        section.our-process-section.py-5 {
            padding-top: 0px !important;
            padding-bottom: 30px !important;
        }

        .pb-80 {
            padding-bottom: 10px;
        }
    }

    .process-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        text-align: center;
        padding-top: 10px;
    }

    .process-timeline::before {
        content: '';
        position: absolute;
        top: 45px;
        left: 8%;
        right: 8%;
        height: 3px;
        background-color: #008a7a;
        z-index: 0;
    }

    /* Individual Step */
    .process-step {
        flex: 1;
        position: relative;
        z-index: 1;
        padding: 0 15px;
    }

    /* The Icon Circle */
    .process-icon {
        width: 75px;
        height: 75px;
        background-color: #008a7a;
        color: #ffffff;
        font-size: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        border: 6px solid #ffffff;
        box-shadow: 0 0 0 2px rgba(0, 138, 122, 0.25);
        transition: transform 0.3s ease;
    }

    .process-icon:hover {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px rgba(0, 138, 122, 0.35);
    }

    /* Text Styles */
    .step-title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 10px;
    }

    .step-desc {
        font-size: 14px;
        color: #777;
        line-height: 1.5;
    }

    @media (max-width: 991px) {
        .process-timeline {
            flex-direction: column;
            text-align: left;
            padding-left: 20px;
        }

        .process-timeline::before {
            top: 0;
            bottom: 0;
            left: 37px;
            right: auto;
            width: 3px;
            height: 100%;
        }

        .process-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            padding: 0;
        }

        .process-icon {
            margin-bottom: 0;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .step-title {
            margin-top: 10px;
        }
    }

    .carousel-item {
        height: 400px;
    }

    .category-img {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .category-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .hero-list-wrap {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .hero-list-wrap::-webkit-scrollbar {
        width: 6px;
    }

    .hero-list-wrap::-webkit-scrollbar-thumb {
        background: #008a7a;
        border-radius: 10px;
    }

    @media (max-width: 767px) {
        .carousel-item {
            height: 140px;
        }
    }

    /* --- Sidebar Category Click-to-Open Styles --- */
    .hero-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .hero-list>li {
        border-bottom: 1px solid #f0f0f0;
    }

    .hero-list>li:last-child {
        border-bottom: none;
    }

    /* Category Item Wrapper */
    .category-item-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.3s;
    }

    .category-item-wrap:hover {
        background-color: #fcfcfc;
    }

    /* Main Link */
    .category-link {
        flex-grow: 1;
        padding: 12px 15px;
        color: #333;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .category-link i {
        color: #008a7a;
        font-size: 14px;
    }

    .category-link:hover {
        color: #008a7a;
    }

    /* Toggle Button (Arrow) */
    .cat-toggle-btn {
        background: transparent;
        border: none;
        padding: 12px 15px;
        color: #777;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .cat-toggle-btn:hover {
        color: #008a7a;
    }

    /* Arrow Animation on Click */
    .arrow-icon {
        transition: transform 0.35s ease;
        font-size: 12px;
    }

    .cat-toggle-btn[aria-expanded="true"] .arrow-icon {
        transform: rotate(180deg);
        color: #008a7a;
    }

    /* Subcategory List */
    .sub-category-list {
        list-style: none;
        padding: 5px 15px 15px 35px;
        margin: 0;
        background: #fdfdfd;
    }

    .sub-category-list li {
        margin-bottom: 5px;
    }

    .sub-category-list li:last-child {
        margin-bottom: 0;
    }

    .sub-category-list a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        color: #555;
        text-decoration: none;
        font-size: 14px;
        border-radius: 5px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .sub-category-list a:hover {
        color: #008a7a;
        background: #ffffff;
        border-color: rgba(0, 138, 122, 0.2);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .carousel-item {
        height: 400px;
    }

    .category-img {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .category-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .hero-list-wrap {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .hero-list-wrap::-webkit-scrollbar {
        width: 6px;
    }

    .hero-list-wrap::-webkit-scrollbar-thumb {
        background: #008a7a;
        border-radius: 10px;
    }

    @media (max-width: 767px) {
        .carousel-item {
            height: 140px;
        }
    }

    .modal-product-image {
        max-height: 350px;
        object-fit: contain;
    }

    /* --- Sidebar Category Click Styles --- */
    .hero-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .hero-list>li {
        position: relative;
        border-bottom: 1px solid #f0f0f0;
        display: block !important;
    }

    .hero-list>li:last-child {
        border-bottom: none;
    }

    /* Item Wrap */
    .category-item-wrap {
        transition: background-color 0.3s ease;
    }

    .category-item-wrap:hover {
        background-color: #fcfcfc;
    }

    /* Main Category Link/Toggle */
    .category-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 12px 15px;
        color: #333;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .category-link .cat-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .category-link i {
        color: #008a7a;
        font-size: 14px;
    }

    .category-link:hover {
        color: #008a7a;
    }

    /* Toggle Action (Count and Icon) */
    .toggle-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #333;
    }

    .category-link:hover .toggle-indicator {
        color: #008a7a;
    }
    

    .arrow-icon {
        font-size: 12px;
        transition: transform 0.3s ease;
    }

    /* Rotate arrow when open */
    .category-link[aria-expanded="true"] .arrow-icon {
        transform: rotate(180deg);
        color: #008a7a;
    }

    /* Subcategory List */
    .sub-category-list {
        list-style: none;
        padding: 5px 15px 15px 35px;
        /* Indented padding */
        margin: 0;
        background: #fcfcfc;
    }

    .sub-category-list li {
        display: block !important;
        margin-bottom: 5px;
    }

    .sub-category-list li:last-child {
        margin-bottom: 0;
    }

    .sub-category-list a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        color: #555;
        text-decoration: none;
        font-size: 14px;
        border-radius: 5px;
        transition: all 0.3s ease;
        background: #ffffff;
        border: 1px solid #eee;
    }

    .sub-category-list a .sub-cat-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sub-category-list a i {
        font-size: 10px;
        color: #999;
        transition: transform 0.3s ease;
    }

    .sub-category-list a:hover {
        color: #008a7a;
        border-color: rgba(0, 138, 122, 0.3);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .sub-category-list a:hover i {
        color: #008a7a;
        transform: translateX(3px);
        /* Small slide effect on hover */
    }

    /* Modal Responsive css  */

    .qty-modern {
        display: inline-flex;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        height: 45px;
    }
    .qty-btn {
        background: transparent;
        border: none;
        width: 45px;
        font-size: 20px;
        cursor: pointer;
    }
    #modalQty {
        width: 50px;
        text-align: center;
        border: none;
        border-left: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
        font-weight: bold;
    }

    @media (max-width: 767px) {
        
        #cartModal .col-md-5.p-4 {
            padding: 15px !important;
        }
        #modalProductImage {
            max-height: 200px !important; 
        }

        #cartModal .col-md-7 .p-4 {
            padding: 20px !important;
        }

        #modalProductName {
            font-size: 20px !important;
        }
        #cartModal .fs-3 {
            font-size: 22px !important; 
        }

        #cartModal .product-btn {
            display: flex;
            flex-direction: column;
            gap: 15px; 
        }

        #cartModal .qty-modern {
            width: 100%;
            justify-content: space-between;
        }
        #cartModal .qty-modern input {
            width: 100%; 
        }

        #finalAddToCart {
            width: 100%;
            padding: 12px;
            border-radius: 22px;
        }

        #cartModal .btn-close {
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            opacity: 1;
        }
    }
</style>
