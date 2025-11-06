<aside class="sidebar" data-sidebar>
    <nav class="sidebar__nav">
        <a href="#" class="sidebar__link sidebar__link--active">Dashboard</a>

        <div class="sidebar__group" data-collapsible>
            <button
                type="button"
                class="sidebar__toggle"
                data-toggle
                aria-expanded="false"
                aria-controls="gadai-submenu"
            >
                <span>Gadai</span>
                <svg class="sidebar__icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <ul id="gadai-submenu" class="sidebar__submenu" data-submenu>
                <li><a href="#" class="sidebar__sublink">Pemberian Kredit</a></li>
                <li><a href="#" class="sidebar__sublink">Lihat Gadai</a></li>
                <li><a href="#" class="sidebar__sublink">Lihat Data Lelang</a></li>
            </ul>
        </div>
    </nav>
</aside>

<style>
    .sidebar {
        width: 100%;
        max-width: 280px;
        min-height: 100vh;
        padding: 1.5rem 1rem;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        color: #f8fafc;
        font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .sidebar__nav {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .sidebar__link,
    .sidebar__toggle,
    .sidebar__sublink {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        color: inherit;
        text-decoration: none;
        transition: background-color 150ms ease, color 150ms ease;
    }

    .sidebar__link:hover,
    .sidebar__toggle:hover,
    .sidebar__sublink:hover {
        background-color: rgba(148, 163, 184, 0.25);
        color: #fff;
    }

    .sidebar__link--active {
        background: rgba(96, 165, 250, 0.25);
        color: #f8fafc;
    }

    .sidebar__toggle {
        width: 100%;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .sidebar__icon {
        width: 1.25rem;
        height: 1.25rem;
        transition: transform 200ms ease;
    }

    .sidebar__submenu {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 0.25rem;
        overflow: hidden;
        max-height: 0;
        transition: max-height 220ms ease;
    }

    .sidebar__submenu[aria-expanded="true"] {
        max-height: 500px;
    }

    .sidebar__toggle[aria-expanded="true"] .sidebar__icon {
        transform: rotate(180deg);
    }

    .sidebar__sublink {
        font-weight: 500;
        font-size: 0.95rem;
        padding-left: 2.5rem;
        color: #cbd5f5;
    }

    .sidebar__sublink:hover {
        color: #fff;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-collapsible]').forEach((collapsible) => {
            const toggleButton = collapsible.querySelector('[data-toggle]');
            const submenu = collapsible.querySelector('[data-submenu]');

            if (!toggleButton || !submenu) {
                return;
            }

            submenu.style.maxHeight = '0px';
            submenu.setAttribute('aria-expanded', 'false');
            submenu.setAttribute('aria-hidden', 'true');
            toggleButton.setAttribute('aria-expanded', 'false');

            toggleButton.addEventListener('click', () => {
                const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
                toggleButton.setAttribute('aria-expanded', String(!isExpanded));
                submenu.setAttribute('aria-expanded', String(!isExpanded));
                submenu.setAttribute('aria-hidden', String(isExpanded));

                if (isExpanded) {
                    submenu.style.maxHeight = '0px';
                } else {
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                }
            });
        });
    });
</script>
