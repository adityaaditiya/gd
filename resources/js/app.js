document.addEventListener('DOMContentLoaded', () => {
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
});
