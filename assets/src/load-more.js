/**
 * Load More Posts (Infinite Scroll trigger)
 */
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('load-more-btn');
    const container = document.getElementById('ajax-post-container');

    if (!loadMoreBtn || !container) return;

    loadMoreBtn.addEventListener('click', function () {
        const button = this;
        const page = parseInt(button.dataset.page);
        const max = parseInt(button.dataset.max);
        const archive = button.dataset.archive;
        const nonce = jagawarta_vars.nonce; // Ensure this is localized

        if (button.classList.contains('loading')) return;

        const initialHtml = button.dataset.initialHtml || button.innerHTML;
        button.classList.add('loading');
        button.setAttribute('aria-busy', 'true');
        button.innerHTML = 'Loading...';
        button.disabled = true;

        const data = new FormData();
        data.append('action', 'jagawarta_load_more');
        data.append('nonce', nonce);
        data.append('page', page);
        data.append('archive', archive);

        fetch(jagawarta_vars.ajax_url, {
            method: 'POST',
            body: data
        })
            .then(response => response.text())
            .then(html => {
                if (html) {
                    // Append posts
                    container.insertAdjacentHTML('beforeend', html);

                    // Update page
                    button.dataset.page = page + 1;

                    if (page >= max) {
                        button.remove();
                    } else {
                        button.classList.remove('loading');
                        button.setAttribute('aria-busy', 'false');
                        button.innerHTML = initialHtml;
                        button.disabled = false;
                    }
                } else {
                    button.remove();
                }
            })
            .catch(() => {
                button.classList.remove('loading');
                button.setAttribute('aria-busy', 'false');
                button.innerHTML = initialHtml;
                button.disabled = false;
            });
    });
});
