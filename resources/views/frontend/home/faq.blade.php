@extends('frontend.layouts.app')
@section('title', 'Frequently Asked Questions')

@section('content')
<section class="faq-section pt-100 pb-100" style="background-color: #fafbfc;">
    <div class="container">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6 text-center">
                <span class="badge rounded-pill mb-2 py-2 px-3" style="background-color: rgba(229, 62, 62, 0.1); color: #E53E3E; font-weight: 600;">
                    <i class="fa-regular fa-circle-question me-1"></i> Support Center
                </span>
                <h2 class="fw-bold mb-3" style="color: #2b3445; font-size: 38px;">How can we help you?</h2>
                <p class="text-muted fs-6">Find the answers to the most common questions below.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="custom-faq-wrapper accordion" id="uniboxFaq">
                    
                    <div class="faq-card">
                        <div class="faq-header" id="faqHeading1" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" role="button">
                            <h5 class="faq-title">How long does delivery take?</h5>
                            <div class="faq-icon"><i class="fa-solid fa-plus plus-icon"></i><i class="fa-solid fa-minus minus-icon"></i></div>
                        </div>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#uniboxFaq">
                            <div class="faq-body">
                                Standard delivery inside Dhaka takes 1-2 business days. For deliveries outside Dhaka, it usually takes 3-5 business days depending on courier availability.
                            </div>
                        </div>
                    </div>

                    <div class="faq-card">
                        <div class="faq-header collapsed" id="faqHeading2" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" role="button">
                            <h5 class="faq-title">Can I cancel or change my order?</h5>
                            <div class="faq-icon"><i class="fa-solid fa-plus plus-icon"></i><i class="fa-solid fa-minus minus-icon"></i></div>
                        </div>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#uniboxFaq">
                            <div class="faq-body">
                                You can cancel or modify your order while its status is still "Pending". Once the order moves to "Accepted" or "On the way", it cannot be modified.
                            </div>
                        </div>
                    </div>

                    <div class="faq-card">
                        <div class="faq-header collapsed" id="faqHeading3" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" role="button">
                            <h5 class="faq-title">What payment methods do you accept?</h5>
                            <div class="faq-icon"><i class="fa-solid fa-plus plus-icon"></i><i class="fa-solid fa-minus minus-icon"></i></div>
                        </div>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#uniboxFaq">
                            <div class="faq-body">
                                We accept Cash on Delivery (COD), Mobile Banking (bKash, Nagad, Rocket), and secure Credit/Debit card payments via our online payment gateway.
                            </div>
                        </div>
                    </div>

                    <div class="faq-card">
                        <div class="faq-header collapsed" id="faqHeading4" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" role="button">
                            <h5 class="faq-title">Are there any hidden charges?</h5>
                            <div class="faq-icon"><i class="fa-solid fa-plus plus-icon"></i><i class="fa-solid fa-minus minus-icon"></i></div>
                        </div>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#uniboxFaq">
                            <div class="faq-body">
                                No, there are absolutely no hidden charges. All prices shown on our website are inclusive of taxes. Only standard delivery fees will be added at checkout.
                            </div>
                        </div>
                    </div>

                    <div class="faq-card">
                        <div class="faq-header collapsed" id="faqHeading5" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" role="button">
                            <h5 class="faq-title">What should I do if I receive a damaged product?</h5>
                            <div class="faq-icon"><i class="fa-solid fa-plus plus-icon"></i><i class="fa-solid fa-minus minus-icon"></i></div>
                        </div>
                        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#uniboxFaq">
                            <div class="faq-body">
                                If you receive a defective or damaged product, please contact our support helpline within 24 hours of delivery. We will arrange a free exchange or return replacement for you.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<style>
    /* Custom FAQ Card Styling */
    .faq-card { background: #ffffff; border-radius: 12px; margin-bottom: 16px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s ease; overflow: hidden; }
    .faq-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.06); border-color: rgba(229, 62, 62, 0.2); }
    .faq-header { padding: 22px 25px; display: flex; justify-content: space-between; align-items: center; background-color: transparent; transition: background-color 0.3s; }
    .faq-header[aria-expanded="true"] { background-color: rgba(229, 62, 62, 0.03); }
    .faq-title { font-size: 17px; font-weight: 600; color: #2b3445; margin: 0; transition: color 0.3s; }
    .faq-header[aria-expanded="true"] .faq-title { color: #E53E3E; }
    .faq-icon { position: relative; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; color: #7d879c; transition: color 0.3s; }
    .faq-header[aria-expanded="true"] .faq-icon { color: #E53E3E; }
    .plus-icon, .minus-icon { position: absolute; transition: all 0.3s ease; font-size: 16px; }
    .faq-header[aria-expanded="true"] .plus-icon { opacity: 0; transform: rotate(90deg); }
    .faq-header.collapsed .minus-icon { opacity: 0; transform: rotate(-90deg); }
    .faq-body { padding: 0 25px 25px 25px; color: #64748b; font-size: 15px; line-height: 1.7; }
</style>
@endsection