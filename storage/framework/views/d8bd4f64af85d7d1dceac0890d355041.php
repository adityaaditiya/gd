<script>
    document.addEventListener('DOMContentLoaded', () => {
        const kodeGroupSelect = document.querySelector('[data-master-kode-group-select]');
        const priceInput = document.querySelector('[data-master-kode-group-price]');

        if (!kodeGroupSelect || !priceInput) {
            return;
        }

        const formatPrice = (value) => {
            if (value === null || value === undefined || value === '') {
                return '';
            }

            const number = Number(value);
            if (Number.isNaN(number)) {
                return '';
            }

            return number.toFixed(2);
        };

        const updatePrice = () => {
            const selectedOption = kodeGroupSelect.options[kodeGroupSelect.selectedIndex];
            if (!selectedOption) {
                priceInput.value = '';
                return;
            }

            const price = selectedOption.dataset.price ?? '';
            priceInput.value = formatPrice(price);
        };

        kodeGroupSelect.addEventListener('change', updatePrice);
        updatePrice();
    });
</script>
<?php /**PATH E:\New folder\laravel 12 php 8\gd\resources\views/barang/partials/master-kode-group-script.blade.php ENDPATH**/ ?>