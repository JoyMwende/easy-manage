<div class="footer">
    <footer>
        <?php wp_footer(); ?>
        <p>2023 Copyright. All rights reserved.</p>
    </footer>
</div>

<script>
            window.addEventListener('load', () => {
                var date = new Date();
                new Date().setDate(date.getDate() + 2);
                var tomorrow = date.toISOString().split('T')[0];
                var dateInput = document.querySelectorAll('input[type="date"]');
                dateInput.forEach(function(input) {
                    input.min = tomorrow;
                });
            });
        </script>

</body>
</html>