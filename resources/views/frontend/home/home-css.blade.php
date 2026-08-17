<style>
    :root {
    --rr-color-theme-primary: #008a7a;
    --rr-color-heading: #111111;
    --rr-color-text-body: #666666;
}

/* ==========================================
   1. HERO & CAROUSEL SECTION (Updated)
   ========================================== */
.hero-list-wrap {
    background: #ffffff;
    border-radius: 12px; 
    box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
    border: 1px solid #e9ecef;
    overflow: hidden; 
    height: 500px !important; 
    display: flex;
    flex-direction: column; 
}

.category-header-custom {
    display: flex;
    align-items: center;
    background-color: #f4f6f8; 
    padding: 20px;
    position: relative;
    border-bottom: 1px solid #e0e0e0;
    flex-shrink: 0; 
}

.category-header-custom::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 30px;
    border-width: 10px 10px 0;
    border-style: solid;
    border-color: #f4f6f8 transparent transparent transparent;
    z-index: 1;
}

.header-icon-wrap {
    font-size: 32px;
    color: #00897b; 
    margin-right: 15px;
    line-height: 1;
}

.header-title-text {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #2c3e50;
    text-transform: uppercase;
}

/* স্লাইডারের ফিক্সড হাইট */
.carousel-inner, .carousel-item {
    height: 500px !important;
    border-radius: 12px;
}

.hero-item {
    height: 100% !important;
    width: 100%;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    border-radius: 12px;
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

/* ==========================================
   2. SIDEBAR & CATEGORY NAVIGATION (Updated)
   ========================================== */
.hero-list {
    padding: 10px 0;
    margin: 0;
    flex-grow: 1; 
    overflow-y: auto;
    list-style: none;
}

/* ক্যাটাগরি লিস্টের স্ক্রল বার */
.hero-list::-webkit-scrollbar {
    width: 5px; 
}
.hero-list::-webkit-scrollbar-track {
    background: #f4f6f8; 
    border-radius: 10px;
}
.hero-list::-webkit-scrollbar-thumb {
    background: #d1d5db; 
    border-radius: 10px;
}
.hero-list::-webkit-scrollbar-thumb:hover {
    background: #00897b; 
}

.hero-list > li {
    position: relative;
    border-bottom: 1px solid #f0f0f0;
    display: block !important;
}

.hero-list > li:last-child {
    border-bottom: none;
}

.category-item-wrap {
    transition: background-color 0.3s ease;
}

.category-item-wrap:hover {
    background-color: #fcfcfc;
}

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
    color: var(--rr-color-theme-primary);
    font-size: 14px;
}

.category-link:hover {
    color: var(--rr-color-theme-primary);
}

.toggle-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
}

.category-link:hover .toggle-indicator {
    color: var(--rr-color-theme-primary);
}

.arrow-icon {
    font-size: 12px;
    transition: transform 0.3s ease;
}

.category-link[aria-expanded="true"] .arrow-icon {
    transform: rotate(180deg);
    color: var(--rr-color-theme-primary);
}

/* Subcategory */
.sub-category-list {
    list-style: none;
    padding: 5px 15px 15px 15px;
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
    padding: 13px 8px !important;
    color: #555;
    text-decoration: none;
    font-size: 14px;
    border-radius: 5px;
    transition: all 0.3s ease;
    background: #ffffff;
    border: 1px solid #eee;
}

.sub-category-list a:hover {
    color: var(--rr-color-theme-primary);
    border-color: rgba(0, 138, 122, 0.3);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
}

.sub-category-list a:hover i {
    color: var(--rr-color-theme-primary);
    transform: translateX(3px);
}

/* ==========================================
   3. PRODUCT CARD DESIGN
   ========================================== */
.product-box {
    display: flex;
}

@media (min-width: 1200px) {
    .product-box {
        width: 20%;
    }
    .bestseller-box {
        width: 20%;
    }
}

.product-item.product-item-2 {
    width: 100%;
    background: #fff;
    border: 1px solid rgba(0, 138, 122, 0.3);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
}

.product-item.product-item-2:hover {
    transform: translateY(0px);
    box-shadow: 0 10px 30px rgba(0, 138, 122, 0.15);
    border-color: var(--rr-color-theme-primary);
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
    transition: 0.4s;
}

