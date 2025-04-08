<footer class="content-info alignfull mb-0 mt-0 container p-md-5 py-md-6 px-3">
    <?php
    if (WP_ENV === 'production') {
        echo the_field('footer_tracker', 'option');
    }
    ?>
    @include('partials.footer')
</footer>