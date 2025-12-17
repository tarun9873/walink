@extends('pricing.base')

{{-- Meta tags / Open Graph / Twitter Card --}}
@section('title', 'Privacy Policy – Walive')
@section('meta_description', 'Walive Privacy Policy – Learn how we collect, use, and protect your data while using our WhatsApp link and QR code services.')
@section('meta_keywords', 'Walive Privacy Policy, data protection, WhatsApp links privacy')

@section('og_title', 'Privacy Policy – Walive')
@section('og_description', 'Understand how Walive protects your personal information and ensures data security.')
@section('og_image', 'https://walive.link/assets/privacy-og.png')

@section('twitter_title', 'Privacy Policy – Walive')
@section('twitter_description', 'Walive Privacy Policy and data protection practices.')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Hero -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 whatsapp-bg-gradient rounded-full text-white text-sm font-medium mb-4">
                <i class="fas fa-user-shield mr-2"></i>
                <span>Privacy Policy</span>
            </div>
            <h1 class="font-bold text-4xl md:text-5xl whatsapp-text-gradient mb-4">Your Privacy Matters at Walive</h1>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                This Privacy Policy explains how Walive collects, uses, stores, and protects your information when you use our website and services.
            </p>
        </div>

        <!-- Policy Content -->
        <section class="space-y-12 text-gray-700">

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Information We Collect</h2>
                <p>We collect information necessary to provide WhatsApp link generation, QR codes, analytics, and subscription services.</p>
                <ul class="list-disc ml-6 mt-3 space-y-2">
                    <li>Personal details such as name, email address, and phone number</li>
                    <li>Account usage data including links created and analytics</li>
                    <li>Technical data like IP address, browser type, and device information</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. How We Use Your Information</h2>
                <ul class="list-disc ml-6 space-y-2">
                    <li>To create and manage your Walive account</li>
                    <li>To provide analytics and WhatsApp link services</li>
                    <li>To process subscriptions and payments</li>
                    <li>To improve platform security and performance</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Cookies & Tracking</h2>
                <p>Walive uses cookies to enhance user experience, track analytics, and maintain secure sessions. You may disable cookies via browser settings.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Data Sharing</h2>
                <p>We do not sell your personal data. Information may only be shared with trusted service providers or legal authorities when required by law.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Security</h2>
                <p>We use encryption, secure servers, and regular backups to protect your information. However, no online service can guarantee complete security.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Payments & Subscriptions</h2>
                <p>Payments are processed via secure third-party gateways. Walive does not store card or banking information.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Your Rights</h2>
                <ul class="list-disc ml-6 space-y-2">
                    <li>Access, update, or delete your data</li>
                    <li>Cancel your subscription anytime</li>
                    <li>Request account deletion</li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Changes to This Policy</h2>
                <p>Walive may update this Privacy Policy periodically. Continued use of the platform indicates acceptance of the updated policy.</p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Contact Us</h2>
                <p>If you have any questions regarding this Privacy Policy, contact us at:</p>
                <p class="mt-2"><strong>Email:</strong> support@walive.link</p>
                <p><strong>Website:</strong> https://walive.link</p>
            </div>

        </section>
    </div>
</div>

@push('styles')
<style>
    :root {
        --whatsapp-green: #25D366;
        --whatsapp-dark-green: #128C7E;
    }
    .whatsapp-bg-gradient {
        background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
    }
    .whatsapp-text-gradient {
        background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush
@endsection