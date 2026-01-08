<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MBI Medical Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/landing.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .btn-youtube {
            background-color: #FF0000;
            color: white;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            animation: pulse-red 2s infinite;
        }

        .btn-youtube:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
            background-color: #cc0000;
            color: white;
        }

        .btn-youtube svg {
            fill: currentColor;
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 0, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
            }
        }

        .lp-cta2 {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <header class="lp-navbar">
        <div class="lp-container navbar-inner">
            <a href="/" class="lp-logo">
                <img width="150" src="{{ url('assets/images/logo.png') }}" alt="My Bridge International UK">
            </a>
            <nav class="lp-nav">
                <a href="#home" class="nav-link active">Home</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="#partners" class="nav-link">Partners</a>
                <a href="#contact" class="nav-link">Contact us</a>
            </nav>
            <div class="lp-cta">
                <a href="{{ route('register') }}" class="btn btn-light">Sign up</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
            <button class="lp-menu-btn" id="lpMenuBtn" aria-label="Open menu">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
            </button>
        </div>
    </header>

    <div class="lp-menu" id="lpMobileMenu">
        <div class="lp-container menu-inner">
            <div class="menu-links">
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#partners">Partners</a>
                <a href="#contact">Contact us</a>
            </div>
            <div class="menu-cta">
                <a href="{{ route('register') }}" class="btn btn-light">Sign up</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>

    <main id="home">
        <section class="lp-hero">
            <div class="lp-container hero-inner">
                <div class="hero-content">
                    <div class="pretitle">Empowering Medical Professionals Globally</div>
                    <h1 class="hero-title">Medical<br>Portal</h1>
                    <p class="hero-desc">
                        An interactive platform that fosters communication and collaboration among healthcare experts:
                        Doctors, Nurses, Medical Equipment Manufacturer and Engineers, Drug Manufacturers and Healthcare Assistants
                        providing vital tools, resources, and networking opportunities to keep users informed and connected within
                        the global healthcare ecosystem.
                    </p>

                    <div class="lp-cta2">
                        <!-- <a href="{{ route('register') }}"style='padding:25px'  class="btn btn-light">Sign up</a> -->
                        <a href="{{ route('register') }}" style='padding:25px' class="btn btn-primary">Get Started</a>
                        <a target='_blank' href="https://youtube.com/shorts/stHN6PYVJz0?si=OfM7-QKQDgLjaG6q" target="_blank" style='padding:25px' class="btn btn-youtube">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                            </svg>
                            Watch Tutorial
                        </a>
                    </div>
                </div>
                <div class="hero-media">
                    <div class="shape small">
                        <img src="{{ url('assets/images/landing/head3.png') }}" alt="Hero image">
                    </div>
                    <div class="shape wide">
                        <img src="{{ url('assets/images/landing/head4.png') }}" alt="Hero image">
                    </div>
                    <div class="shape tall">
                        <img src="{{ url('assets/images/landing/head1.png') }}" alt="Hero image">
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-video">
            <div class="lp-container">
                <video class="video-frame" src="{{ url('assets/images/landing/mbi-video.mp4') }}" controls playsinline preload="metadata">
                </video>
            </div>
        </section>

        <section id="services" class="lp-services-intro">
            <div class="lp-container">
                <h2 class="services-title">Our Services</h2>
                <p class="services-sub">
                    Tailored services that harness technology, collaboration, and expertise to improve access to quality healthcare
                    and deliver impactful solutions that empower communities.
                </p>
            </div>
        </section>

        <section class="lp-services-band" style="background:#007aff">
            <div class="lp-container">
                <div class="services-wrap">
                    <img class="services-bg tl" src="{{ url('assets/images/landing/svg1.png') }}" alt="">
                    <img class="services-bg br" src="{{ url('assets/images/landing/svg2.png') }}" alt="">
                    <div class="services-grid">
                        <div class="service-item">
                            <div class="icon">
                                <img src="{{ url('assets/images/landing/serv1.png') }}" width="30" alt="">
                            </div>
                            <div class="title">Telemedicine</div>
                            <div class="desc">
                                Connects patients in remote areas with healthcare professionals, providing access to consultations,
                                diagnoses, and treatment plans without travelling.
                            </div>
                        </div>
                        <div class="service-item">
                            <div class="icon">
                                <img src="{{ url('assets/images/landing/serv2.png') }}" width="30" alt="">
                            </div>
                            <div class="title">Healthcare Management Solutions</div>
                            <div class="desc">
                                Comprehensive solutions for facilities, including EHR systems, hospital management software, and training programs
                                that streamline operations and improve care.
                            </div>
                        </div>
                        <div class="service-item">
                            <div class="icon">
                                <img src="{{ url('assets/images/landing/serv3.png') }}" width="30" alt="">
                            </div>
                            <div class="title">Cross-Border Healthcare Network</div>
                            <div class="desc">
                                Connects hospitals and clinics across countries, enabling resource sharing, specialist consultations,
                                and coordinated emergency response.
                            </div>
                        </div>

                        <div class="service-item">
                            <div class="icon">
                                <img src="{{ url('assets/images/landing/serv4.png') }}" width="30" alt="">
                            </div>
                            <div class="title">Clinical Programs and Certification</div>
                            <div class="desc">
                                Programs to empower professionals to improve skills, stay current with advancements, and provide high‑quality patient care.
                            </div>
                        </div>
                        <div class="service-item">
                            <div class="icon">
                                <img src="{{ url('assets/images/landing/serv5.png') }}" width="30" alt="">
                            </div>
                            <div class="title">Medical Training and Support</div>
                            <div class="desc">
                                Training centers for doctors, nurses, and medical engineers to ensure they are equipped with the latest knowledge and skills.
                            </div>
                        </div>
                        <div class="service-item">
                            <div class="icon">
                                <img src="{{ url('assets/images/landing/serv6.png') }}" width="30" alt="">x
                            </div>
                            <div class="title">Innovative Research</div>
                            <div class="desc">
                                Contribute to clinical research and trials, leading to new treatments and understanding of diseases in underserved regions.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-how">
            <div class="lp-container">
                <div class="how-wrap">
                    <img class="how-bg tl" src="https://via.placeholder.com/160x160?text=BG" alt="">
                    <img class="how-bg br" src="https://via.placeholder.com/160x160?text=BG" alt="">
                    <h2 class="how-title">How to get Started</h2>
                    <p class="how-sub">A simple guide to register and navigate the My Bridge Medical Portal</p>
                    <div class="how-steps">
                        <div class="step left r1">
                            <div class="num">01</div>
                            <div class="stitle">Choose Your Registration Type</div>
                            <div class="sdesc">Sign up as a healthcare professional or Medical Institution.</div>
                            <div class="hline"></div>
                        </div>
                        <div class="step right r2">
                            <div class="num">02</div>
                            <div class="stitle">Select Industry Category</div>
                            <div class="sdesc">Sign up as a healthcare professional or Medical Institution.</div>
                            <div class="hline"></div>
                        </div>
                        <div class="step left r3">
                            <div class="num">03</div>
                            <div class="stitle">Provide Registration Details</div>
                            <div class="sdesc">Fill registration form details and submit profession or Institution licence number.</div>
                            <div class="hline"></div>
                        </div>
                        <div class="step right r4">
                            <div class="num">04</div>
                            <div class="stitle">Verify Your Account</div>
                            <div class="sdesc">Verify registration link and complete profile.</div>
                            <div class="hline"></div>
                        </div>
                        <div class="step left last r5">
                            <div class="num">05</div>
                            <div class="stitle">Access the Medical Dashboard</div>
                            <div class="sdesc">Explore resources and tools on the medical dashboard.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="partners" class="lp-partners">
            <div class="lp-container">
                <h2 class="partners-title">Our Partners</h2>
                <p class="partners-sub">Trusted by the world's leading organizations</p>
                <div class="partners-marquee">
                    <div class="partners-track">
                        <img src="{{ url('assets/images/landing/partner1.png') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/partner2.png') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/partner3.png') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/partner4.png') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/partner5.png') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part11.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part12.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part13.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part14.jpg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part15.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part16.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part17.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part18.svg') }}" alt="Partner">
                        <img src="{{ url('assets/images/landing/part19.png') }}" alt="Partner">
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-map">
            <div class="lp-container">
                <img class="map-frame" src="{{ url('assets/images/landing/map.png') }}" alt="World Map">
            </div>
        </section>

        <section id="contact" class="lp-contact">
            <div class="lp-container">
                <div class="contact-grid">
                    <div>
                        <h2 class="contact-title">Contact Us</h2>
                        <p class="contact-sub">Have questions or ideas to share? Our team is ready to connect and know you.</p>
                        <div class="contact-card">
                            <div class="form-field">
                                <label>NAME</label>
                                <input id="contactName" class="form-input" type="text" placeholder="Last name first">
                            </div>
                            <div class="form-field">
                                <label>EMAIL</label>
                                <input id="contactEmail" class="form-input" type="email" placeholder="something@email">
                            </div>
                            <div class="form-field">
                                <label>PHONE NUMBER</label>
                                <input id="contactPhone" class="form-input" type="text" placeholder="0123456789">
                            </div>
                            <div class="form-field">
                                <label>MESSAGE</label>
                                <textarea id="contactMessage" class="form-textarea" placeholder="Your message here..."></textarea>
                            </div>
                            <div id="contactStatus" style="font-size:14px;color:#4b5563;"></div>
                            <button id="contactSubmit" class="submit-btn" type="button">Submit</button>
                        </div>
                    </div>
                    <div>
                        <h3 class="contact-title">How can we help?</h3>
                        <div class="contact-info">
                            <div class="info-item">
                                <div>
                                    <img src="{{ url('assets/images/landing/location.png') }}" width='50' alt="Phone">
                                </div>
                                <div>
                                    <div class="label">Office Address</div>
                                    <div>167 - 169 Great Portland Street, London, W1W 5PF, UK</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div>
                                    <img src="{{ url('assets/images/landing/call.png') }}" width="50" alt="Phone">

                                </div>
                                <div>
                                    <div class="label">Contact Phone</div>
                                    <div>+44 (0)203 813 9086<br>+44 (0)300 102 1597</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div>
                                    <img src="{{ url('assets/images/landing/email.png') }}" width="50" alt="Email">
                                </div>
                                <div>
                                    <div class="label">Send Us an Email</div>
                                    <div>info@mybridgeinternational.org</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-donate">
            <div class="lp-container">
                <div class="donate-grid">
                    <div class="donate-collage">
                        <img class="img r1c1" src="{{ url('assets/images/landing/head2.png') }}" alt="Donate collage">
                        <img class="img r1c2a" src="{{ url('assets/images/landing/foot1.png') }}" alt="Donate collage">
                        <img class="img r1c2b" src="{{ url('assets/images/landing/foot1.png') }}" alt="Donate collage">
                        <img class="img r1c3" src="{{ url('assets/images/landing/foot3.png') }}" alt="Donate collage">
                    </div>
                    <div class="donate-panel">
                        <h3 class="donate-title">Be the bridge to better healthcare</h3>
                        <p class="donate-sub">
                            Your contribution to My Bridge International is vital in our mission to bridge healthcare access gaps in underserved
                            and developing nations, ensuring more families receive the care they deserve.
                        </p>
                        <a href="#" class="donate-btn">Donate</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="lp-footer">
        <div class="lp-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img class="footer-logo" src="{{ url('assets/images/logo.png') }}" alt="My Bridge International UK">
                    <p class="footer-desc">
                        MY BRIDGE INTERNATIONAL UK (MBI) is a non-governmental / non-profit organisation
                        registered with the United Kingdom Charity Commission on November 5, 2020,
                        Registration No. 1192169.
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="X">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 4l7.5 9L4 20h3l6-7 4.5 7H20l-7-10 7-6h-3l-5.5 6L7 4H4z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook">
                            <svg viewBox="0 0 24 24">
                                <path d="M15 2h-3a5 5 0 0 0-5 5v3H5v4h2v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17" cy="7" r="1" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="footer-title">Company</div>
                    <ul class="footer-links">
                        <li><a href="#">Donate</a></li>
                        <li><a href="#">Partner with us</a></li>
                        <li><a href="/dashboard">Medical Portal</a></li>
                        <li><a href="#services">Services</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-title">Get in touch</div>
                    <ul class="footer-links">
                        <li>167 – 169 Great Portland Street, London, W1W 5PF, UK</li>
                        <li>info@mybridgeinternational.org</li>
                        <li>+44 (0)203 813 9086</li>
                        <li>+44 (0)300 102 1597</li>
                    </ul>
                </div>
                <div>
                    <div class="footer-title">SUBSCRIBE TO OUR NEWSLETTER</div>
                    <form class="footer-subscribe" action="#" method="post">
                        <input class="subscribe-input" type="email" placeholder="Enter your Email">
                        <button class="subscribe-btn" type="button">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
    <script>
        (function() {
            const btn = document.getElementById('lpMenuBtn');
            const menu = document.getElementById('lpMobileMenu');
            if (btn && menu) {
                btn.addEventListener('click', function() {
                    document.body.classList.toggle('menu-open');
                });
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        document.body.classList.remove('menu-open');
                    }
                });
            }
        })();
        (function() {
            const submitBtn = document.getElementById('contactSubmit');
            const nameEl = document.getElementById('contactName');
            const emailEl = document.getElementById('contactEmail');
            const phoneEl = document.getElementById('contactPhone');
            const messageEl = document.getElementById('contactMessage');
            const statusEl = document.getElementById('contactStatus');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            function showStatus(text, ok) {
                if (!statusEl) return;
                statusEl.textContent = text;
                statusEl.style.color = ok ? '#0f766e' : '#b91c1c';
            }

            if (submitBtn) {
                submitBtn.addEventListener('click', async function() {
                    const name = nameEl?.value?.trim() || '';
                    const email = emailEl?.value?.trim() || '';
                    const phone = phoneEl?.value?.trim() || '';
                    const message = messageEl?.value?.trim() || '';

                    if (!name || !email || !message) {
                        showStatus('Please fill in name, email and message', false);
                        return;
                    }

                    submitBtn.disabled = true;
                    showStatus('Submitting...', true);
                    try {
                        const res = await fetch('/v1/mbicontactus', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                name,
                                email,
                                phone,
                                message
                            })
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok && data?.success !== false) {
                            showStatus('Thanks! We will reach out shortly.', true);
                            nameEl.value = '';
                            emailEl.value = '';
                            phoneEl.value = '';
                            messageEl.value = '';
                        } else {
                            const err = data?.message || 'Submission failed. Please try again.';
                            showStatus(err, false);
                        }
                    } catch (e) {
                        showStatus('Network error. Please try again.', false);
                    } finally {
                        submitBtn.disabled = false;
                    }
                });
            }
        })();
    </script>
</body>

</html>