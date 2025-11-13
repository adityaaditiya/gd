const initTransaksiGadaiTableDropdown = () => {
    const table = document.querySelector('[data-transaksi-gadai-table]');

    if (!table) {
        return;
    }

    let activeDropdown = null;

    const closeDropdown = () => {
        if (!activeDropdown) {
            return;
        }

        const { menu, toggle } = activeDropdown;

        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        activeDropdown = null;
    };

    table.addEventListener('click', (event) => {
        const toggle = event.target.closest('[data-more-toggle]');

        if (toggle) {
            event.preventDefault();
            const container = toggle.parentElement;
            const menu = container.querySelector('[data-more-menu]');

            if (!menu) {
                return;
            }

            if (activeDropdown && activeDropdown.menu === menu) {
                closeDropdown();
                return;
            }

            closeDropdown();

            menu.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            activeDropdown = { menu, toggle };

            return;
        }

        if (event.target.closest('[data-more-menu]')) {
            return;
        }

        closeDropdown();
    });

    document.addEventListener('click', (event) => {
        if (!activeDropdown) {
            return;
        }

        if (table.contains(event.target)) {
            return;
        }

        closeDropdown();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeDropdown();
        }
    });
};

const initCurrencyInputs = () => {
    const inputs = document.querySelectorAll('[data-currency-input]');

    if (!inputs.length) {
        return;
    }

    const formatCurrency = (value) => {
        const digitsOnly = (value ?? '')
            .toString()
            .replace(/\D/g, '');
        const normalized = digitsOnly.replace(/^0+(?=\d)/, '');

        if (!normalized) {
            return '';
        }

        return normalized.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    const countDigitsBefore = (value, cursorPosition) => {
        return value.slice(0, cursorPosition).replace(/\D/g, '').length;
    };

    const restoreCursor = (input, digitIndex) => {
        const value = input.value;
        let digitsSeen = 0;
        let position = 0;

        while (position < value.length && digitsSeen < digitIndex) {
            if (/\d/.test(value[position])) {
                digitsSeen += 1;
            }

            position += 1;
        }

        input.setSelectionRange(position, position);
    };

    const normalizeForSubmission = (value) => {
        if (!value) {
            return '';
        }

        return value.replace(/\D/g, '');
    };

    inputs.forEach((input) => {
        const initial = input.value;

        if (initial) {
            input.value = formatCurrency(initial);
        }

        input.addEventListener('input', (event) => {
            const target = event.target;
            const selectionStart = target.selectionStart ?? target.value.length;
            const digitIndex = countDigitsBefore(target.value, selectionStart);
            target.value = formatCurrency(target.value);

            requestAnimationFrame(() => {
                restoreCursor(target, digitIndex);
            });
        });

        input.addEventListener('blur', (event) => {
            event.target.value = formatCurrency(event.target.value);
        });
    });

    const forms = new Set(Array.from(inputs).map((input) => input.form).filter(Boolean));

    forms.forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('[data-currency-input]').forEach((field) => {
                field.value = normalizeForSubmission(field.value);
            });
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initTransaksiGadaiTableDropdown();
    initCurrencyInputs();
});
