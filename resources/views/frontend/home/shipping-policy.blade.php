@extends('frontend.layouts.app')
@section('title', 'Shipping & Delivery Policy | Unibox') 

@push('css')
<style>
    .policy-wrapper {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        border: 1px solid #f3f4f6;
        padding: 40px 50px;
        margin-top: 40px;
        margin-bottom: 80px;
    }
    .policy-header-title {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }
    .policy-last-updated {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 40px;
        display: block;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 20px;
    }
    .policy-section-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1f2937;
        margin-top: 35px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    .policy-section-title::before {
        content: "";
        display: inline-block;
        width: 6px;
        height: 20px;
        background: #111827;
        border-radius: 4px;
        margin-right: 12px;
    }
    .policy-text {
        color: #4b5563;
        line-height: 1.8;
        font-size: 1rem;
        margin-bottom: 15px;
    }
    .policy-list {
        padding-left: 1.5rem;
        margin-bottom: 20px;
        color: #4b5563;
        line-height: 1.8;
    }
    .policy-list li {
        margin-bottom: 8px;
    }
    .policy-list li::marker {
        color: #9ca3af;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .policy-wrapper {
            padding: 25px 20px;
            margin-top: 20px;
            margin-bottom: 40px;
            border-radius: 12px;
        }
        .policy-header-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<main>
    <section class="container mw-930">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                
                <div class="policy-wrapper">
                    <h1 class="policy-header-title">Shipping & Delivery Policy</h1>
                    <span class="policy-last-updated">Last Updated: {{ date('F d, Y') }}</span>

                    <h5 class="policy-section-title">অর্ডার প্রসেসিং (Order Processing)</h5>
                    <p class="policy-text">
                        Unibox-এ প্লেস করা প্রতিটি অর্ডার কনফার্ম হওয়ার পর দ্রুততম সময়ের মধ্যে প্যাক করে কুরিয়ারে হ্যান্ডওভার করা হয়। 
                    </p>
                    <ul class="policy-list">
                        <li>সাধারণত অর্ডার কনফার্মেশনের <strong>২৪ ঘণ্টার মধ্যে</strong> পণ্য কুরিয়ারে হস্তান্তর করা হয়।</li>
                        <li>অর্ডার প্রসেসিং এবং ডেলিভারির কাজ শুধুমাত্র কার্যদিবসে (শনিবার থেকে বৃহস্পতিবার) সম্পন্ন হয়। সরকারি ছুটির দিনে প্রসেসিং বন্ধ থাকে।</li>
                    </ul>

                    <h5 class="policy-section-title">ডেলিভারি সময়সীমা (Delivery Timeline)</h5>
                    <p class="policy-text">আমরা বাংলাদেশের শীর্ষস্থানীয় কুরিয়ার সার্ভিসগুলোর মাধ্যমে সারা দেশে ডেলিভারি প্রদান করে থাকি। ডেলিভারির আনুমানিক সময়:</p>
                    <ul class="policy-list">
                        <li><strong>ঢাকার ভেতরে:</strong> ১ থেকে ২ কার্যদিবস।</li>
                        <li><strong>ঢাকার বাইরে (সদর ও উপজেলা পর্যায়ে):</strong> ২ থেকে ৪ কার্যদিবস।</li>
                    </ul>
                    <p class="policy-text text-muted" style="font-size: 0.9rem;">
                        * বিশেষ নোটিশ: প্রাকৃতিক দুর্যোগ, রাজনৈতিক অস্থিরতা বা কুরিয়ার সার্ভিসের অভ্যন্তরীণ সমস্যার কারণে ডেলিভারি সময়ে কিছুটা বিলম্ব হতে পারে।
                    </p>

                    <h5 class="policy-section-title">ডেলিভারি চার্জ (Delivery Charges)</h5>
                    <p class="policy-text">অর্ডার করার সময় চেকআউট পেজে ডেলিভারি চার্জ স্বয়ংক্রিয়ভাবে যুক্ত হয়ে যায়। সাধারণত ডেলিভারি চার্জ নিম্নরূপ:</p>
                    <ul class="policy-list">
                        <li><strong>ঢাকার ভেতরে:</strong> ৳৭০ (স্ট্যান্ডার্ড)</li>
                        <li><strong>ঢাকার বাইরে:</strong> ৳১৩০ (স্ট্যান্ডার্ড)</li>
                    </ul>
                    <p class="policy-text">
                        <em>* পণ্যের ওজন এবং সাইজের ওপর ভিত্তি করে ডেলিভারি চার্জ পরিবর্তিত হতে পারে। অফার চলাকালীন সময়ে ডেলিভারি চার্জ ফ্রী থাকতে পারে।</em>
                    </p>

                    <h5 class="policy-section-title">অর্ডার ট্র্যাকিং (Order Tracking)</h5>
                    <p class="policy-text">
                        আপনার পার্সেলটি কুরিয়ারে বুকিং হওয়ার পর আমরা আপনাকে একটি কনফার্মেশন SMS বা ইমেইল পাঠাবো। এছাড়া, আপনি আপনার Unibox অ্যাকাউন্টে লগইন করে অথবা কুরিয়ারের ট্র্যাকিং লিঙ্ক ব্যবহার করে সহজেই আপনার অর্ডারের বর্তমান অবস্থা জানতে পারবেন।
                    </p>

                    <h5 class="policy-section-title">পার্সেল রিসিভ করার নিয়ম (Instant Check Policy)</h5>
                    <p class="policy-text">
                        আমাদের রিটার্ন পলিসি অনুযায়ী, ডেলিভারি ম্যানের উপস্থিতিতে অবশ্যই পার্সেল খুলে চেক করে নিতে হবে। 
                    </p>
                    <ul class="policy-list">
                        <li>পণ্যটি সঠিক এবং ড্যামেজ-ফ্রি আছে কিনা, তা ডেলিভারি ম্যান সামনে থাকা অবস্থায়ই নিশ্চিত করুন।</li>
                        <li>কোনো সমস্যা থাকলে সাথে সাথে ডেলিভারি ম্যানকে পণ্য ফেরত দিন। ডেলিভারি ম্যান চলে যাওয়ার পর ড্যামেজ বা মিসিং সংক্রান্ত কোনো অভিযোগ গ্রহণ করা হবে না।</li>
                    </ul>

                    <h5 class="policy-section-title">আমাদের সাথে যোগাযোগ করুন</h5>
                    <p class="policy-text">
                        ডেলিভারি সংক্রান্ত যেকোনো জিজ্ঞাসা বা সমস্যার জন্য আমাদের সাপোর্ট টিমের সাথে যোগাযোগ করুন:
                    </p>
                    <ul class="policy-list" style="list-style-type: none; padding-left: 0;">
                        <li>📞 <strong>Phone:</strong> [আপনার ফোন নাম্বার দিন]</li>
                        <li>📧 <strong>Email:</strong> [আপনার ইমেইল দিন]</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection