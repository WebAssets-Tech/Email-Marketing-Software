<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <!-- Essential Meta Tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Primary Title & Meta Description for High-Intent SEO -->
  <title>MailWave — #1 AI Email Marketing &amp; Bulk Email Platform | Web Assets</title>
  <meta name="description" content="Scale your business with MailWave by Web Assets. High-deliverability bulk email platform, multi-SMTP routing, Amazon SES integration, Twilio SMS broadcast, and ChatGPT copywriting. Zero per-subscriber fees.">
  <meta name="keywords" content="MailWave, Web Assets, email marketing platform, bulk email platform, self-hosted email marketing, Amazon SES email marketing, multi-SMTP relay, Mailchimp alternative, newsletter builder, AI email copywriter, bulk SMS gateway">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="author" content="Web Assets">
  <meta name="theme-color" content="#2563EB">
  <link rel="canonical" href="{{ url()->current() }}">

  <!-- Open Graph Meta Tags (Social Media & Sharing) -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="MailWave">
  <meta property="og:title" content="MailWave — Email Marketing. Made to Reach.">
  <meta property="og:description" content="Send bulk email campaigns, automate customer drip workflows, broadcast SMS, and write converting copy with ChatGPT. Unlimited contacts with your own SMTP &amp; SES routing. Powered by Web Assets.">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('frontend/mailwave/mailwave-logo.png') }}">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="342">
  <meta property="og:image:alt" content="MailWave Logo - Email Marketing. Made to Reach.">
  <meta property="og:locale" content="en_US">

  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@webassetstech">
  <meta name="twitter:creator" content="@webassetstech">
  <meta name="twitter:title" content="MailWave — #1 AI Email Marketing &amp; Bulk Email Platform">
  <meta name="twitter:description" content="Stop paying per-subscriber fees. Dispatch unlimited emails and SMS with Amazon SES, Twilio, and OpenAI copilot.">
  <meta name="twitter:image" content="{{ asset('frontend/mailwave/mailwave-logo.png') }}">

  <!-- Favicons using the official MailWave App Icon -->
  <link rel="icon" type="image/png" href="{{ asset('frontend/mailwave/mailwave-icon.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('frontend/mailwave/mailwave-icon.png') }}">

  <!-- Google Fonts Preconnect -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="{{ asset('frontend/mailwave/style.css') }}">

  <!-- Structured Data (JSON-LD) for Search Engine Optimization -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "SoftwareApplication",
        "@id": "{{ url('/') }}/#software",
        "name": "MailWave",
        "url": "{{ url('/') }}",
        "operatingSystem": "Web, Cloud, Self-Hosted Linux/Docker",
        "applicationCategory": "BusinessApplication, MarketingApplication",
        "softwareVersion": "6.0",
        "description": "MailWave by Web Assets is an enterprise AI email marketing and bulk email platform featuring multi-SMTP routing, drag-and-drop template editor, Amazon SES integration, and ChatGPT copilot.",
        "image": "{{ asset('frontend/mailwave/mailwave-logo.png') }}",
        "offers": {
          "@type": "AggregateOffer",
          "priceCurrency": "USD",
          "lowPrice": "19",
          "highPrice": "149",
          "offerCount": "3"
        },
        "aggregateRating": {
          "@type": "AggregateRating",
          "ratingValue": "4.9",
          "ratingCount": "1240",
          "bestRating": "5",
          "worstRating": "1"
        },
        "creator": {
          "@type": "Organization",
          "name": "Web Assets"
        }
      },
      {
        "@type": "Organization",
        "@id": "{{ url('/') }}/#organization",
        "name": "Web Assets",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('frontend/mailwave/mailwave-logo.png') }}",
        "sameAs": [
          "https://twitter.com/webassetstech",
          "https://github.com/WebAssets-Tech"
        ]
      },
      {
        "@type": "WebSite",
        "@id": "{{ url('/') }}/#website",
        "url": "{{ url('/') }}",
        "name": "MailWave",
        "publisher": {
          "@id": "{{ url('/') }}/#organization"
        },
        "potentialAction": {
          "@type": "SearchAction",
          "target": "{{ url('/') }}/?s={search_term_string}",
          "query-input": "required name=search_term_string"
        }
      },
      {
        "@type": "FAQPage",
        "@id": "{{ url('/') }}/#faq",
        "mainEntity": [
          {
            "@type": "Question",
            "name": "Can I really send unlimited emails without per-subscriber penalties with MailWave?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "Yes. Unlike Mailchimp or Klaviyo, MailWave by Web Assets does not penalize you for growing your contact list. You connect your own ultra-affordable delivery relays (Amazon SES, Mailgun, SMTP, Gmail) and pay pennies per 10,000 emails directly to the provider."
            }
          },
          {
            "@type": "Question",
            "name": "Which SMTP and SMS providers are supported out of the box in MailWave?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "MailWave natively integrates with Amazon SES, Mailgun, SendGrid, Gmail Workspace, Zoho Mail, ElasticEmail, and custom SMTP servers. For SMS and WhatsApp, we integrate Twilio, Nexmo/Vonage, Plivo, Infobip, and Viber."
            }
          },
          {
            "@type": "Question",
            "name": "Do I need coding or technical skills to use MailWave?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "None at all. MailWave features a visual drag-and-drop newsletter builder, one-click gateway connection wizards, and pre-built templates so any marketer can launch high-converting campaigns immediately."
            }
          },
          {
            "@type": "Question",
            "name": "How does the built-in ChatGPT AI integration assist my campaigns in MailWave?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "MailWave's AI copilot generates high-converting email subject lines, body copy variations, and compelling SMS messages tailored to your target audience in seconds to maximize open and click-through rates."
            }
          },
          {
            "@type": "Question",
            "name": "Can I white-label MailWave and charge my own clients?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "Absolutely. MailWave includes multi-tenant SaaS features, automated client subscription billing via Stripe and PayPal, and a built-in CSV marketplace where you can sell verified contact segments."
            }
          }
        ]
      }
    ]
  }
  </script>
</head>
<body>

  <!-- Ambient Glow Effects (Light Theme Warm & Cool Glows) -->
  <div class="hero-glow-warm"></div>
  <div class="hero-glow-cool"></div>

  <!-- Header / Navigation Bar with Official MailWave Logo -->
  <header class="navbar" id="navbar">
    <div class="container nav-container">
      <a href="{{ url('/') }}" class="logo-brand" aria-label="MailWave - Email Marketing. Made to Reach.">
        <img src="{{ asset('frontend/mailwave/mailwave-logo.png') }}" alt="MailWave - Email Marketing. Made to Reach." class="brand-logo-img" width="165" height="55">
      </a>

      <nav class="nav-menu" id="nav-menu" aria-label="Main Navigation">
        <ul class="nav-links">
          <li><a href="#features" class="nav-link">Features</a></li>
          <li><a href="#comparison" class="nav-link">Why MailWave</a></li>
          <li><a href="#showcase" class="nav-link">Platform</a></li>
          <li><a href="#integrations" class="nav-link">Integrations</a></li>
          <li><a href="#workflow" class="nav-link">How It Works</a></li>
          <li><a href="#pricing" class="nav-link">Pricing</a></li>
          <li><a href="#faq" class="nav-link">FAQ</a></li>
        </ul>
        <div class="mobile-nav-actions">
          @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-md w-full">Go To Dashboard</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-md w-full">Sign In</a>
            @if (Route::has('user_register'))
              <a href="{{ route('user_register') }}" class="btn btn-primary btn-md w-full">
                <span>Get Started Free</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
              </a>
            @endif
          @endauth
        </div>
      </nav>

      <div class="nav-actions">
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm desktop-only-btn" id="nav-dash-btn">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="btn btn-outline btn-sm desktop-only-btn" id="nav-login-btn">Sign In</a>
          @if (Route::has('user_register'))
            <a href="{{ route('user_register') }}" class="btn btn-primary btn-sm desktop-only-btn" id="nav-register-btn">
              <span>Get Started Free</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
          @endif
        @endauth
        <button class="mobile-toggle" id="mobile-toggle" aria-label="Open Navigation Menu" aria-expanded="false">
          <svg class="hamburger-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
          <svg class="close-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
    </div>
  </header>

  <main>
    <!-- HERO SECTION (Split Layout Matching Reference) -->
    <section class="hero-section" id="hero">
      <div class="container hero-grid">
        <!-- Hero Left Column: Copy & Actions with Primary H1 -->
        <div class="hero-content">
          <div class="hero-pill">
            <span style="color: #F59E0B;">✦</span>
            <span>By Web Assets • #1 Bulk Email &amp; Marketing Platform</span>
          </div>

          <h1 class="hero-title">
            AI Email Marketing. <span class="gradient-text">Made to Reach</span> Every Inbox.
          </h1>

          <p class="hero-description">
            MailWave is the high-deliverability bulk email platform engineered for scaling businesses. Send unlimited campaigns via Amazon SES, Twilio SMS, and multi-SMTP routing without painful per-contact penalties.
          </p>

          <div class="hero-cta-box">
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" id="hero-cta-dash">
                <span>Go To Dashboard</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
              </a>
            @else
              @if (Route::has('user_register'))
                <a href="{{ route('user_register') }}" class="btn btn-primary btn-lg" id="hero-cta-trial">
                  <span>Start Free 14-Day Trial</span>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </a>
              @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Get Started</a>
              @endif
            @endauth
            <a href="#showcase" class="btn btn-outline btn-lg" id="hero-cta-demo">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="5 3 19 12 5 21 5 3"></polygon>
              </svg>
              <span>Explore Demo</span>
            </a>
          </div>

          <div class="hero-trust-row">
            <div class="trust-item">
              <div class="trust-item-icon">✓</div>
              <span>99.4% Primary Inbox Rate</span>
            </div>
            <div class="trust-item">
              <div class="trust-item-icon">✓</div>
              <span>No Credit Card Required</span>
            </div>
            <div class="trust-item">
              <div class="trust-item-icon">✓</div>
              <span>Multi-SMTP &amp; SES Load Balancing</span>
            </div>
          </div>
        </div>

        <!-- Hero Right Column: High-Impact Visual Card with Floating Elements -->
        <div class="hero-visual-wrapper">
          <!-- Floating Badge 1 (Top-Left) -->
          <div class="float-card float-top-left">
            <div class="float-icon-box icon-sms">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
            </div>
            <div class="float-text">
              <h5>Campaign Dispatched</h5>
              <p>142,000 Delivered • 99.8% Open Rate</p>
            </div>
          </div>

          <!-- Floating Badge 2 (Bottom-Right) -->
          <div class="float-card float-bottom-right">
            <div class="float-icon-box icon-ai">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
              </svg>
            </div>
            <div class="float-text">
              <h5>ChatGPT Copilot</h5>
              <p>Subject Lines Optimized (+48% CTR)</p>
            </div>
          </div>

          <!-- Central Ambient Image Frame -->
          <div class="hero-main-card">
            <img src="{{ asset('frontend/argon/assets/img/person-1.png') }}" alt="Entrepreneur scaling with MailWave" class="hero-person-img" loading="eager" width="380" height="460">
          </div>
        </div>
      </div>
    </section>

    <!-- SOCIAL PROOF / CLIENT LOGOS STRIP -->
    <div class="social-proof-strip">
      <div class="container">
        <p class="social-proof-title">Trusted by 12,000+ Fast-Growing Brands, Agencies &amp; Marketers</p>
        <div class="client-logos-row">
          <img src="{{ asset('frontend/argon/assets/img/logo-codelab.png') }}" alt="CodeLab" class="client-logo-img" height="28">
          <img src="{{ asset('frontend/argon/assets/img/logo-atica.png') }}" alt="Atica" class="client-logo-img" height="28">
          <img src="{{ asset('frontend/argon/assets/img/logo-earth.png') }}" alt="Earth" class="client-logo-img" height="28">
          <img src="{{ asset('frontend/argon/assets/img/logo-foxhub.png') }}" alt="FoxHub" class="client-logo-img" height="28">
          <img src="{{ asset('frontend/argon/assets/img/logo-ideaa.png') }}" alt="Ideaa" class="client-logo-img" height="28">
          <img src="{{ asset('frontend/argon/assets/img/logo-treva.png') }}" alt="Treva" class="client-logo-img" height="28">
        </div>
      </div>
    </div>

    <!-- METRICS RIBBON (Dark Navy Strip from Reference Image 1) -->
    <section class="metrics-ribbon" aria-label="MailWave Key Statistics">
      <div class="container">
        <div class="metrics-grid">
          <div class="metric-item">
            <div class="metric-item-num">25M+</div>
            <div class="metric-item-label">Emails &amp; SMS Delivered</div>
          </div>
          <div class="metric-item">
            <div class="metric-item-num">99.4%</div>
            <div class="metric-item-label">Primary Inbox Deliverability</div>
          </div>
          <div class="metric-item">
            <div class="metric-item-num">12,000+</div>
            <div class="metric-item-label">Active Scaling Businesses</div>
          </div>
          <div class="metric-item">
            <div class="metric-item-num">4.9 / 5</div>
            <div class="metric-item-label">Verified Customer Satisfaction</div>
          </div>
        </div>
      </div>
    </section>

    <!-- CORE 4 VALUE PILLARS -->
    <section class="section section-bg-subtle" id="pillars">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Core Value Pillars</span>
          <h2 class="section-title">Built For Growth-Focused Marketers</h2>
          <p class="section-subtitle">Everything you need to broadcast, automate, and monetize under one intuitive roof without expensive third-party locks.</p>
        </div>

        <div class="pillars-grid">
          <div class="pillar-card">
            <div class="pillar-icon-box icon-box-blue">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
              </svg>
            </div>
            <h4>Visual Drag &amp; Drop Studio</h4>
            <p>Craft pixel-perfect, responsive newsletters without touching a single line of code. Export or edit anytime.</p>
          </div>

          <div class="pillar-card">
            <div class="pillar-icon-box icon-box-orange">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
            </div>
            <h4>Omnichannel SMS &amp; WhatsApp</h4>
            <p>Reach customers instantly on their phones. Multi-provider gateways guarantee 99% open rates within 3 minutes.</p>
          </div>

          <div class="pillar-card">
            <div class="pillar-icon-box icon-box-green">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a10 10 0 1 0 10 10H12V2z"></path>
                <path d="M12 12L2.1 12.05"></path>
                <path d="M12 12l6.9-7.1"></path>
              </svg>
            </div>
            <h4>ChatGPT Copywriting Copilot</h4>
            <p>Generate high-converting subject lines, email sequences, and persuasive call-to-actions with built-in OpenAI intelligence.</p>
          </div>

          <div class="pillar-card">
            <div class="pillar-icon-box icon-box-pink">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 20V10"></path>
                <path d="M12 20V4"></path>
                <path d="M6 20v-6"></path>
              </svg>
            </div>
            <h4>Real-Time Heatmaps &amp; Tracking</h4>
            <p>Track opens, unique clicks, bounces, and geographic engagement in real time with automated spam shield protection.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- BEFORE VS AFTER: PROBLEM VS SOLUTION (From Reference Image 1) -->
    <section class="section" id="comparison">
      <div class="container">
        <div class="section-header">
          <span class="section-tag" style="background: #FEE2E2; color: #DC2626;">Compare Approaches</span>
          <h2 class="section-title">The Old Expensive Way vs <span class="gradient-text">The MailWave Way</span></h2>
          <p class="section-subtitle">See why thousands of entrepreneurs and agencies are leaving overpriced legacy marketing tools behind.</p>
        </div>

        <div class="comparison-grid">
          <!-- The Old Way Card -->
          <div class="comparison-card comparison-card-old">
            <div class="card-badge-old">
              <span>✕</span> The Legacy Way
            </div>
            <h3>Paying Per-Contact Extortion</h3>
            <ul class="comparison-list">
              <li class="comparison-item">
                <div class="item-old-icon">✕</div>
                <div><strong>Punished for list growth:</strong> Monthly fees scale exponentially from $150 to $1,200+ just because you added subscribers.</div>
              </li>
              <li class="comparison-item">
                <div class="item-old-icon">✕</div>
                <div><strong>Spam &amp; Promotions trap:</strong> Shared IP pools ruin your sender reputation and sink open rates below 12%.</div>
              </li>
              <li class="comparison-item">
                <div class="item-old-icon">✕</div>
                <div><strong>Fragmented tool fatigue:</strong> Paying separately for email marketing, SMS software, and AI writing assistants.</div>
              </li>
              <li class="comparison-item">
                <div class="item-old-icon">✕</div>
                <div><strong>Zero data privacy:</strong> Customer lists stored on foreign proprietary cloud servers prone to arbitrary account bans.</div>
              </li>
              <li class="comparison-item">
                <div class="item-old-icon">✕</div>
                <div><strong>No monetization potential:</strong> Unable to rebrand, resell, or charge your own clients for sending access.</div>
              </li>
            </ul>
          </div>

          <!-- The MailWave Smart Way Card (High-Contrast Navy from Reference) -->
          <div class="comparison-card comparison-card-new">
            <div class="card-badge-new">
              <span>✓</span> The MailWave Smart Way
            </div>
            <h3>Unlimited Scale &amp; Full Control</h3>
            <ul class="comparison-list">
              <li class="comparison-item">
                <div class="item-new-icon">✓</div>
                <div><strong>100% Flat Predictable Pricing:</strong> Upload 500,000+ contacts without price spikes. Connect Amazon SES and pay pennies.</div>
              </li>
              <li class="comparison-item">
                <div class="item-new-icon">✓</div>
                <div><strong>99.4% Primary Inbox placement:</strong> Dedicated multi-SMTP load balancing, automatic bounce filtering, and SPF/DKIM wizard.</div>
              </li>
              <li class="comparison-item">
                <div class="item-new-icon">✓</div>
                <div><strong>Unified All-In-One Hub:</strong> Drag-and-drop email builder, SMS gateway, WhatsApp broadcast, and ChatGPT copywriter combined.</div>
              </li>
              <li class="comparison-item">
                <div class="item-new-icon">✓</div>
                <div><strong>Total Infrastructure Ownership:</strong> Complete data control with self-hosted sovereignty or private dedicated cloud.</div>
              </li>
              <li class="comparison-item">
                <div class="item-new-icon">✓</div>
                <div><strong>Built-in SaaS Multi-Tenancy:</strong> Charge your own clients subscription fees via Stripe/PayPal and sell verified CSV lists.</div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- INTERACTIVE PLATFORM SHOWCASE TABS (From Reference Image 2) -->
    <section class="section section-bg-subtle" id="showcase">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Interactive Platform</span>
          <h2 class="section-title">Help, Sell, and Support with Driven AI Automation</h2>
          <p class="section-subtitle">A state-of-the-art marketing dashboard designed to streamline every step of your audience communication.</p>
        </div>

        <!-- Tab Selector Pills -->
        <div class="showcase-tabs-nav" role="tablist">
          <button class="tab-btn active" data-tab="tab-builder" role="tab" aria-selected="true">Email Visual Studio</button>
          <button class="tab-btn" data-tab="tab-sms" role="tab" aria-selected="false">SMS &amp; WhatsApp</button>
          <button class="tab-btn" data-tab="tab-ai" role="tab" aria-selected="false">ChatGPT Copilot</button>
          <button class="tab-btn" data-tab="tab-analytics" role="tab" aria-selected="false">Analytics &amp; Heatmaps</button>
        </div>

        <!-- Tab 1: Email Visual Studio -->
        <div class="tab-content active" id="tab-builder">
          <div class="showcase-info">
            <h3>Visual Email Studio with Zero Coding</h3>
            <p>Compose responsive, high-converting email newsletters in a few clicks. Use pre-built modular blocks, image grids, countdown timers, and dynamic tags.</p>
            <ul class="showcase-checklist">
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Fully responsive templates optimized for mobile &amp; desktop</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Dynamic merge tags (Name, Company, Custom Fields)</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> 1-click HTML &amp; MJML import/export</li>
            </ul>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Open Studio</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Try Builder Live</a>
            @endauth
          </div>
          <div class="showcase-media">
            <img src="{{ asset('frontend/argon/assets/img/ui-5.png') }}" alt="Email Designer Interface" width="600" height="380" loading="lazy">
          </div>
        </div>

        <!-- Tab 2: SMS & WhatsApp -->
        <div class="tab-content" id="tab-sms">
          <div class="showcase-info">
            <h3>Omnichannel SMS &amp; WhatsApp Campaigns</h3>
            <p>Connect industry-standard gateways like Twilio, Nexmo, Plivo, and Infobip to broadcast instant flash sales and urgent customer alerts.</p>
            <ul class="showcase-checklist">
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Instant 99% open rates within 180 seconds</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Custom sender IDs &amp; regional phone number routing</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Automated 2-way autoresponders for inbound customer replies</li>
            </ul>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Open SMS Manager</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Launch SMS Campaign</a>
            @endauth
          </div>
          <div class="showcase-media">
            <img src="{{ asset('frontend/argon/assets/img/ui-6.png') }}" alt="SMS Gateway Dashboard" width="600" height="380" loading="lazy">
          </div>
        </div>

        <!-- Tab 3: ChatGPT Copilot -->
        <div class="tab-content" id="tab-ai">
          <div class="showcase-info">
            <h3>OpenAI-Powered Copywriting Engine</h3>
            <p>Never stare at a blank page again. Provide a 1-sentence prompt and let ChatGPT generate 5 subject variations, email bodies, and call-to-actions.</p>
            <ul class="showcase-checklist">
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> High-converting subject line suggestions based on CTR history</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Tone adaptation: Professional, Persuasive, Playful, or Urgent</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Instant multi-language translation for global reach</li>
            </ul>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Use AI Assistant</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Generate AI Copy</a>
            @endauth
          </div>
          <div class="showcase-media">
            <img src="{{ asset('frontend/argon/assets/img/ui-4.png') }}" alt="ChatGPT AI Copywriter" width="600" height="380" loading="lazy">
          </div>
        </div>

        <!-- Tab 4: Analytics & Heatmaps -->
        <div class="tab-content" id="tab-analytics">
          <div class="showcase-info">
            <h3>Deep Real-Time Tracking &amp; Heatmaps</h3>
            <p>Measure real business impact with pinpoint analytics. See who opened, what links they clicked, and optimize future campaigns accordingly.</p>
            <ul class="showcase-checklist">
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Live open rate, click-through, and delivery tracking</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Automated Bounce Shield to protect domain reputation</li>
              <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Device, browser, and geographic engagement breakdown</li>
            </ul>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">View Analytics</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Explore Analytics</a>
            @endauth
          </div>
          <div class="showcase-media">
            <img src="{{ asset('frontend/argon/assets/img/ui-1.png') }}" alt="Live Campaign Analytics" width="600" height="380" loading="lazy">
          </div>
        </div>
      </div>
    </section>

    <!-- 9-GRID COMPREHENSIVE FEATURES (3x3 Grid from Reference Image 1) -->
    <section class="section" id="features">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Feature Suite</span>
          <h2 class="section-title">Everything You Need to Scale Fast</h2>
          <p class="section-subtitle">Engineered to outperform Mailchimp, Klaviyo, and Brevo without the enterprise price tag.</p>
        </div>

        <div class="features-9-grid">
          <!-- Card 1 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            </div>
            <h4>Visual Drag &amp; Drop Designer</h4>
            <p>Compose custom newsletters, headers, CTA buttons, and images with full responsive previews.</p>
          </div>

          <!-- Card 2 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
            </div>
            <h4>Campaign Scheduler &amp; Queue</h4>
            <p>Queue millions of emails to be delivered at the exact optimal local time for highest open rates.</p>
          </div>

          <!-- Card 3 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <h4>Multi-SMTP Load Balancing</h4>
            <p>Route emails dynamically through Amazon SES, Mailgun, Zoho, Gmail, or your own dedicated SMTP.</p>
          </div>

          <!-- Card 4 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <h4>Omnichannel SMS Broadcast</h4>
            <p>Send high-urgency SMS alerts and notifications via Twilio, Nexmo, Plivo, and Infobip gateways.</p>
          </div>

          <!-- Card 5 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
            </div>
            <h4>ChatGPT AI Copywriter</h4>
            <p>Craft irresistible subject lines, personalized intros, and engaging body paragraphs with AI prompts.</p>
          </div>

          <!-- Card 6 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <h4>Automated Drip Sequences</h4>
            <p>Onboard new users, nurture warm leads, and re-engage dormant customers on complete autopilot.</p>
          </div>

          <!-- Card 7 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <h4>CSV Cleaner &amp; Bounce Shield</h4>
            <p>Import unlimited contact lists from CSV, remove invalid emails, and protect sender score reputation.</p>
          </div>

          <!-- Card 8 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <h4>Monetization &amp; SaaS Billing</h4>
            <p>Sell subscriptions to your own clients using built-in Stripe, PayPal, Razorpay &amp; Mollie gateways.</p>
          </div>

          <!-- Card 9 -->
          <div class="feat-item-card">
            <div class="feat-icon-top">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            </div>
            <h4>CSV Marketplace Platform</h4>
            <p>Monetize your verified industry lists by selling CSV datasets directly to other users and businesses.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CONNECTED INTEGRATION ECOSYSTEM (From Reference Image 2 - OutReach) -->
    <section class="section section-bg-subtle" id="integrations">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Integration Hub</span>
          <h2 class="section-title">Effortless Integration, Endless Possibilities</h2>
          <p class="section-subtitle">Seamlessly connect MailWave with 100+ delivery relays, SMS providers, CRM systems, and billing solutions.</p>
        </div>

        <div class="integration-tree-wrapper">
          <!-- Central Hub Node with MailWave Wave Crest -->
          <div class="integration-center-node" title="MailWave Core Engine by Web Assets">
            <img src="{{ asset('frontend/mailwave/mailwave-icon.png') }}" alt="MailWave Icon" width="56" height="56" style="border-radius: 12px; display: block;">
          </div>

          <!-- Orbit Integration Pills -->
          <div class="integration-orbit-nodes">
            <div class="orbit-node">
              <span style="color: #FF9900; font-size: 1.2rem;">☁️</span>
              <span>Amazon SES</span>
            </div>
            <div class="orbit-node">
              <span style="color: #F22F46; font-size: 1.2rem;">💬</span>
              <span>Twilio SMS</span>
            </div>
            <div class="orbit-node">
              <span style="color: #25D366; font-size: 1.2rem;">📱</span>
              <span>WhatsApp API</span>
            </div>
            <div class="orbit-node">
              <span style="color: #EA4335; font-size: 1.2rem;">✉️</span>
              <span>Gmail / Google</span>
            </div>
            <div class="orbit-node">
              <span style="color: #F85A40; font-size: 1.2rem;">🚀</span>
              <span>Mailgun</span>
            </div>
            <div class="orbit-node">
              <span style="color: #10A37F; font-size: 1.2rem;">🤖</span>
              <span>OpenAI GPT-4o</span>
            </div>
            <div class="orbit-node">
              <span style="color: #635BFF; font-size: 1.2rem;">💳</span>
              <span>Stripe</span>
            </div>
            <div class="orbit-node">
              <span style="color: #003087; font-size: 1.2rem;">🅿️</span>
              <span>PayPal</span>
            </div>
            <div class="orbit-node">
              <span style="color: #FF4A00; font-size: 1.2rem;">⚡</span>
              <span>Zapier Webhooks</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4-STEP WORKFLOW (From Reference Image 1) -->
    <section class="section" id="workflow">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Workflow</span>
          <h2 class="section-title">Launch Your Campaign in 4 Simple Steps</h2>
          <p class="section-subtitle">From zero to high-converting delivery in less than 5 minutes with MailWave.</p>
        </div>

        <div class="workflow-grid">
          <div class="workflow-card">
            <div class="step-num-badge">1</div>
            <h4>Connect Your Gateway</h4>
            <p>Link your Amazon SES, Mailgun, or Twilio API keys in 60 seconds using intuitive wizards.</p>
          </div>

          <div class="workflow-card">
            <div class="step-num-badge">2</div>
            <h4>Import &amp; Clean Contacts</h4>
            <p>Upload CSV lists. MailWave automatically filters duplicate contacts and invalid syntax.</p>
          </div>

          <div class="workflow-card">
            <div class="step-num-badge">3</div>
            <h4>Design with AI Copilot</h4>
            <p>Pick a template or build from scratch. Let ChatGPT generate click-worthy subject lines.</p>
          </div>

          <div class="workflow-card">
            <div class="step-num-badge">4</div>
            <h4>Dispatch &amp; Track Live</h4>
            <p>Schedule your queue and watch real-time open rates, click heatmaps, and delivery metrics.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- COMPETITOR COMPARISON TABLE (From Reference Image 1) -->
    <section class="section section-bg-subtle" id="compare">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Head-to-Head Comparison</span>
          <h2 class="section-title">Why Marketers Switch to MailWave</h2>
          <p class="section-subtitle">See how MailWave stacks up against legacy tools like Mailchimp and Brevo.</p>
        </div>

        <div class="table-scroll-hint">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Scroll / swipe horizontally to compare all platforms</span>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </div>

        <div class="table-responsive">
          <table class="comparison-table">
            <thead>
              <tr>
                <th>Feature / Capability</th>
                <th class="td-highlight" style="color: #15803D;">MailWave</th>
                <th>Mailchimp</th>
                <th>Brevo (Sendinblue)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="td-feature">Pricing for 50,000 Contacts</td>
                <td class="td-highlight">$49 / mo (Flat rate)</td>
                <td>$350+ / mo (Scales up)</td>
                <td>$289+ / mo</td>
              </tr>
              <tr>
                <td class="td-feature">Self-Hosted Data Ownership</td>
                <td class="td-highlight">100% Full Ownership</td>
                <td>No (Cloud Locked)</td>
                <td>No (Cloud Locked)</td>
              </tr>
              <tr>
                <td class="td-feature">Multi-SMTP Load Balancing</td>
                <td class="td-highlight">Included (SES, Zoho, etc.)</td>
                <td>No</td>
                <td>No</td>
              </tr>
              <tr>
                <td class="td-feature">Native SMS &amp; WhatsApp Gateways</td>
                <td class="td-highlight">Included (Twilio, Plivo)</td>
                <td>Extra expensive add-on</td>
                <td>SMS Only (Pricey)</td>
              </tr>
              <tr>
                <td class="td-feature">Integrated ChatGPT AI Writer</td>
                <td class="td-highlight">Included (Unlimited)</td>
                <td>Limited / Extra</td>
                <td>No</td>
              </tr>
              <tr>
                <td class="td-feature">SaaS Client Billing Multi-Tenancy</td>
                <td class="td-highlight">Included (Sell to clients)</td>
                <td>Enterprise Only ($$$)</td>
                <td>No</td>
              </tr>
              <tr>
                <td class="td-feature">CSV Audience Marketplace</td>
                <td class="td-highlight">Included (Monetize lists)</td>
                <td>No</td>
                <td>No</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- PRICING SECTION (Clean Light Theme Cards) -->
    <section class="section" id="pricing">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Predictable Plans</span>
          <h2 class="section-title">Simple, Transparent Pricing</h2>
          <p class="section-subtitle">No hidden per-contact fees. Choose the plan that accelerates your marketing growth.</p>
        </div>

        <!-- Monthly / Annual Switcher -->
        <div class="billing-switcher">
          <span class="switch-label active" id="label-monthly">Monthly Billing</span>
          <div class="switch-toggle" id="billing-toggle" role="button" aria-label="Toggle annual billing"></div>
          <span class="switch-label" id="label-annual">Annual Billing</span>
          <span class="discount-pill">Save 20%</span>
        </div>

        <div class="pricing-grid">
          <!-- Starter Plan -->
          <div class="pricing-card">
            <h3 class="plan-title">Starter</h3>
            <p class="plan-desc">For indie makers, startups, and solo entrepreneurs.</p>
            <div class="price-box">
              <span class="price-currency">$</span>
              <span class="price-val" id="price-starter">19</span>
              <span class="price-period">/month</span>
            </div>
            <ul class="pricing-features-list">
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Up to 25,000 Contacts</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Visual Drag &amp; Drop Builder</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Amazon SES &amp; Gmail SMTP</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>SMS Broadcast Gateway</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Basic Open &amp; Click Tracking</span>
              </li>
            </ul>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-outline" style="width: 100%;">Access Account</a>
            @else
              @if (Route::has('user_register'))
                <a href="{{ route('user_register') }}" class="btn btn-outline" style="width: 100%;">Start Free Trial</a>
              @else
                <a href="{{ route('login') }}" class="btn btn-outline" style="width: 100%;">Sign In</a>
              @endif
            @endauth
          </div>

          <!-- Pro Plan (Most Popular) -->
          <div class="pricing-card popular">
            <div class="popular-ribbon">Most Popular</div>
            <h3 class="plan-title">Pro Scale</h3>
            <p class="plan-desc">For growing brands, ecommerce stores, and marketers.</p>
            <div class="price-box">
              <span class="price-currency">$</span>
              <span class="price-val" id="price-pro">49</span>
              <span class="price-period">/month</span>
            </div>
            <ul class="pricing-features-list">
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Unlimited</strong> Contacts &amp; Subscribers</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Multi-SMTP Load Balancing</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>ChatGPT Copilot</strong> Copywriting</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Automated Drip Sequences</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Real-Time Bounce Shield &amp; Heatmaps</span>
              </li>
            </ul>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary" style="width: 100%;">Upgrade Plan</a>
            @else
              @if (Route::has('user_register'))
                <a href="{{ route('user_register') }}" class="btn btn-primary" style="width: 100%;">Get Pro Scale</a>
              @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">Get Started</a>
              @endif
            @endauth
          </div>

          <!-- Enterprise Plan -->
          <div class="pricing-card">
            <h3 class="plan-title">Agency / SaaS</h3>
            <p class="plan-desc">For agencies, marketing consultancies, and resellers.</p>
            <div class="price-box">
              <span class="price-currency">$</span>
              <span class="price-val" id="price-enterprise">149</span>
              <span class="price-period">/month</span>
            </div>
            <ul class="pricing-features-list">
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Everything in Pro Scale</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span><strong>Multi-Tenant Client Portals</strong></span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Client Subscription Billing (Stripe)</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>CSV Audience Marketplace Access</span>
              </li>
              <li class="pricing-feature-row">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Priority 24/7 Dedicated Support</span>
              </li>
            </ul>
            <a href="{{ Route::has('contact.create') ? route('contact.create') : url('/contact') }}" class="btn btn-outline" style="width: 100%;">Contact Enterprise</a>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS (From Reference Image 2 - OutReach) -->
    <section class="section section-bg-subtle" id="reviews">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Client Reviews</span>
          <h2 class="section-title">1.2k+ Clients Love Us</h2>
          <p class="section-subtitle">Discover how business owners use MailWave by Web Assets to scale email deliverability and save thousands.</p>
        </div>

        <div class="testimonials-grid">
          <div class="testimonial-card">
            <div class="stars-row">★★★★★</div>
            <p class="testimonial-quote">"MailWave has completely eliminated our recurring Mailchimp invoice, which was over $650/month. We switched to Amazon SES via MailWave and our deliverability actually increased to 99.4%!"</p>
            <div class="testimonial-author">
              <img src="{{ asset('frontend/images/users/01.jpg') }}" alt="Sarah Jenkins" class="author-avatar" width="46" height="46" loading="lazy">
              <div class="author-info">
                <h5>Sarah Jenkins</h5>
                <p>Growth Director, GrowthForge</p>
              </div>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="stars-row">★★★★★</div>
            <p class="testimonial-quote">"The built-in ChatGPT copilot in MailWave is unbelievable. It drafts 5 variations of subject lines in seconds and gives us open rates we couldn't achieve with manual copywriting."</p>
            <div class="testimonial-author">
              <img src="{{ asset('frontend/images/users/02.jpg') }}" alt="Marcus Zhao" class="author-avatar" width="46" height="46" loading="lazy">
              <div class="author-info">
                <h5>Marcus Zhao</h5>
                <p>Founder, Velocity Commerce</p>
              </div>
            </div>
          </div>

          <div class="testimonial-card">
            <div class="stars-row">★★★★★</div>
            <p class="testimonial-quote">"Having both bulk email and Twilio SMS in a single unified system has saved our support team hundreds of hours. Plus, we now charge our sub-clients using the SaaS feature!"</p>
            <div class="testimonial-author">
              <img src="{{ asset('frontend/images/users/03.jpg') }}" alt="Elena Rostova" class="author-avatar" width="46" height="46" loading="lazy">
              <div class="author-info">
                <h5>Elena Rostova</h5>
                <p>Managing Director, PixelBrand Media</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FREQUENTLY ASKED QUESTIONS (Accordion from Reference Images) -->
    <section class="section" id="faq">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Got Questions?</span>
          <h2 class="section-title">Questions &amp; Answers</h2>
          <p class="section-subtitle">Everything you need to know about setting up, routing, and scaling with MailWave.</p>
        </div>

        <div class="faq-box">
          <div class="faq-item">
            <div class="faq-question">
              <span>Can I really send unlimited emails without per-subscriber penalties with MailWave?</span>
              <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
              Yes! MailWave breaks free from the extortionate pricing model of legacy SaaS. You connect your own ultra-affordable delivery relays (Amazon SES, Mailgun, SMTP, Gmail) and pay pennies directly to the provider ($1 per 10,000 emails on Amazon SES) without MailWave ever capping your audience size.
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <span>What SMTP and SMS gateways are supported out of the box in MailWave?</span>
              <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
              MailWave comes pre-integrated with Amazon SES, Mailgun, SendGrid, Gmail Workspace, Zoho Mail, ElasticEmail, and custom SMTP servers. For SMS and WhatsApp, we natively support Twilio, Nexmo/Vonage, Plivo, Infobip, and Viber.
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <span>Do I need coding or technical skills to operate MailWave?</span>
              <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
              None at all. The platform includes a visual drag-and-drop newsletter builder, one-click gateway setup wizards, and responsive email templates so any marketer or business owner can launch campaigns right away.
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <span>How does the ChatGPT AI copywriter work in MailWave?</span>
              <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
              Our native OpenAI integration connects directly into the campaign composer. You enter a topic or target audience, and ChatGPT crafts persuasive subject lines, intro paragraphs, and body copy optimized for conversions in seconds.
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <span>Can I white-label MailWave and resell it to my clients?</span>
              <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
              Yes! The Agency &amp; SaaS plan allows you to create separate sub-accounts for your clients, set quota limits, and automatically charge them recurring subscription fees through Stripe or PayPal.
            </div>
          </div>

          <div class="faq-item">
            <div class="faq-question">
              <span>How easy is it to import my existing contact lists?</span>
              <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
              You can upload your subscriber lists instantly from standard CSV or Excel files. MailWave automatically maps your custom fields and runs an automated syntax and bounce filter to keep your sender score clean.
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- BOTTOM CONVERSION CTA BANNER (From Reference Image 2 - OutReach) -->
    <section class="section" style="padding-top: 0;" id="cta">
      <div class="container">
        <div class="cta-light-banner">
          <h2>Get Your Free MailWave Trial Now!</h2>
          <p>Join thousands of high-performing marketing teams saving 90% on their email and SMS marketing bills with superior inbox deliverability.</p>
          <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
            @else
              @if (Route::has('user_register'))
                <a href="{{ route('user_register') }}" class="btn btn-primary btn-lg" id="bottom-cta-btn">
                  <span>Start Free 14-Day Trial</span>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
              @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Get Started</a>
              @endif
            @endauth
            <a href="{{ url('/contact') }}" class="btn btn-outline btn-lg">Schedule Live Walkthrough</a>
          </div>
          <div style="margin-top: 22px; font-size: 0.875rem; color: #4338CA; font-weight: 600;">
            ✓ Instant Activation • No Credit Card Required • Full Feature Access • Powered by Web Assets
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ENTERPRISE FOOTER (Dark Navy from Reference Image 1) -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <img src="{{ asset('frontend/mailwave/mailwave-logo.png') }}" alt="MailWave by Web Assets" class="footer-logo-img" width="180" height="60">
          <p>The unified AI-driven Email &amp; SMS marketing automation platform designed for modern SaaS, ecommerce, and scaling agencies. A Web Assets Technology product.</p>
        </div>

        <div class="footer-col">
          <h5>Platform</h5>
          <ul class="footer-links">
            <li><a href="#features">Visual Email Builder</a></li>
            <li><a href="#features">SMS &amp; WhatsApp</a></li>
            <li><a href="#features">ChatGPT Copywriter</a></li>
            <li><a href="#features">Bounce Shield</a></li>
            <li><a href="#pricing">Pricing Plans</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>Gateways</h5>
          <ul class="footer-links">
            <li><a href="#integrations">Amazon SES</a></li>
            <li><a href="#integrations">Twilio SMS</a></li>
            <li><a href="#integrations">Mailgun Relay</a></li>
            <li><a href="#integrations">Gmail / Google Workspace</a></li>
            <li><a href="#integrations">Stripe &amp; PayPal</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>Company &amp; Legal</h5>
          <ul class="footer-links">
            <li><a href="{{ route('login') }}">User Login</a></li>
            @if (Route::has('user_register'))
              <li><a href="{{ route('user_register') }}">Create Account</a></li>
            @endif
            <li><a href="{{ url('/page/privacy-policy') }}">Privacy Policy</a></li>
            <li><a href="{{ url('/page/terms-of-service') }}">Terms of Service</a></li>
            <li><a href="{{ url('/contact') }}">Support Center</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <div>&copy; {{ date('Y') }} MailWave. All rights reserved. Web Assets Technology Infrastructure.</div>
        <div style="display: flex; gap: 24px;">
          <a href="{{ url('/page/privacy-policy') }}" style="color: #94A3B8;">Privacy</a>
          <a href="{{ url('/page/terms-of-service') }}" style="color: #94A3B8;">Terms</a>
          <a href="{{ url('/contact') }}" style="color: #94A3B8;">Security</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('frontend/mailwave/main.js') }}"></script>
</body>
</html>
