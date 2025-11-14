const initMoreActionDropdowns = () => {
    const containers = document.querySelectorAll('[data-more-container]');

    if (!containers.length) {
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

    const openDropdown = (nextActive) => {
        const { menu, toggle } = nextActive;

        closeDropdown();

        menu.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
        activeDropdown = nextActive;
    };

    containers.forEach((container) => {
        const toggle = container.querySelector('[data-more-toggle]');
        const menu = container.querySelector('[data-more-menu]');

        if (!toggle || !menu) {
            return;
        }

        toggle.addEventListener('click', (event) => {
            event.preventDefault();

            if (activeDropdown && activeDropdown.menu === menu) {
                closeDropdown();
                return;
            }

            openDropdown({ container, menu, toggle });
        });

        menu.addEventListener('click', () => {
            closeDropdown();
        });
    });

    document.addEventListener('click', (event) => {
        if (!activeDropdown) {
            return;
        }

        if (event.target.closest('[data-more-container]') === activeDropdown.container) {
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

const initCicilanCancelModal = () => {
    window.KRESNO = window.KRESNO || {};
    const state = window.KRESNO.cicilanCancelModal || {
        initialized: false,
        isOpen: false,
        open: null,
        close: null,
    };

    const getModal = () => document.getElementById('cicilan-cancel-modal');

    const closeModal = () => {
        const modal = getModal();

        if (!modal || !state.isOpen) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        const reasonField = modal.querySelector('[data-cancel-reason]');
        reasonField?.blur();

        state.isOpen = false;
    };

    const openModal = (detail = {}) => {
        const modal = getModal();

        if (!modal) {
            return;
        }

        const form = modal.querySelector('[data-cancel-form]');
        const summaryField = modal.querySelector('[data-cancel-summary]');
        const reasonField = modal.querySelector('[data-cancel-reason]');
        const transactionInput = modal.querySelector('[data-cancel-transaction]');
        const actionTemplate = modal.dataset.actionTemplate || form?.getAttribute('action') || '';

        const transactionId = detail.id ?? modal.dataset.initialTransaction ?? '';
        const summary = typeof detail.summary === 'string' && detail.summary.length
            ? detail.summary
            : (modal.dataset.initialSummary || '');
        const reason = typeof detail.reason === 'string'
            ? detail.reason
            : (modal.dataset.initialReason || '');

        if (transactionInput) {
            transactionInput.value = transactionId || '';
        }

        if (form) {
            if (transactionId) {
                form.setAttribute('action', actionTemplate.replace('__TRANSACTION__', transactionId));
            } else {
                form.setAttribute('action', actionTemplate);
            }
        }

        if (summaryField) {
            summaryField.textContent = summary;
        }

        if (reasonField) {
            reasonField.value = reason;
        }

        modal.dataset.initialTransaction = '';
        modal.dataset.initialSummary = '';
        modal.dataset.initialReason = '';
        modal.dataset.openOnLoad = 'false';

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        requestAnimationFrame(() => {
            reasonField?.focus({ preventScroll: true });
        });

        state.isOpen = true;
    };

    state.open = openModal;
    state.close = closeModal;

    if (!state.initialized) {
        window.addEventListener('cicilan:cancel', (event) => {
            const payload = event?.detail || {};
            state.open(payload);
        });

        document.addEventListener('click', (event) => {
            if (event.target.closest('[data-cancel-close]')) {
                event.preventDefault();
                state.close();
            }
        });

        document.addEventListener('click', (event) => {
            const modal = getModal();

            if (!modal || !state.isOpen) {
                return;
            }

            if (event.target === modal) {
                state.close();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && state.isOpen) {
                state.close();
            }
        });

        state.initialized = true;
    }

    const modal = getModal();

    if (modal && modal.dataset.openOnLoad === 'true') {
        state.open({
            id: modal.dataset.initialTransaction,
            summary: modal.dataset.initialSummary,
            reason: modal.dataset.initialReason,
        });
    }

    window.KRESNO.cicilanCancelModal = state;
};

const initCicilanCancelActions = () => {
    if (!document.querySelector('[data-cicilan-cancel-trigger]')) {
        return;
    }

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-cicilan-cancel-trigger]');

        if (!trigger) {
            return;
        }

        if (trigger.hasAttribute('disabled')) {
            return;
        }

        event.preventDefault();

        const detail = {
            id: trigger.dataset.transactionId || '',
            summary: trigger.dataset.summary || '',
            reason: trigger.dataset.reason || '',
        };

        window.dispatchEvent(new CustomEvent('cicilan:cancel', { detail }));
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

const initCicilanPagination = () => {
    const components = document.querySelectorAll('[data-cicilan-pagination]');

    if (!components.length) {
        return;
    }

    components.forEach((component) => {
        const tableId = component.getAttribute('data-table-id');
        const table = tableId ? document.getElementById(tableId) : document.querySelector('[data-cicilan-table]');
        const tbody = table?.querySelector('tbody');
        const rows = tbody ? Array.from(tbody.querySelectorAll('tr')) : [];
        const select = component.querySelector('[data-rows-per-page-select]');
        const valueLabel = component.querySelector('[data-rows-per-page-value]');
        const nav = component.querySelector('[data-pagination-nav]');

        if (!table || !tbody || !rows.length || !select || !nav) {
            component.classList.add('hidden');
            return;
        }

        let rowsPerPage = Number.parseInt(select.value, 10);

        if (!Number.isFinite(rowsPerPage) || rowsPerPage <= 0) {
            rowsPerPage = 10;
        }

        let currentPage = 1;

        const updateValueLabel = () => {
            if (valueLabel) {
                valueLabel.textContent = String(rowsPerPage);
            }
        };

        const getTotalPages = () => Math.max(1, Math.ceil(rows.length / rowsPerPage));

        const clampPage = () => {
            const totalPages = getTotalPages();

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            if (currentPage < 1) {
                currentPage = 1;
            }
        };

        const renderRows = () => {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                if (index >= start && index < end) {
                    row.classList.remove('hidden');
                    row.removeAttribute('aria-hidden');
                } else {
                    row.classList.add('hidden');
                    row.setAttribute('aria-hidden', 'true');
                }
            });
        };

        const createButton = (label, targetPage, options = {}) => {
            const { disabled = false, active = false, ariaLabel } = options;
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = label;
            button.disabled = disabled;

            const baseClass = 'inline-flex items-center justify-center rounded-md border px-3 py-1.5 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-emerald-500/40';
            const disabledClass = 'cursor-not-allowed border-neutral-200 bg-neutral-100 text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-500';
            const activeClass = 'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900';
            const defaultClass = 'border-neutral-300 bg-white text-neutral-700 hover:bg-neutral-100 focus:ring-offset-2 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:ring-offset-neutral-900';

            if (active) {
                button.className = `${baseClass} ${activeClass}`;
                button.setAttribute('aria-current', 'page');
            } else if (disabled) {
                button.className = `${baseClass} ${disabledClass}`;
            } else {
                button.className = `${baseClass} ${defaultClass}`;
                button.addEventListener('click', () => {
                    currentPage = targetPage;
                    render();
                });
            }

            if (ariaLabel) {
                button.setAttribute('aria-label', ariaLabel);
            }

            return button;
        };

        const renderNav = () => {
            const totalPages = getTotalPages();
            const fragment = document.createDocumentFragment();
            const isFirstPage = currentPage === 1;
            const isLastPage = currentPage === totalPages;

            fragment.append(
                createButton('<< First', 1, {
                    disabled: isFirstPage,
                    ariaLabel: 'Go to first page',
                }),
            );
            fragment.append(
                createButton('< Back', Math.max(1, currentPage - 1), {
                    disabled: isFirstPage,
                    ariaLabel: 'Go to previous page',
                }),
            );

            for (let page = 1; page <= totalPages; page += 1) {
                fragment.append(
                    createButton(String(page), page, {
                        active: page === currentPage,
                        ariaLabel: `Go to page ${page}`,
                    }),
                );
            }

            fragment.append(
                createButton('Next >', Math.min(totalPages, currentPage + 1), {
                    disabled: isLastPage,
                    ariaLabel: 'Go to next page',
                }),
            );
            fragment.append(
                createButton('Last >>', totalPages, {
                    disabled: isLastPage,
                    ariaLabel: 'Go to last page',
                }),
            );

            nav.innerHTML = '';
            nav.append(fragment);
        };

        const render = () => {
            clampPage();
            updateValueLabel();
            renderRows();
            renderNav();
        };

        select.addEventListener('change', () => {
            const value = Number.parseInt(select.value, 10);

            if (Number.isFinite(value) && value > 0) {
                rowsPerPage = value;
                currentPage = 1;
                render();
            } else {
                select.value = String(rowsPerPage);
            }
        });

        component.classList.remove('hidden');
        render();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initMoreActionDropdowns();
    initCurrencyInputs();
    initCicilanCancelModal();
    initCicilanCancelActions();
    initCicilanPagination();
});
