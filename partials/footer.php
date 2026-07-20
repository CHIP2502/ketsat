</main>
<footer class="site-footer" id="lien-he">
    <div class="container footer-grid">
        <section>
            <h2>Két sắt Việt Tiệp</h2>
            <p>Cung cấp két sắt chính hãng, hỗ trợ lựa chọn sản phẩm phù hợp cho gia đình và doanh nghiệp.</p>
        </section>
        <section>
            <h2>Thông tin pháp lý</h2>
            <p>CÔNG TY TNHH THƯƠNG MẠI VÀ SẢN XUẤT KÉT SẮT VIỆT TIỆP VN</p>
            <p>Mã số thuế: 0111372405</p>
            <p>Địa chỉ: Số nhà 20, ngõ 31 thôn Đại Tự, Xã Hoài Đức, Thành phố Hà Nội.</p>
        </section>
        <section>
            <h2>Hotline</h2>
            <?php foreach ($config['hotlines'] as $hotline): ?>
                <a class="footer-phone" href="tel:<?= e($hotline['tel']) ?>"><?= e($hotline['label']) ?>: <?= e($hotline['number']) ?></a>
            <?php endforeach; ?>
        </section>
    </div>
    <div class="copyright">© <?= date('Y') ?> <?= e($config['app_name']) ?>.</div>
</footer>
</body>
</html>