.product-item:hover .product-thumb img {
    transform: scale(1.06);
}

/* Content */
.product-content {
    flex: 1;
    padding: 15px;
    display: flex;
    flex-direction: column;
}

.product-content .category {
    font-size: 12px;
    font-weight: 600;
    color: var(--rr-color-theme-primary);
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}

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
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-content .title a:hover {
    color: var(--rr-color-theme-primary);
}

.product-content .quantity {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 10px;
    min-height: 20px;
}

.product-content .price {
    font-size: 20px;
    font-weight: 700;
    color: var(--rr-color-theme-primary);
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

.product-bottom {
    padding: 0 15px 15px;
}

.product-bottom .rr-primary-btn {
    width: 100%;
    height: 45px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: 0.3s;
    align-items: center;
    justify-content: center;
}

/* ==========================================
   4. PRODUCT VARIANTS & QUANTITY
   ========================================== */
.size-box {
    width: 45px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--rr-color-theme-primary);
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    position: relative;
}

.size-box.active {
    background: var(--rr-color-theme-primary);
    color: #fff;
    border-color: var(--rr-color-theme-primary);
}

.size-box.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
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

.color-box-modal.stock-out {
    opacity: 0.4;
    cursor: not-allowed;
}

.product-btn {
    display: flex;
    align-items: center;
    gap: 20px;
    width: 100%;
}

.qty-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 130px;
    height: 52px;
    background: var(--rr-color-theme-primary);
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    width: 40px;
    height: 52px;
    border: none;
    background: transparent;
    color: #fff;
    font-size: 20px;
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

.cart-btn {
    flex: 1;
    height: 52px;
    border: 2px solid #222;
    border-radius: 50px;
    background: #fff;
    color: #222;
    font-weight: 600;
    font-size: 16px;
    transition: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
}

.cart-btn:hover {
    background: #141414;
    color: #fff;
}

/* ==========================================
   5. SECTIONS & TIMELINE
   ========================================== */
.product-top-content .section-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 0;
}

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
    transition: 0.3s;
}

.project-filter li.active,
.project-filter li:hover {
    background: var(--rr-color-theme-primary);
    border-color: var(--rr-color-theme-primary);
    color: #fff;
}

/* About Section */
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

/* Counter Section */
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
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: rgba(0, 138, 122, 0.2);
}

.counter-icon-wrap {
    width: 85px;
    height: 85px;
    margin: 0 auto 25px auto;
    background: rgba(0, 138, 122, 0.08);
    color: var(--rr-color-theme-primary);
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
    inset: -5px;
    border: 1px dashed rgba(0, 138, 122, 0.3);
    border-radius: 50%;
    transition: all 0.4s ease;
}

.modern-counter-card:hover .counter-icon-wrap {
    background: var(--rr-color-theme-primary);
    color: #ffffff;
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 10px 25px rgba(0, 138, 122, 0.4);
}

.modern-counter-card:hover .counter-icon-wrap::after {
    border-color: var(--rr-color-theme-primary);
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
    color: var(--rr-color-theme-primary);
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

/* Process Timeline */
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
    background-color: var(--rr-color-theme-primary);
    z-index: 0;
}

.process-step {
    flex: 1;
    position: relative;
    z-index: 1;
    padding: 0 15px;
}

.process-icon {
    width: 75px;
    height: 75px;
    background-color: var(--rr-color-theme-primary);
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

/* Modal Specifics */
.custom-modal-img-container {
    background-color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 30px !important;
}

#modalProductImage,
.modal-product-image {
    max-height: 350px !important;
    width: auto !important;
    max-width: 100% !important;
    object-fit: contain !important;
}

#modalQty {
    width: 50px;
    text-align: center;
    border: none;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
    font-weight: bold;
}

/* ==========================================
   6. RESPONSIVE MEDIA QUERIES
   ========================================== */
