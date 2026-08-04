<?php
$company = [
    'name' => 'SEVEN G BINA SDN BHD',
    'short' => '7GB',
    'ssm' => '202301028045 / 1521968-A',
    'phone' => '+603.2774.5253',
    'phone_link' => '+60327745253',
    'email' => 'sevgb.my@gmail.com',
    'website' => '7gb.my',
    'address' => 'Level 19, Boutique Office 1 (BO1-C), Menara 2, No. 3, Jalan Bangsar, KL Eco City, 59200 Kuala Lumpur, Malaysia',
    'cidb_registration' => '0120241015-SL144770',
    'cidb_grade' => 'G5',
    'cidb_categories' => 'B, CE, ME',
];

$services = [
    ['title' => 'General Building Works', 'copy' => 'Coordinated building works for commercial, industrial, and institutional project requirements.'],
    ['title' => 'Civil & Structural Engineering', 'copy' => 'Engineering-led delivery for structures, strengthening, concrete works, and site infrastructure.'],
    ['title' => 'Mechanical & Electrical Engineering', 'copy' => 'Practical M&E support for building systems, process spaces, and operating facilities.'],
    ['title' => 'Project Management Consultancy', 'copy' => 'Planning, coordination, documentation, and supervision for controlled project delivery.'],
    ['title' => 'Facility Maintenance', 'copy' => 'Responsive maintenance support focused on continuity, safety, and asset condition.'],
    ['title' => 'Infrastructure', 'copy' => 'Site infrastructure works connecting access, utilities, drainage, and civil requirements.'],
    ['title' => 'Road & Drainage', 'copy' => 'Road improvement, drainage upgrading, and related external works for active sites.'],
    ['title' => 'Renovation', 'copy' => 'Refurbishment and fit-out works for offices, facilities, and commercial premises.'],
    ['title' => 'Green Technology Engineering', 'copy' => 'Polymer-based and energy-saving engineering solutions for modern project demands.'],
];

$projects = [
    ['title' => '82 Rening', 'place' => 'Lebuhraya Bukit Jalil', 'image' => 'assets/images/photo_2026-07-23_03-57-48.jpg'],
    ['title' => 'Drain Upgrading Works', 'place' => 'Bukit Naga, Shah Alam', 'image' => 'assets/images/photo_2026-07-23_03-58-11.jpg'],
    ['title' => 'Design & Build Resort-Type Villas', 'place' => 'Batang Kali', 'image' => 'assets/images/photo_2026-07-23_03-58-17.jpg'],
    ['title' => 'AirAsia-Teleport Process Center', 'place' => 'KLIA FCZ Sepang', 'image' => 'assets/images/photo_2026-07-23_03-58-25.jpg'],
    ['title' => 'Indoor Futsal Court Construction', 'place' => 'Wisma FAM, Kelana Jaya', 'image' => 'assets/images/photo_2026-07-23_03-59-09.jpg'],
    ['title' => 'Floor Slab Reconstruction', 'place' => 'Shopee Express, Kepong', 'image' => 'assets/images/photo_2026-07-23_03-59-13.jpg'],
];

// ── Dynamic media loader ────────────────────────────────────────────────────
// Images already used in the hero, about, or showcase sections are excluded from the gallery.
$_heroImages = [
    'photo_2026-07-23_03-59-43.jpg',
    'photo_2026-07-23_04-00-08.jpg',
    'photo_2026-07-23_03-57-48.jpg',
    'photo_2026-07-23_03-58-11.jpg',
    'photo_2026-07-23_03-58-17.jpg',
    'photo_2026-07-23_03-58-25.jpg',
    'photo_2026-07-23_03-59-09.jpg',
    'photo_2026-07-23_03-59-13.jpg',
];

// Glob all supported image formats from the images directory.
$_imageDir = __DIR__ . '/assets/images/';
$_imageExts = ['jpg', 'jpeg', 'png', 'webp'];
$_rawImages = [];
foreach ($_imageExts as $_ext) {
    $_rawImages = array_merge(
        $_rawImages,
        glob($_imageDir . '*.' . $_ext) ?: [],
        glob($_imageDir . '*.' . strtoupper($_ext)) ?: []
    );
}
sort($_rawImages);

// Masonry size classes cycle – keeps visual rhythm without manual assignment.
$_masonrySizes = ['masonry-tall', '', 'masonry-wide', '', 'masonry-tall', ''];
$_sizeIndex = 0;

