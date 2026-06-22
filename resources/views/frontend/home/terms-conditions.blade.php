@extends('frontend.layouts.app')
@section('title', 'Terms & Conditions | Unibox') 

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
                    <h1 class="policy-header-title">Terms & Conditions</h1>
                    <span class="policy-last-updated">Last Updated: {{ date('F d, Y') }}</span>

                    <h5 class="policy-section-title">Acceptance of Terms</h5>
                    <p class="policy-text">
                        এই ওয়েবসাইট ব্যবহার করার মাধ্যমে আপনি আমাদের Terms & Conditions মেনে নিচ্ছেন। আপনি যদি এই শর্তগুলোর সাথে একমত না হন, তাহলে অনুগ্রহ করে আমাদের ওয়েবসাইট ব্যবহার করবেন না।
                    </p>

                    <h5 class="policy-section-title">Use of Website & Content</h5>
                    <p class="policy-text">
                        এই ওয়েবসাইটের সকল কন্টেন্ট (ছবি, টেক্সট, প্রোডাক্ট তথ্য, ডিজাইন) আমাদের সম্পত্তি। অনুমতি ছাড়া কোনো কন্টেন্ট কপি, পরিবর্তন বা পুনরায় ব্যবহার করা যাবে না।
                    </p>

                    <h5 class="policy-section-title">Product Information</h5>
                    <p class="policy-text">
                        আমরা চেষ্টা করি যেন প্রোডাক্টের ছবি ও বিবরণ যথাসম্ভব সঠিক হয়। তবে আলো, স্ক্রিন রেজোলিউশন বা ম্যানুফ্যাকচারিং পার্থক্যের কারণে বাস্তব পণ্যের সাথে সামান্য পার্থক্য থাকতে পারে।
                    </p>

                    <h5 class="policy-section-title">Pricing & Payment</h5>
                    <p class="policy-text">
                        সকল পণ্যের মূল্য আমাদের ওয়েবসাইটে উল্লেখ করা আছে এবং আমরা যেকোনো সময় পূর্ব নোটিশ ছাড়াই মূল্য পরিবর্তনের অধিকার রাখি। পেমেন্ট সম্পূর্ণ হওয়ার পরই অর্ডার কনফার্ম হবে।
                    </p>

                    <h5 class="policy-section-title">Shipping & Delivery</h5>
                    <p class="policy-text">
                        অর্ডার কনফার্ম হওয়ার পর নির্ধারিত সময়ের মধ্যে পণ্য ডেলিভারি দেওয়ার চেষ্টা করা হয়। তবে প্রাকৃতিক দুর্যোগ, লজিস্টিক সমস্যা বা অন্য কোনো অনিবার্য কারণে ডেলিভারিতে বিলম্ব হতে পারে।
                    </p>

                    <h5 class="policy-section-title">Return & Refund Policy</h5>
                    <p class="policy-text">
                        প্রোডাক্ট রিটার্ন বা রিফান্ড সংক্রান্ত সকল নিয়ম আমাদের Return Policy অনুযায়ী প্রযোজ্য। ব্যবহৃত, ক্ষতিগ্রস্ত বা কাস্টমাইজড পণ্য সাধারণত রিটার্নযোগ্য নয়।
                    </p>

                    <h5 class="policy-section-title">User Responsibilities</h5>
                    <p class="policy-text">
                        ব্যবহারকারী হিসেবে আপনি সঠিক ও সম্পূর্ণ তথ্য প্রদান করতে বাধ্য। ভুল বা মিথ্যা তথ্যের কারণে কোনো সমস্যা হলে তার দায়ভার সম্পূর্ণভাবে ব্যবহারকারীর।
                    </p>

                    <h5 class="policy-section-title">Limitation of Liability</h5>
                    <p class="policy-text">
                        আমাদের ওয়েবসাইট বা পণ্য ব্যবহারের ফলে কোনো প্রত্যক্ষ বা পরোক্ষ ক্ষতির জন্য আমরা দায়বদ্ধ থাকবো না, যতটুকু আইন দ্বারা অনুমোদিত।
                    </p>

                    <h5 class="policy-section-title">Governing Law</h5>
                    <p class="policy-text">
                        এই Terms & Conditions বাংলাদেশের প্রচলিত আইন অনুযায়ী পরিচালিত হবে। যেকোনো আইনি বিরোধ সংশ্লিষ্ট আদালতের অধীনে নিষ্পত্তি হবে।
                    </p>

                    <h5 class="policy-section-title">Changes to Terms</h5>
                    <p class="policy-text">
                        আমরা যেকোনো সময় এই Terms & Conditions আপডেট বা পরিবর্তন করার অধিকার রাখি। পরিবর্তিত শর্ত ওয়েবসাইটে প্রকাশের সাথে সাথেই কার্যকর হবে।
                    </p>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection