@extends('pricing.base')

{{-- Meta tags / Open Graph / Twitter Card --}}
@section('title', 'Walive – Trusted WhatsApp Link Generator, QR Maker & Business Communication Tools')
@section('meta_description', 'Learn about Walive, trusted by 10K+ businesses for WhatsApp link generation, QR codes, analytics & communication tools.')
@section('meta_keywords', 'Walive About, WhatsApp tools, WhatsApp business QR code, Walive team')

@section('og_title', 'About Walive – Business WhatsApp Tools')
@section('og_description', 'Walive helps businesses create WhatsApp links, QR codes and manage communication at scale.')
@section('og_image', 'https://walive.link/assets/about-og.png')

@section('twitter_title', 'About Walive – WhatsApp Tools')
@section('twitter_description', 'Know how Walive helps global businesses with WhatsApp link creation & analytics.')


@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Hero: use H1 once for best SEO -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 whatsapp-bg-gradient rounded-full text-white text-sm font-medium mb-4" aria-hidden="true">
                <i class="fab fa-whatsapp mr-2"></i>
                <span>About Walive</span>
            </div>

            <h1 class="font-bold text-4xl md:text-5xl whatsapp-text-gradient mb-4">
                WhatsApp Business Tools for Teams & Marketers
            </h1>

            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                Walive makes it simple for businesses to create WhatsApp links and QR codes, measure engagement, and run scalable customer communication campaigns — all from one dashboard.
            </p>
        </div>

        <!-- Company Story (use H2 / H3) -->
        <section class="mb-20" aria-labelledby="our-story">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 id="our-story" class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
                    <div class="space-y-4 text-gray-700">
                        <p>
                            Founded in 2023, Walive was created to help businesses overcome ad-hoc WhatsApp workflows. We saw teams manually setting up links, juggling multiple numbers, and lacking analytics to measure impact — so we built a platform to fix that.
                        </p>
                        <p>
                            Our team of product-first engineers and growth marketers focused on building simple tools that integrate into marketing stacks. Today, thousands of businesses across 50+ countries use Walive to convert web visitors into customers via WhatsApp.
                        </p>
                        <p>
                            Whether you're a small store or an enterprise support team, Walive streamlines WhatsApp outreach with link & QR generation, bulk link creation, and an analytics dashboard that surfaces what's working.
                        </p>
                    </div>
                </div>

                <aside class="relative" aria-hidden="false">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-8 shadow-lg">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-4xl font-bold whatsapp-text-gradient mb-2">10K+</div>
                                <div class="text-gray-600">Businesses Trust Walive</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold whatsapp-text-gradient mb-2">50+</div>
                                <div class="text-gray-600">Countries Served</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold whatsapp-text-gradient mb-2">5M+</div>
                                <div class="text-gray-600">Links Generated</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold whatsapp-text-gradient mb-2">24/7</div>
                                <div class="text-gray-600">Customer Support</div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="mb-20" aria-labelledby="mission-vision">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <article class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
                    <div class="w-16 h-16 whatsapp-bg-gradient rounded-xl flex items-center justify-center mb-6" aria-hidden="true">
                        <i class="fas fa-bullseye text-white text-2xl"></i>
                    </div>
                    <h3 id="mission" class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                    <p class="text-gray-700 mb-4">
                        Democratize business communication by providing powerful, affordable WhatsApp tools that help businesses engage customers and drive growth.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Make WhatsApp accessible for all businesses</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Simplify complex communication workflows</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Provide actionable insights with analytics</span>
                        </li>
                    </ul>
                </article>

                <article class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
                    <div class="w-16 h-16 whatsapp-bg-gradient rounded-xl flex items-center justify-center mb-6" aria-hidden="true">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h3 id="vision" class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                    <p class="text-gray-700 mb-4">
                        To be the global leader in WhatsApp business tools, enabling companies to build faster, more personal customer journeys.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Global leader in WhatsApp business tools</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Innovator in communication technology</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Trusted partner for businesses worldwide</span>
                        </li>
                    </ul>
                </article>
            </div>
        </section>

        <!-- Core Values -->
        <section class="mb-20" aria-labelledby="core-values">
            <h2 id="core-values" class="text-3xl font-bold text-center text-gray-900 mb-12">Our Core Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 whatsapp-bg-gradient rounded-full flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                        <i class="fas fa-lightbulb text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
                    <p class="text-gray-600">Constantly pushing boundaries to create better WhatsApp tools.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 whatsapp-bg-gradient rounded-full flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                        <i class="fas fa-bolt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Simplicity</h3>
                    <p class="text-gray-600">Making complex communication tools easy to use.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 whatsapp-bg-gradient rounded-full flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                        <i class="fas fa-shield-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Reliability</h3>
                    <p class="text-gray-600">99.9% uptime and secure service you can count on.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 whatsapp-bg-gradient rounded-full flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Customer Success</h3>
                    <p class="text-gray-600">We're committed to helping you grow using WhatsApp.</p>
                </div>
            </div>
        </section>

        <!-- Features Highlight -->
        <section class="mb-20" aria-labelledby="features">
            <h2 id="features" class="text-3xl font-bold text-center text-gray-900 mb-12">Why Choose Walive?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <article class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                    <div class="w-14 h-14 whatsapp-bg-gradient rounded-xl flex items-center justify-center mb-4" aria-hidden="true">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Full Analytics Suite</h3>
                    <p class="text-gray-700">Track link performance, user engagement, and conversion metrics with our analytics dashboard.</p>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Real-time click tracking</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Geographic analytics</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Device & browser reports</li>
                    </ul>
                </article>

                <article class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                    <div class="w-14 h-14 whatsapp-bg-gradient rounded-xl flex items-center justify-center mb-4" aria-hidden="true">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Phone Support</h3>
                    <p class="text-gray-700">Phone, email & chat support with fast response times and dedicated account managers.</p>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Phone, email & chat</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Average response time: 2 minutes</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Dedicated account managers</li>
                    </ul>
                </article>

                <article class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                    <div class="w-14 h-14 whatsapp-bg-gradient rounded-xl flex items-center justify-center mb-4" aria-hidden="true">
                        <i class="fas fa-qrcode text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Generator & QR Maker</h3>
                    <p class="text-gray-700">Create custom WhatsApp links and QR codes — ideal for campaigns, business cards, and kiosks.</p>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Customizable QR designs</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Multiple format downloads</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Bulk link generation</li>
                    </ul>
                </article>
            </div>
        </section>

        <!-- CTA Section -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-12">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Join Thousands of Successful Businesses</h3>
                <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                    Start using Walive and transform how you communicate with your customers.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('register') }}"
                       class="px-8 py-4 whatsapp-bg-gradient text-white rounded-xl hover:opacity-90 transition font-bold text-lg shadow-lg">
                        Start Free Trial
                    </a>
                    <a href="{{ route('pricing') }}"
                       class="px-8 py-4 bg-white text-green-600 border-2 border-green-600 rounded-xl hover:bg-green-50 transition font-bold text-lg">
                        View Pricing
                    </a>
                </div>
                <p class="text-sm text-gray-500 mt-6">No credit card required • 14-day free trial</p>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
    /* WhatsApp Colors */
    :root {
        --whatsapp-green: #25D366;
        --whatsapp-dark-green: #128C7E;
        --whatsapp-teal-green: #075E54;
        --whatsapp-light-green: #dcf8c6;
        --whatsapp-blue: #34B7F1;
    }
    .whatsapp-bg-gradient {
        background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
    }
    .whatsapp-text-gradient {
        background: linear-gradient(135deg, var(--whatsapp-green), var(--whatsapp-dark-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush

{{-- JSON-LD: Organization + FAQ (structured data) --}}
@section('structured-data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "name": "Walive",
      "url": "https://walive.link",
      "logo": "https://walive.link/images/walive-logo.png",
      "sameAs": [
        "https://www.linkedin.com/company/walive",
        "https://twitter.com/walive",
        "https://facebook.com/walive"
      ],
      "contactPoint": [{
        "@type": "ContactPoint",
        "telephone": "+1-800-000-0000",
        "contactType": "customer support",
        "areaServed": "Worldwide",
        "availableLanguage": ["English"]
      }]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What is Walive?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Walive is a business platform for creating WhatsApp links and QR codes, tracking analytics, and managing customer conversations at scale."
          }
        },
        {
          "@type": "Question",
          "name": "Can I generate bulk WhatsApp links?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes — Walive supports bulk link generation for marketing campaigns and enterprise workflows."
          }
        },
        {
          "@type": "Question",
          "name": "Is there a free trial?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Walive offers a 14-day free trial with no credit card required."
          }
        }
      ]
    }
  ]
}
</script>
@endsection

@endsection
