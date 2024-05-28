<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
    .about-section {
        padding: 0;
    }

    .row {
        display: flex;
        justify-content: space-around;
    }

    .about-image {
        margin: 10px;
    }

    .img-responsive {
        width: 400px;
        height: 400px;
        object-fit: cover;
    }

    .about-content {
        text-align: center;
    }

    p {
        font-size: 18px;
    }
</style>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

        <?php include 'includes/navbar.php'; ?>

        <div class="content-wrapper">
            <div class="container about-section">
                <!-- Main content -->
                <section class="content">
                        <div class="row">
                            <div class="col-md-12 about-content">
                                <h2>About Us</h2>
                                <p>Selamat datang di SaturnStore, satu-satunya toko elektronik nomor satu untuk segala hal yang berhubungan dengan elektronik. Kami berdedikasi untuk memberikan yang terbaik dalam bidang elektronik, dengan fokus pada keandalan, layanan pelanggan, dan keunikan.</p>
                                <p>Didirikan pada tahun 2024, SaturnStore telah berkembang pesat sejak awal. Saat pertama kali memulai, semangat kami untuk menyediakan peralatan terbaik bagi sesama penggemar teknologi mendorong kami untuk melakukan penelitian yang intens dan memberi kami dorongan untuk mengubah kerja keras dan inspirasi menjadi toko online yang berkembang pesat. Kami sekarang melayani pelanggan di seluruh dunia dan sangat senang menjadi bagian dari sayap perdagangan yang unik, ramah lingkungan, dan adil dalam industri elektronik.</p>
                                <p>Kami harap Anda menikmati produk kami seperti halnya kami menikmati menawarkannya kepada Anda. Jika Anda memiliki pertanyaan atau komentar, jangan ragu untuk menghubungi kami.</p>
                                <p>Hormat Kami,</p>
                                <p><strong>Tim SaturnStore</strong></p>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-md-4 about-image">
                                <img id="handphone-image" class="img-responsive" alt="Electronics">
                            </div>
                            <div class="col-md-4 about-image">
                                <img id="technology-image" class="img-responsive" alt="Technology">
                            </div>
                            <div class="col-md-4 about-image">
                                <img id="laptop-image" class="img-responsive" alt="Gadgets">
                            </div>
                        </div>
                </section>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>

    <script type="module">
        import config from './config.js';

        async function fetchImage(query, imgId) {
            const accessKey = config.UNSPLASH_ACCESS_KEY;
            const url = `https://api.unsplash.com/photos/random?query=${query}&client_id=${accessKey}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                const imageUrl = `${data.urls.raw}&w=400&h=400&fit=crop`;
                document.getElementById(imgId).src = imageUrl;
            } catch (error) {
                console.error('Error fetching image:', error);
            }
        }

        fetchImage('handphone', 'handphone-image');
        fetchImage('technology', 'technology-image');
        fetchImage('laptop', 'laptop-image');
    </script>

</body>

</html>