$galleryImages = [];
foreach ($_rawImages as $_fullPath) {
    $_filename = basename($_fullPath);
    if (in_array($_filename, $_heroImages, true)) {
        continue; // skip images already used elsewhere on the page
    }
    // Humanise: strip extension, replace hyphens/underscores with spaces, title-case.
    $_caption = ucwords(str_replace(['-', '_'], ' ', pathinfo($_filename, PATHINFO_FILENAME)));
    $galleryImages[] = [
        'title' => $_caption,
        'image' => 'assets/images/' . $_filename,
        'size' => $_masonrySizes[$_sizeIndex % count($_masonrySizes)],
    ];
    $_sizeIndex++;
}

// Glob all supported video formats from the videos directory.
$_videoDir = __DIR__ . '/assets/videos/';
$_videoExts = ['mp4', 'webm', 'mov'];
$_rawVideos = [];
foreach ($_videoExts as $_ext) {
    $_rawVideos = array_merge(
        $_rawVideos,
        glob($_videoDir . '*.' . $_ext) ?: [],
        glob($_videoDir . '*.' . strtoupper($_ext)) ?: []
    );
}
sort($_rawVideos);

// MIME type map for correct <source type="…"> attributes.
$_videoMime = [
    'mp4' => 'video/mp4',
    'webm' => 'video/webm',
    'mov' => 'video/quicktime',
];

$galleryVideos = [];
foreach ($_rawVideos as $_fullPath) {
    $_filename = basename($_fullPath);
    $_ext = strtolower(pathinfo($_filename, PATHINFO_EXTENSION));
    $_caption = ucwords(str_replace(['-', '_'], ' ', pathinfo($_filename, PATHINFO_FILENAME)));
    $galleryVideos[] = [
        'title' => $_caption,
        'video' => 'assets/videos/' . $_filename,
        'mime' => $_videoMime[$_ext] ?? 'video/mp4',
    ];
}
// ── End dynamic media loader ─────────────────────────────────────────────────

$proof = [
    ['number' => 'G5', 'label' => 'CIDB contractor grade', 'target' => '5'],
    ['number' => '9', 'label' => 'Core service areas', 'target' => '9'],
    ['number' => '3', 'label' => 'CIDB categories', 'target' => '3'],
];

$pageTitle = $company['name'] . ' | Construction & Engineering Company Malaysia';
$pageDescription = 'SEVEN G BINA SDN BHD is a CIDB certified Malaysian construction and engineering company based in KL Eco City, Kuala Lumpur.';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords"
        content="SEVEN G BINA, 7GB, Malaysian construction company, CIDB G5 contractor, civil engineering, structural engineering, M&E engineering, facility maintenance, infrastructure works, road drainage works, Kuala Lumpur contractor">
    <meta name="author" content="<?php echo htmlspecialchars($company['name']); ?>">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://7gb.my">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($company['name']); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#0F8F8C">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#0F8F8C',
                        brandInk: '#071318',
                        brandMist: '#DDF3F1',
                        brandLine: 'rgba(255, 255, 255, 0.14)'
                    },
                    fontFamily: {
                        display: ['ui-sans-serif', 'system-ui', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="font-display antialiased">
    <div class="page-aurora" aria-hidden="true"></div>
    <div class="page-grid" aria-hidden="true"></div>
    <div class="top-sentinel" aria-hidden="true"></div>

    <!-- Header / Navigation -->
    <header class="site-header fixed left-0 right-0 top-0 z-40 px-4 pt-4">
        <nav class="nav-shell mx-auto flex h-16 max-w-7xl items-center justify-between rounded-2xl px-4 sm:px-6"
            aria-label="Main navigation">
            <a href="#home" class="nav-brand magnetic-target flex items-center gap-3"
                aria-label="<?php echo htmlspecialchars($company['name']); ?> home">
                <span
                    class="brand-mark flex h-10 w-10 items-center justify-center rounded-xl text-sm font-black tracking-tight text-white">7GB</span>
                <span class="hidden leading-tight sm:block">
                    <span
                        class="block text-sm font-bold tracking-tight text-white"><?php echo htmlspecialchars($company['name']); ?></span>
                    <span class="block text-[11px] font-medium text-white/55">CIDB Certified Contractor</span>
                </span>
            </a>

            <div class="hidden items-center gap-6 text-sm font-semibold text-white/70 lg:flex">
                <a class="nav-link active" href="#home">Home</a>
                <a class="nav-link" href="#about">About</a>
                <a class="nav-link" href="#services">Services</a>
                <a class="nav-link" href="#projects">Projects</a>
                <a class="nav-link" href="#media">Media</a>
                <a class="nav-link" href="#contact">Contact</a>
            </div>

            <a href="#contact" class="btn btn-primary magnetic-target whitespace-nowrap">
                Get a Quote
            </a>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="home" class="hero-section relative min-h-[100dvh] overflow-hidden px-4 pb-16 pt-24 sm:pt-28">
            <div class="hero-media" aria-hidden="true">
                <img src="assets/images/photo_2026-07-23_03-59-43.jpg" alt="" loading="eager">
            </div>
            <div class="hero-scrim" aria-hidden="true"></div>

            <div
                class="relative mx-auto grid min-h-[calc(100dvh-7rem)] max-w-7xl items-center gap-10 lg:grid-cols-[1.45fr_0.85fr]">
                <div class="hero-copy max-w-3xl">
                    <p class="kicker reveal-stagger">Malaysian Construction & Engineering</p>
                    <h1 class="hero-title reveal-stagger">
                        Building With Precision
                    </h1>
                    <p class="hero-lead reveal-stagger">
                        Reliable building, infrastructure, M&E, and facility solutions across Malaysia.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row reveal-stagger">
                        <a href="#contact" class="btn btn-primary magnetic-target justify-center">Get a Quote</a>
                        <a href="#projects" class="btn btn-secondary magnetic-target justify-center">View Projects</a>
                    </div>
                </div>

                <div class="hero-proof relative grid gap-4 justify-self-end">
                    <?php foreach ($proof as $item): ?>
                        <article class="glass-card hero-stat float-card" data-parallax="0.08">
                            <p class="stat-number">
                                <?php if (is_numeric($item['number'])): ?>
                                    <span data-counter data-target="<?php echo htmlspecialchars($item['target']); ?>">0</span>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($item['number']); ?>
                                <?php endif; ?>
                            </p>
                            <p class="stat-label"><?php echo htmlspecialchars($item['label']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="cinema-section px-4 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="section-intro reveal fade-up">
                    <h2>A Kuala Lumpur contractor built for disciplined delivery.</h2>
                    <p>
                        Seven G Bina is a Malaysian registered construction and engineering company based in KL Eco
                        City. The company supports clients through building works, engineering services, facility
                        maintenance, infrastructure, renovation, and green technology engineering.
                    </p>
                </div>

                <div class="about-layout mt-14 grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
                    <figure class="media-frame reveal fade-left parallax-media">
                        <img src="assets/images/photo_2026-07-23_04-00-08.jpg"
                            alt="Seven G Bina construction site works" loading="lazy">
                    </figure>
                    <article class="glass-card reveal fade-right p-7 sm:p-9">
                        <h3 class="card-title">Certified capability</h3>
                        <p class="mt-5 leading-8 text-white/66">
                            Seven G Bina is a CIDB certified contractor with Grade G5 registration and categories B, CE,
                            and ME. That certification supports a practical operating model: clear scope, accountable
                            coordination, and professional project delivery.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="cinema-section px-4 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="section-intro reveal fade-up">
                    <h2>Services shaped around real site conditions.</h2>
                    <p>
                        Each discipline supports planning, execution, maintenance, and long-term asset performance
                        without adding unnecessary complexity.
                    </p>
                </div>

                <div class="services-grid mt-12 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <?php foreach ($services as $index => $service): ?>
                        <article class="service-card glass-card reveal zoom-in magnetic-card"
                            style="--delay: <?php echo ($index % 6) * 70; ?>ms">
                            <span
                                class="service-index"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></span>
                            <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                            <p><?php echo htmlspecialchars($service['copy']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="cinema-section projects-section px-4 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="section-intro reveal fade-up">
                    <p class="kicker">Project Showcase</p>
                    <h2>Selected work across buildings, roads, drainage, and facilities.</h2>
                </div>

                <div class="project-grid mt-14 grid gap-5 lg:grid-cols-4 lg:auto-rows-[280px]">
                    <?php foreach ($projects as $index => $project): ?>
                        <?php $span = $index === 0 ? 'lg:col-span-2 lg:row-span-2' : ($index === 3 ? 'lg:col-span-2' : ''); ?>
                        <article class="project-card reveal fade-up magnetic-card <?php echo $span; ?>"
                            style="--delay: <?php echo ($index % 4) * 90; ?>ms">
                            <img src="<?php echo htmlspecialchars($project['image']); ?>"
                                alt="<?php echo htmlspecialchars($project['title'] . ' at ' . $project['place']); ?>"
                                loading="lazy">
                            <div class="project-card-shade"></div>
                            <div class="project-card-content">
                                <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p><?php echo htmlspecialchars($project['place']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="cinema-section px-4 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="choice-panel glass-card reveal blur-in">
                    <div class="section-intro compact">
                        <h2>Project control, from scope to site.</h2>
                        <p>
                            The company combines certified contracting capability with grounded coordination,
                            engineering judgment, and reliable workmanship.
                        </p>
                    </div>

                    <div class="choice-grid mt-10 grid gap-4 md:grid-cols-2">
                        <article class="choice-item reveal fade-up">
                            <h3>CIDB Certified Contractor</h3>
                            <p>G5 registration with B, CE, and ME categories for qualified project participation.</p>
                        </article>
                        <article class="choice-item reveal fade-up">
                            <h3>Engineering-Led Solutions</h3>
                            <p>Technical input across civil, structural, mechanical, electrical, and green technology
                                scopes.</p>
                        </article>
                        <article class="choice-item reveal fade-up">
                            <h3>On-Time Project Delivery</h3>
                            <p>Project planning and coordination focused on practical milestones and site readiness.</p>
                        </article>
                        <article class="choice-item reveal fade-up">
                            <h3>Quality Workmanship</h3>
                            <p>Clear supervision, reliable execution, and attention to the finish of the work.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <!-- Media Gallery -->
        <section id="media" class="cinema-section px-4 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="section-intro reveal fade-up">
                    <p class="kicker">Visual Record</p>
                    <h2>Crafted with Precision</h2>
                    <p>
                        A visual record of our structural execution, civil engineering works, and site operations across
                        Malaysia.
                    </p>
                </div>

                <!-- Responsive CSS Grid Gallery -->
                <div class="media-grid mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if (!empty($galleryImages)): ?>
                        <?php foreach ($galleryImages as $index => $image): ?>
                            <figure class="media-grid-card glass-card reveal zoom-in magnetic-card"
                                data-lightbox-src="<?php echo htmlspecialchars($image['image']); ?>"
                                data-lightbox-caption="<?php echo htmlspecialchars($image['title']); ?>"
                                data-lightbox-index="<?php echo $index; ?>" role="button" tabindex="0"
                                aria-label="Open <?php echo htmlspecialchars($image['title']); ?> in lightbox">
                                <div class="media-grid-image-wrap">
                                    <img src="<?php echo htmlspecialchars($image['image']); ?>"
                                        alt="<?php echo htmlspecialchars($image['title']); ?>" loading="lazy"
                                        class="media-grid-img">
                                </div>
                                <figcaption class="media-grid-overlay">
                                    <div class="media-grid-caption-content">
                                        <span class="media-grid-badge">Site Asset</span>
                                        <span class="media-grid-title"><?php echo htmlspecialchars($image['title']); ?></span>
                                    </div>
                                    <span class="media-grid-zoom-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8" />
                                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                            <line x1="11" y1="8" x2="11" y2="14" />
                                            <line x1="8" y1="11" x2="14" y2="11" />
                                        </svg>
                                    </span>
                                </figcaption>
                            </figure>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-white/40 text-sm sm:col-span-2 lg:col-span-3">No images found in assets/images/.</p>
                    <?php endif; ?>
                </div>

                <!-- Video Showcase Grid -->
                <div class="video-grid mt-12 grid gap-6 lg:grid-cols-2">
                    <?php if (!empty($galleryVideos)): ?>
                        <?php foreach ($galleryVideos as $video): ?>
                            <article class="video-card glass-card reveal fade-up magnetic-card">
                                <div class="video-shell">
                                    <video controls preload="metadata">
                                        <source src="<?php echo htmlspecialchars($video['video']); ?>"
                                            type="<?php echo htmlspecialchars($video['mime']); ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div class="video-card-header">
                                    <span class="video-card-badge">Site Motion Record</span>
                                    <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-white/40 text-sm">No videos found in assets/videos/.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Lightbox Modal -->
        <div id="gallery-lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Image lightbox" hidden>
            <div class="lightbox-backdrop"></div>
            <div class="lightbox-shell">
                <button class="lightbox-close" id="lightbox-close-btn" aria-label="Close lightbox">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
                <button class="lightbox-nav lightbox-prev" id="lightbox-prev-btn" aria-label="Previous image">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>
                <button class="lightbox-nav lightbox-next" id="lightbox-next-btn" aria-label="Next image">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>
                <figure class="lightbox-figure">
                    <img id="lightbox-img" src="" alt="" loading="eager">
                    <figcaption id="lightbox-caption"></figcaption>
                </figure>
                <div class="lightbox-counter" id="lightbox-counter"></div>
            </div>
        </div>

        <!-- Certification Section -->
        <section class="cinema-section px-4 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl">
                <div class="cert-panel reveal blur-in">
                    <div class="section-intro compact">
                        <h2>Registered for accountable delivery.</h2>
                        <p>Company and contractor credentials are presented clearly for project enquiries and
                            procurement review.</p>
                    </div>

                    <div class="cert-grid mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div class="cert-card">
                            <p>SSM</p>
                            <strong><?php echo htmlspecialchars($company['ssm']); ?></strong>
                        </div>
                        <div class="cert-card">
                            <p>CIDB Registration</p>
                            <strong><?php echo htmlspecialchars($company['cidb_registration']); ?></strong>
                        </div>
                        <div class="cert-card">
                            <p>CIDB Grade</p>
                            <strong><?php echo htmlspecialchars($company['cidb_grade']); ?></strong>
                        </div>
                        <div class="cert-card">
                            <p>Categories</p>
                            <strong><?php echo htmlspecialchars($company['cidb_categories']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="cinema-section contact-section px-4 py-24 sm:py-32">
            <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[0.82fr_1.18fr]">
                <div class="reveal fade-left">
                    <div class="section-intro compact">
                        <h2>Ready to discuss a project?</h2>
                        <p>Share your scope and the team will respond with the right next step for your enquiry.</p>
                    </div>

                    <address class="contact-list mt-10 not-italic">
                        <p><strong>Address</strong><span><?php echo htmlspecialchars($company['address']); ?></span></p>
                        <p><strong>Phone</strong><a
                                href="tel:<?php echo htmlspecialchars($company['phone_link']); ?>"><?php echo htmlspecialchars($company['phone']); ?></a>
                        </p>
                        <p><strong>Email</strong><a
                                href="mailto:<?php echo htmlspecialchars($company['email']); ?>"><?php echo htmlspecialchars($company['email']); ?></a>
                        </p>
                        <p><strong>Website</strong><a
                                href="https://<?php echo htmlspecialchars($company['website']); ?>"><?php echo htmlspecialchars($company['website']); ?></a>
                        </p>
                    </address>
                </div>

                <form class="contact-form glass-card reveal fade-right"
                    action="mailto:<?php echo htmlspecialchars($company['email']); ?>" method="post"
                    enctype="text/plain">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="field">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" autocomplete="name" required
                                placeholder="Your full name">
                            <small>Required</small>
                        </div>
                        <div class="field">
                            <label for="company">Company</label>
                            <input id="company" name="company" type="text" autocomplete="organization"
                                placeholder="Company name">
                            <small>Optional</small>
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                placeholder="name@company.com">
                            <small>Required</small>
                        </div>
                        <div class="field">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="+60">
                            <small>Optional</small>
                        </div>
                        <div class="field sm:col-span-2">
                            <label for="service">Project type</label>
                            <select id="service" name="service">
                                <option value="">Select a service</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?php echo htmlspecialchars($service['title']); ?>">
                                        <?php echo htmlspecialchars($service['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small>Select the closest scope.</small>
                        </div>
                        <div class="field sm:col-span-2">
                            <label for="message">Project details</label>
                            <textarea id="message" name="message" rows="5" required
                                placeholder="Tell us about your scope, location, timeline, or maintenance requirement."></textarea>
                            <small>Required</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary magnetic-target mt-6 w-full justify-center">
                        Send Enquiry
                    </button>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="site-footer px-4 py-12">
        <div class="mx-auto flex max-w-7xl flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <span
                        class="brand-mark flex h-10 w-10 items-center justify-center rounded-xl text-sm font-black text-white">7GB</span>
                    <div>
                        <p class="text-sm font-black tracking-tight text-white">
                            <?php echo htmlspecialchars($company['name']); ?>
                        </p>
                        <p class="text-xs text-white/50">SSM <?php echo htmlspecialchars($company['ssm']); ?></p>
                    </div>
                </div>
                <p class="mt-6 max-w-[58ch] text-sm leading-7 text-white/58">
                    Construction, engineering, project management, maintenance, infrastructure, renovation, and green
                    technology engineering solutions from Kuala Lumpur.
                </p>
            </div>
            <div class="text-sm leading-7 text-white/58 md:text-right">
                <p><a
                        href="mailto:<?php echo htmlspecialchars($company['email']); ?>"><?php echo htmlspecialchars($company['email']); ?></a>
                </p>
                <p><a
                        href="tel:<?php echo htmlspecialchars($company['phone_link']); ?>"><?php echo htmlspecialchars($company['phone']); ?></a>
                </p>
                <p class="mt-4">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($company['name']); ?>. All
                    rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js" defer></script>
</body>

</html>