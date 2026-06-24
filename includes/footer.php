<?php
$script_path = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
$project_root = str_replace('\\', '/', dirname(__DIR__));
$relative_path = str_ireplace($project_root, '', $script_path);
$parts = explode('/', trim($relative_path, '/'));
$first_dir = strtolower($parts[0] ?? '');
$script_name = basename($_SERVER['SCRIPT_FILENAME']);

$show_footer_content = ($first_dir !== 'admin' && $first_dir !== 'siswa' && $script_name !== 'login.php' && $script_name !== 'register.php');
?>
<?php if ($show_footer_content): ?>
<footer id="kontak" class="bg-dark text-white pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="mb-4 d-flex align-items-center" style="height: 30px;">
                    <div class="bg-maroon text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="fas fa-graduation-cap" style="font-size: 0.8rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-white fs-5">SI<span class="text-maroon">MEKS</span></h5>
                </div>
                <p class="text-white mb-4">Sistem Informasi Manajemen Ekstrakurikuler SMAN 2 Sukatani. Wadah pengembangan bakat, minat, dan karakter siswa di era digital.</p>
                
                <h6 class="text-white fw-bold mb-3 small text-uppercase" style="letter-spacing: 1px;">Ikuti Kami</h6>
                <div class="mt-3">
                    <div class="mb-2">
                        <a href="https://www.instagram.com/sman2sukataniofficial/" target="_blank" class="text-white text-decoration-none hover-maroon d-flex align-items-center transition-all">
                            <i class="fab fa-instagram fs-5 me-2 text-maroon" style="width: 25px;"></i>
                            <span class="small">@sman2sukataniofficial</span>
                        </a>
                    </div>
                    <div class="mb-2">
                        <a href="https://www.facebook.com/profile.php?id=100070101236958" target="_blank" class="text-white text-decoration-none hover-maroon d-flex align-items-center transition-all">
                            <i class="fab fa-facebook fs-5 me-2 text-maroon" style="width: 25px;"></i>
                            <span class="small">SMAN 2 Sukatani Official</span>
                        </a>
                    </div>
                    <div class="mb-2">
                        <a href="https://www.youtube.com/@sman2sukataniofficial302" target="_blank" class="text-white text-decoration-none hover-maroon d-flex align-items-center transition-all">
                            <i class="fab fa-youtube fs-5 me-2 text-maroon" style="width: 25px;"></i>
                            <span class="small">SMAN 2 Sukatani Official</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4 text-white" style="height: 30px; line-height: 30px;">Tautan Cepat</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="index.php" class="text-white text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="#ekskul" class="text-white text-decoration-none">Ekskul</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Pengumuman</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Tentang Kami</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-4 text-white" style="height: 30px; line-height: 30px;">Hubungi Kami</h5>
                <ul class="list-unstyled text-white">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="fas fa-map-marker-alt me-3 text-maroon" style="width: 20px; text-align: center; line-height: 1.5;"></i>
                        <a href="https://maps.app.goo.gl/JLTpRLBqtPr96L3G7" target="_blank" class="text-white text-decoration-none hover-maroon transition-all">
                            <span>Jl. Raya Sasak Bali Sukamanah, Sukamanah, Kec. Sukatani, Kabupaten Bekasi, Jawa Barat 17630</span>
                        </a>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fas fa-phone-alt me-3 text-maroon" style="width: 20px; text-align: center;"></i>
                        <a href="tel:0218901234" class="text-white text-decoration-none hover-maroon transition-all">
                            <span>(021) 8901234</span>
                        </a>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fas fa-envelope me-3 text-maroon" style="width: 20px; text-align: center;"></i>
                        <a href="mailto:info@sman2sukatani.sch.id" class="text-white text-decoration-none hover-maroon transition-all">
                            <span>info@sman2sukatani.sch.id</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-white fw-bold mb-4" style="height: 30px; line-height: 30px;">Jam Operasional</h5>
                <p class="text-white mb-1">Senin - Jumat: 07.00 - 16.00</p>
                <p class="text-white">Sabtu - Minggu: Tutup</p>
            </div>
        </div>

        <hr class="my-4 border-secondary opacity-25">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-white mb-0 small">&copy; <?php echo date('Y'); ?> SIMEKS SMAN 2 Sukatani. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <p class="text-white mb-0 small">Handcrafted by <span class="text-white">Darmantotech</span></p>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center" id="backToTop">
        <i class="fas fa-arrow-up text-white"></i>
    </a>
</footer>
<?php endif; ?>

<style>
    .hover-maroon:hover {
        color: var(--maroon) !important;
        transform: translateX(5px);
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .footer-links a:hover {
        color: var(--maroon) !important;
        padding-left: 8px;
        transition: all 0.3s ease;
    }

    /* Back to Top Styling */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 45px;
        height: 45px;
        background: var(--maroon);
        border-radius: 50%;
        z-index: 1000;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        display: flex; /* Kept flex for centering icons */
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        background: var(--maroon-dark);
        transform: translateY(-5px);
        color: white;
    }
</style>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS (Animate On Scroll) JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Custom Scripts -->
<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Back to Top Logic
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    // Counter Animation Logic
    const counters = document.querySelectorAll('.counter-value');
    const animateCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / 50; // Speed adjustment

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(animateCounters, 30);
            } else {
                counter.innerText = target;
            }
        });
    };

    // Intersection Observer to start counter when section is visible
    const observerOptions = { threshold: 0.5 };
    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const counterSection = document.querySelector('.bg-maroon.text-white');
    if (counterSection) counterObserver.observe(counterSection);

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            const target = document.querySelector(targetId);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
</script>

</body>

</html>