@extends('frontend.layouts.app')
@section('title', 'Privacy Policy | Unibox') 

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
                    <h1 class="policy-header-title">Privacy Policy</h1>
                    <span class="policy-last-updated">Last Updated: {{ date('F d, Y') }}</span>

                    <h5 class="policy-section-title">Introduction</h5>
                    <p class="policy-text">
                        আমরা আপনার গোপনীয়তাকে গুরুত্ব সহকারে দেখি। এই Privacy Policy ব্যাখ্যা করে কীভাবে আমরা আপনার ব্যক্তিগত তথ্য সংগ্রহ, ব্যবহার ও সুরক্ষা করি যখন আপনি আমাদের ওয়েবসাইট ব্যবহার করেন।
                    </p>

                    <h5 class="policy-section-title">Information We Collect</h5>
                    <p class="policy-text">আমরা নিম্নলিখিত তথ্য সংগ্রহ করতে পারি:</p>
                    <ul class="policy-list">
                        <li>নাম এবং যোগাযোগের তথ্য (ফোন নম্বর, ইমেইল ঠিকানা)।</li>
                        <li>ডেলিভারি ঠিকানা এবং বিলিং ইনফরমেশন।</li>
                        <li>পেমেন্ট সংক্রান্ত প্রয়োজনীয় তথ্য।</li>
                        <li>আপনার অর্ডার ইতিহাস এবং ওয়েবসাইটের ব্রাউজিং ডেটা।</li>
                    </ul>

                    <h5 class="policy-section-title">How We Use Your Information</h5>
                    <p class="policy-text">আপনার তথ্য প্রধানত নিচের কাজগুলোতে ব্যবহার করা হয়:</p>
                    <ul class="policy-list">
                        <li>অর্ডার প্রসেস করা এবং পণ্য ডেলিভারি দেওয়া।</li>
                        <li>কাস্টমার সাপোর্ট প্রদান এবং পেমেন্ট যাচাই করা।</li>
                        <li>আমাদের সেবা ও ওয়েবসাইটের ইউজার এক্সপেরিয়েন্স উন্নত করা।</li>
                    </ul>

                    <h5 class="policy-section-title">Cookies & Tracking Technologies</h5>
                    <p class="policy-text">
                        আমাদের ওয়েবসাইটে Cookies ব্যবহার করা হতে পারে যাতে আপনার ব্রাউজিং অভিজ্ঞতা আরও উন্নত হয়। আপনি চাইলে আপনার ব্রাউজার সেটিংস থেকে Cookies নিষ্ক্রিয় করতে পারেন, তবে এতে ওয়েবসাইটের কিছু ফিচার কাজ না-ও করতে পারে।
                    </p>

                    <h5 class="policy-section-title">Data Protection</h5>
                    <p class="policy-text">
                        আমরা আপনার ব্যক্তিগত তথ্য সুরক্ষিত রাখতে যথাযথ টেকনিক্যাল ও অর্গানাইজেশনাল ব্যবস্থা গ্রহণ করি। তবে ইন্টারনেটের মাধ্যমে তথ্য আদান-প্রদান সম্পূর্ণ ১০০% নিরাপদ—এমন নিশ্চয়তা আইনত দেওয়া সম্ভব নয়।
                    </p>

                    <h5 class="policy-section-title">Sharing of Information</h5>
                    <p class="policy-text">
                        আমরা আপনার ব্যক্তিগত তথ্য কোনো তৃতীয় পক্ষের কাছে বিক্রি বা ভাড়া দেই না। তবে অর্ডার ডেলিভারি (কুরিয়ার পার্টনার), পেমেন্ট প্রসেসিং (পেমেন্ট গেটওয়ে) বা আইনি প্রয়োজনে নির্ভরযোগ্য সার্ভিস প্রোভাইডারের সাথে তথ্য শেয়ার করা হতে পারে।
                    </p>

                    <h5 class="policy-section-title">User Rights</h5>
                    <p class="policy-text">
                        আপনি চাইলে আপনার ব্যক্তিগত তথ্য আপডেট, সংশোধন বা ডিলিট করার অনুরোধ করতে পারেন। এ বিষয়ে আমাদের সাথে যেকোনো সময় যোগাযোগ করতে পারেন।
                    </p>

                    <h5 class="policy-section-title">Third-Party Links</h5>
                    <p class="policy-text">
                        আমাদের ওয়েবসাইটে তৃতীয় পক্ষের ওয়েবসাইটের লিংক থাকতে পারে। আমরা সেই ওয়েবসাইটগুলোর Privacy Policy বা তাদের কার্যক্রমের জন্য দায়ী নই।
                    </p>

                    <h5 class="policy-section-title">Changes to This Policy</h5>
                    <p class="policy-text">
                        আমরা যেকোনো সময় এই Privacy Policy আপডেট করার অধিকার রাখি। পরিবর্তিত নীতিমালা ওয়েবসাইটে প্রকাশের সাথে সাথেই কার্যকর হবে।
                    </p>

                    <h5 class="policy-section-title">Contact Us</h5>
                    <p class="policy-text">
                        আমাদের Privacy Policy সম্পর্কে কোনো প্রশ্ন বা অভিযোগ থাকলে অনুগ্রহ করে আমাদের Contact পেজের মাধ্যমে অথবা সাপোর্ট ইমেইলে যোগাযোগ করুন।
                    </p>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection