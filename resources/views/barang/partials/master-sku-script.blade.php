<script>
    document.addEventListener('DOMContentLoaded', () => {
        const skuSelect = document.querySelector('[data-master-sku-select]');
        const priceInput = document.querySelector('[data-master-sku-price]');

        if (!skuSelect || !priceInput) {
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
            const selectedOption = skuSelect.options[skuSelect.selectedIndex];
            if (!selectedOption) {
                priceInput.value = '';
                return;
            }

            const price = selectedOption.dataset.price ?? '';
            priceInput.value = formatPrice(price);
        };

        skuSelect.addEventListener('change', updatePrice);
        updatePrice();
    });
</script>
