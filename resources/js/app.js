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
        const trimmed = (value ?? '').toString().trim();

        if (!trimmed) {
            return '';
        }

        let sanitized = trimmed.replace(/[^0-9,.-]/g, '');

        if (!sanitized) {
            return '';
        }

        const lastComma = sanitized.lastIndexOf(',');
        const lastDot = sanitized.lastIndexOf('.');

        if (lastComma !== -1 && lastDot !== -1) {
            if (lastComma > lastDot) {
                sanitized = sanitized.replace(/\./g, '').replace(',', '.');
            } else {
                sanitized = sanitized.replace(/,/g, '');
            }
        } else if (lastComma !== -1) {
            sanitized = sanitized.replace(/\./g, '').replace(',', '.');
        } else if (lastDot !== -1) {
            const decimals = sanitized.length - lastDot - 1;

            if (decimals > 0 && decimals <= 2) {
                sanitized = sanitized.replace(/,/g, '');
            } else {
                sanitized = sanitized.replace(/\./g, '');
            }
        } else {
            sanitized = sanitized.replace(/,/g, '');
        }

        if (!sanitized || sanitized === '-' || sanitized === '.') {
            return '';
        }

        const number = Number.parseFloat(sanitized);

        if (Number.isNaN(number)) {
            return '';
        }

        const isNegative = number < 0;
        const absolute = Math.abs(number);
        const decimalSegment = sanitized.split('.')[1] ?? '';
        const precision = decimalSegment.length;
        const fixed = absolute.toFixed(precision);
        const [integerPartRaw, fractionPartRaw = ''] = fixed.split('.');
        const formattedInteger = integerPartRaw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const signedInteger = isNegative && formattedInteger ? `-${formattedInteger}` : formattedInteger;

        return fractionPartRaw ? `${signedInteger},${fractionPartRaw}` : signedInteger;
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

        return value.replace(/\./g, '').replace(',', '.');
    };

    const allowedNavigationKeys = new Set([
        'Backspace',
        'Delete',
        'ArrowLeft',
        'ArrowRight',
        'ArrowUp',
        'ArrowDown',
        'Home',
        'End',
        'Tab',
        'Enter',
    ]);

    inputs.forEach((input) => {
        if (input.dataset.currencyBound === 'true') {
            return;
        }

        const initial = input.value;

        if (initial) {
            input.value = formatCurrency(initial);
        }

        input.addEventListener('keydown', (event) => {
            const isControlCombo = event.ctrlKey || event.metaKey || event.altKey;

            if (isControlCombo) {
                return;
            }

            if (allowedNavigationKeys.has(event.key)) {
                return;
            }

            if (/^\d$/.test(event.key)) {
                return;
            }

            event.preventDefault();
        });

        const handleTyping = (event) => {
            const target = event.target;
            const selectionStart = target.selectionStart ?? target.value.length;
            const digitIndex = countDigitsBefore(target.value, selectionStart);
            target.value = formatCurrency(target.value);

            requestAnimationFrame(() => {
                restoreCursor(target, digitIndex);
            });
        };

        input.addEventListener('input', handleTyping);
        input.addEventListener('keyup', handleTyping);

        input.addEventListener('blur', (event) => {
            event.target.value = formatCurrency(event.target.value);
        });

        input.dataset.currencyBound = 'true';
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

document.addEventListener('livewire:navigated', () => {
    initCurrencyInputs();
});

window.KRESNO = window.KRESNO || {};
window.KRESNO.initCurrencyInputs = initCurrencyInputs;
