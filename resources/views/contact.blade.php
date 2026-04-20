@extends('layouts.app')

@section('title', 'Contact Us - AlphaVendor Multi Vendor Marketplace')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fadeInLeft {
        animation: fadeInLeft 0.8s ease-out forwards;
    }

    .animate-fadeInRight {
        animation: fadeInRight 0.8s ease-out forwards;
    }

    .animate-scaleIn {
        animation: scaleIn 0.6s ease-out forwards;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .delay-100 { animation-delay: 0.1s; opacity: 0; }
    .delay-200 { animation-delay: 0.2s; opacity: 0; }
    .delay-300 { animation-delay: 0.3s; opacity: 0; }
    .delay-400 { animation-delay: 0.4s; opacity: 0; }
    .delay-500 { animation-delay: 0.5s; opacity: 0; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative bg-[#0d5c63] text-white py-16 sm:py-20 md:py-24 lg:py-32 overflow-hidden">
    <div class="absolute inset-0 bg-[#0a4a50] opacity-30"></div>
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-48 sm:w-64 md:w-72 h-48 sm:h-64 md:h-72 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float"></div>
        <div class="absolute bottom-10 right-10 w-48 sm:w-64 md:w-72 h-48 sm:h-64 md:h-72 bg-[#2e9099] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float" style="animation-delay: 2s;"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6 animate-fadeInUp">{{ $content['hero_title'] ?? 'Contact Us' }}</h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 sm:mb-8 animate-fadeInUp delay-100 max-w-3xl mx-auto px-4">{{ $content['hero_subtitle'] ?? 'We\'d love to hear from you. Get in touch with our team' }}</p>
        </div>
    </div>
</section>

<!-- Contact Info Section -->
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-12 sm:mb-16">
            <div class="text-center p-6 sm:p-8 bg-teal-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-fadeInUp delay-100">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#0d5c63] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-map-marker-alt text-white text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $content['address_title'] ?? 'Visit Us' }}</h3>
                <p class="text-gray-600 text-sm sm:text-base">
                    {{ $content['address_line1'] ?? '123 Business Street' }}<br>
                    @if(!empty($content['address_line2'])){{ $content['address_line2'] }}<br>@endif
                    @if(!empty($content['address_line3'])){{ $content['address_line3'] }}@endif
                </p>
            </div>
            <div class="text-center p-6 sm:p-8 bg-teal-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-fadeInUp delay-200">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#0d5c63] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-phone text-white text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $content['phone_title'] ?? 'Call Us' }}</h3>
                <p class="text-gray-600 text-sm sm:text-base">
                    {{ $content['phone_primary'] ?? '+1 (555) 123-4567' }}<br>
                    @if(!empty($content['phone_secondary'])){{ $content['phone_secondary'] }}<br>@endif
                    @if(!empty($content['phone_hours'])){{ $content['phone_hours'] }}@endif
                </p>
            </div>
            <div class="text-center p-6 sm:p-8 bg-teal-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-fadeInUp delay-300">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#0d5c63] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-envelope text-white text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $content['email_title'] ?? 'Email Us' }}</h3>
                <p class="text-gray-600 text-sm sm:text-base">
                    {{ $content['email_info'] ?? 'info@alphavendor.com' }}<br>
                    @if(!empty($content['email_support'])){{ $content['email_support'] }}<br>@endif
                    @if(!empty($content['email_sales'])){{ $content['email_sales'] }}@endif
                </p>
            </div>
            <div class="text-center p-6 sm:p-8 bg-teal-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-fadeInUp delay-400">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#0d5c63] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-clock text-white text-xl sm:text-2xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">{{ $content['hours_title'] ?? 'Working Hours' }}</h3>
                <p class="text-gray-600 text-sm sm:text-base">
                    {{ $content['hours_weekdays'] ?? 'Monday - Friday' }}<br>
                    {{ $content['hours_weekdays_time'] ?? '9:00 AM - 6:00 PM' }}<br>
                    @if(!empty($content['hours_weekend'])){{ $content['hours_weekend'] }}@endif
                </p>
            </div>
        </div>
    </div>
</section>
<!-- Contact Form & Map Section -->
<section class="py-12 sm:py-16 md:py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Contact Form -->
            <div class="animate-fadeInLeft">
                <div class="bg-white p-6 sm:p-8 md:p-10 rounded-2xl shadow-lg">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6">{{ $content['form_title'] ?? 'Send Us a Message' }}</h2>
                    <form action="#" method="POST" class="space-y-4 sm:space-y-6">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c63] focus:border-transparent transition-all text-sm sm:text-base" placeholder="John Doe">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c63] focus:border-transparent transition-all text-sm sm:text-base" placeholder="john@example.com">
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c63] focus:border-transparent transition-all text-sm sm:text-base" placeholder="+1 (555) 123-4567">
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                                <select id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c63] focus:border-transparent transition-all text-sm sm:text-base">
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="support">Technical Support</option>
                                    <option value="sales">Sales Question</option>
                                    <option value="partnership">Partnership</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c63] focus:border-transparent transition-all resize-none text-sm sm:text-base" placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-[#0d5c63] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg hover:bg-[#0a4a50] transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map & Social -->
            <div class="animate-fadeInRight space-y-6 sm:space-y-8">
                <!-- Map -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">{{ $content['map_title'] ?? 'Our Location' }}</h3>
                    <div class="w-full h-64 sm:h-80 bg-gray-200 rounded-xl overflow-hidden">
                        <iframe src="{{ $content['map_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.11976373946234!3d40.69766374865766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s' }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">{{ $content['social_title'] ?? 'Follow Us' }}</h3>
                    <p class="text-gray-600 mb-6 text-sm sm:text-base">{{ $content['social_description'] ?? 'Stay connected with us on social media for updates, news, and special offers.' }}</p>
                    <div class="flex flex-wrap gap-3 sm:gap-4">
                        @if(!empty($content['social_facebook']))
                        <a href="{{ $content['social_facebook'] }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-[#0d5c63] text-white rounded-full hover:bg-[#0a4a50] transition-all duration-300 transform hover:scale-110 shadow-lg">
                            <i class="fab fa-facebook-f text-lg sm:text-xl"></i>
                        </a>
                        @endif
                        @if(!empty($content['social_twitter']))
                        <a href="{{ $content['social_twitter'] }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-[#0d5c63] text-white rounded-full hover:bg-[#0a4a50] transition-all duration-300 transform hover:scale-110 shadow-lg">
                            <i class="fab fa-twitter text-lg sm:text-xl"></i>
                        </a>
                        @endif
                        @if(!empty($content['social_instagram']))
                        <a href="{{ $content['social_instagram'] }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-[#0d5c63] text-white rounded-full hover:bg-[#0a4a50] transition-all duration-300 transform hover:scale-110 shadow-lg">
                            <i class="fab fa-instagram text-lg sm:text-xl"></i>
                        </a>
                        @endif
                        @if(!empty($content['social_linkedin']))
                        <a href="{{ $content['social_linkedin'] }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-[#0d5c63] text-white rounded-full hover:bg-[#0a4a50] transition-all duration-300 transform hover:scale-110 shadow-lg">
                            <i class="fab fa-linkedin-in text-lg sm:text-xl"></i>
                        </a>
                        @endif
                        @if(!empty($content['social_youtube']))
                        <a href="{{ $content['social_youtube'] }}" class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-[#0d5c63] text-white rounded-full hover:bg-[#0a4a50] transition-all duration-300 transform hover:scale-110 shadow-lg">
                            <i class="fab fa-youtube text-lg sm:text-xl"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16 animate-fadeInUp">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">{{ $content['faq_title'] ?? 'Frequently Asked Questions' }}</h2>
            <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto px-4">{{ $content['faq_subtitle'] ?? 'Find quick answers to common questions' }}</p>
        </div>
        <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
            <div class="bg-teal-50 p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 animate-fadeInUp delay-100">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3">How can I become a vendor on AlphaVendor?</h3>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">To become a vendor, simply register an account and apply for vendor status through your dashboard. Our team will review your application within 24-48 hours.</p>
            </div>
            <div class="bg-teal-50 p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 animate-fadeInUp delay-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3">What are your shipping policies?</h3>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">Each vendor sets their own shipping policies. You can find specific shipping information on individual product pages. We support both local and international shipping.</p>
            </div>
            <div class="bg-teal-50 p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 animate-fadeInUp delay-300">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3">How do I track my order?</h3>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">Once your order ships, you'll receive a tracking number via email. You can also track your orders through your account dashboard.</p>
            </div>
            <div class="bg-teal-50 p-6 sm:p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 animate-fadeInUp delay-400">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3">What payment methods do you accept?</h3>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">We accept all major credit cards, PayPal, bank transfers, and various other payment methods depending on your region.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 sm:py-20 bg-[#0d5c63] text-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto animate-fadeInUp">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6">{{ $content['cta_title'] ?? 'Still Have Questions?' }}</h2>
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 opacity-90 px-4">{{ $content['cta_subtitle'] ?? 'Our support team is here to help you. Reach out anytime!' }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 px-4">
                @if(!empty($content['cta_email_text']) && !empty($content['cta_email_link']))
                <a href="mailto:{{ $content['cta_email_link'] }}" class="bg-white text-[#0d5c63] px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-envelope mr-2"></i>{{ $content['cta_email_text'] }}
                </a>
                @endif
                @if(!empty($content['cta_phone_text']) && !empty($content['cta_phone_link']))
                <a href="tel:{{ $content['cta_phone_link'] }}" class="border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg hover:bg-white hover:text-[#0d5c63] transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-phone mr-2"></i>{{ $content['cta_phone_text'] }}
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