@media (max-width: 991px) {
    .about-title {
        font-size: 32px;
    }

    .experience-badge {
        padding: 10px 15px;
    }

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

@media (max-width: 767px) {
    .carousel-inner, .carousel-item {
        height: 250px !important; 
    }

    .hero-list-wrap {
        height: auto !important;
    }

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

    .pb-100 { padding-bottom: 40px; }
    .pt-60 { padding-top: 30px; }
    .pb-60 { padding-bottom: 40px; }
    .pb-80 { padding-bottom: 10px; }

    .section-heading {
        margin-bottom: 20px;
    }

    section.our-process-section.py-5 {
        padding-top: 0px !important;
        padding-bottom: 30px !important;
    }

    

    /* Modal Responsive */
    .custom-modal-img-container {
        padding: 20px 15px 5px 15px !important;
    }

    #modalProductImage {
        max-height: 300px !important;
        max-width: 85% !important;
        margin: 0 auto !important;
        display: block !important;
    }

    #cartModal .btn-close {
        position: absolute !important;
        top: 15px;
        right: 15px;
        background-color: #ffffff;
        border-radius: 50%;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        opacity: 1;
        z-index: 1055;
    }

    #cartModal .col-md-7 .p-4 {
        padding: 15px 22px 25px 22px !important;
    }

    #modalProductName {
        font-size: 18px !important;
        font-weight: 700;
        line-height: 1.4;
        color: #111;
        margin-top: 5px;
    }

    #cartModal .fs-3 {
        font-size: 22px !important;
    }

    #cartModal .mb-4 {
        margin-bottom: 12px !important;
    }

    #cartModal .product-btn {
        display: grid !important;
        grid-template-columns: 120px 1fr !important;
        gap: 12px !important;
        align-items: center;
        margin-top: 20px !important;
    }

    #cartModal .qty-modern {
        width: 100% !important;
        height: 46px !important;
        border-radius: 30px !important;
        padding: 0 8px !important;
        display: flex !important;
        align-items: center;
        justify-content: space-between;
    }

    #finalAddToCart {
        width: 100% !important;
        height: 46px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px !important;
        font-weight: 600 !important;
        border-radius: 30px !important;
        border: 1.5px solid #000000 !important;
        background-color: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
    }
}
.heading-space {
    position: relative;
}

@media (min-width: 768px) {
    .heading-space {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 35px !important;
    }
    .heading-space .product-top-content.text-start {
        margin-bottom: 0 !important;
        flex-shrink: 0;
        margin-right: 30px;
    }
    .heading-space .section-title {
        text-transform: uppercase;
        font-size: 24px;
        font-weight: 800;
    }
}

@media (max-width: 767px) {
    .heading-space {
        padding-bottom: 10px;
    }
}

.project-filter {
    display: flex !important;
    flex-wrap: nowrap !important;
    overflow-x: auto !important;
    justify-content: flex-start !important; 
    -ms-overflow-style: none;
    scrollbar-width: none;
    align-items: center;
    gap: 12px;
    margin: 0;
    padding-left: 15px; 
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

.project-filter::-webkit-scrollbar {
    display: none;
}

.project-filter li {
    white-space: nowrap;
    padding: 8px 24px !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 50px !important; /* Pill Shape */
    color: #4b5563;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    margin: 0 !important;
    flex-shrink: 0;
}

@media (max-width: 767px) {
    .project-filter li {
        font-size: 13px !important;
        padding: 8px 16px !important;
    }
}

.project-filter li.active,
.project-filter li:hover {
    background: var(--rr-color-theme-primary) !important;
    border-color: var(--rr-color-theme-primary) !important;
    color: #ffffff !important;
}


/* 🟢 Smart Swipe Arrow (Desktop & Mobile) 🟢 */
/* 🟢 Base Swipe Arrow (Hidden on Desktop, Visible on Mobile) 🟢 */
.swipe-indicator {
    position: absolute;
    right: 0;
    bottom: 0;
    height: 100%;
    min-height: 40px;
    width: 60px;
    background: linear-gradient(to right, rgba(244,246,248,0) 0%, #f4f6f8 60%); 
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 5px;
    color: #111;
    font-size: 14px;
    font-weight: bold;
    z-index: 10;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

/* 🟢 Mobile Specific Styling 🟢 */
@media (max-width: 767px) {
    .swipe-indicator {
        height: 40px;
        bottom: 8px;
        color: #008a7a;
    }
    .swipe-indicator i {
        animation: swipeBounce 1.2s infinite;
    }
}

/* 🟢 Hide Swipe Arrow on Desktop 🟢 */
@media (min-width: 768px) {
    .swipe-indicator {
        display: none !important;
    }
}

@keyframes swipeBounce {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(4px); }
}




@media (max-width: 576px) {
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
</style>