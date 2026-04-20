
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: Client-side logic for the secure admin dashboard, handling AJAX CRUD operations for events, partners, and executives. (BASE is defined inline in dashboard.php).
 */

/* ============================================================
   VentureHub - admin.js
   BASE is defined inline in dashboard.php before this loads
   ============================================================ */

'use strict';

window.addEventListener('load', () => {

    // ── Generic AJAX POST helper ────────────────────────────────

    /**
    * Sends a POST request to a given API endpoint using FormData.
     *
    * @param {String} url - The API endpoint URL to post to.
    * @param {Object} data - Key-value pairs to append to the FormData body.
    * @returns {Promise} A promise that resolves to the parsed JSON response.
    */
    function apiPost(url, data) {
        const fd = new FormData();
        Object.entries(data).forEach(([k, v]) => fd.append(k, v ?? ''));
        return fetch(url, { method: 'POST', body: fd }).then(r => r.json());
    }

    // ── Flash message ───────────────────────────────────────────
    /**
    * Displays a temporary flash notification message on the page.
    *
    * @param {String} msg - The message text to display.
    * @param {String} type - The alert style, either 'success' or 'error'.
    * @returns {void}
    */
    function flash(msg, type = 'success') {
        const el = document.getElementById('flashMsg');
        if (!el) return;
        el.textContent = msg;
        el.className = 'alert alert-' + type;
        el.style.display = 'block';
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        setTimeout(() => { el.style.display = 'none'; }, 4000);
    }

    /**
    * Prompts the user with a confirmation dialog before a delete action.
    *
    * @param {String} msg - The confirmation message to show the user.
    * @returns {Boolean} True if the user confirmed, false if they cancelled.
    */
    function confirmDelete(msg) {
        return confirm(msg || 'Are you sure you want to delete this?');
    }

    // XSS escape

    /**
    * Escapes a string to make it safe for insertion into HTML.
    *
    * @param {String} str - The raw string to escape.
    * @returns {String} The HTML-escaped string with special characters replaced.
    */
    function esc(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ============================================================
    // EVENTS — Matthew
    // ============================================================
    const eventsSection = document.getElementById('admin-events');
    if (eventsSection) {

        const form = document.getElementById('eventForm');
        const tbody = document.getElementById('eventsTbody');
        const idInput = document.getElementById('eventId');
        const formTitle = document.getElementById('eventFormTitle');
        const cancelBtn = document.getElementById('eventCancel');

        /**
        * Fetches all events from the API and passes them to renderEvents.
        *
        * @returns {void}
        */
        function loadEvents() {
            fetch(BASE + '/api/events.php?action=list')
                .then(r => r.json())
                .then(data => renderEvents(data.events || []));
        }

        /**
        * Renders the list of events into the events table body.
        *
        * @param {Array} events - Array of event objects returned from the API.
        * @returns {void}
        */
        function renderEvents(events) {
            if (!events.length) {
                tbody.innerHTML = '<tr><td colspan="4" style="color:#999;padding:1rem;">No events yet.</td></tr>';
                return;
            }
            tbody.innerHTML = events.map(e => `
            <tr>
                <td>${esc(e.title)}</td>
                <td>${e.event_date || ''}</td>
                <td>${esc(e.location || '')}</td>
                <td class="actions">
                    <button class="btn btn-sm btn-red" onclick="editEvent(${e.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteEvent(${e.id})">Delete</button>
                </td>
            </tr>
        `).join('');
        }

        window.editEvent = function (id) {
            fetch(BASE + '/api/events.php?action=get&id=' + id)
                .then(r => r.json())
                .then(data => {
                    const e = data.event;
                    idInput.value = e.id;
                    document.getElementById('eventTitle').value = e.title;
                    document.getElementById('eventDescription').value = e.description || '';
                    document.getElementById('eventDate').value = e.event_date || '';
                    document.getElementById('eventLocation').value = e.location || '';
                    document.getElementById('eventPhoto').value = e.photo_url || '';
                    formTitle.textContent = 'Edit Event';
                    cancelBtn.style.display = 'inline-block';
                    form.scrollIntoView({ behavior: 'smooth' });
                });
        };

        window.deleteEvent = function (id) {
            if (!confirmDelete('Delete this event?')) return;
            apiPost(BASE + '/api/events.php', { action: 'delete', id })
                .then(data => {
                    flash(data.success ? 'Event deleted.' : (data.error || 'Error.'), data.success ? 'success' : 'error');
                    if (data.success) loadEvents();
                });
        };

        form.addEventListener('submit', e => {
            e.preventDefault();
            const payload = {
                action: idInput.value ? 'update' : 'insert',
                id: idInput.value,
                title: document.getElementById('eventTitle').value.trim(),
                description: document.getElementById('eventDescription').value.trim(),
                event_date: document.getElementById('eventDate').value,
                location: document.getElementById('eventLocation').value.trim(),
                photo_url: document.getElementById('eventPhoto').value.trim(),
            };
            if (!payload.title) { flash('Title is required.', 'error'); return; }
            apiPost(BASE + '/api/events.php', payload).then(data => {
                flash(data.success ? 'Event saved.' : (data.error || 'Error.'), data.success ? 'success' : 'error');
                if (data.success) { resetEventForm(); loadEvents(); }
            });
        });

        if (cancelBtn) cancelBtn.addEventListener('click', resetEventForm);

        /**
        * Resets the event form back to its default empty 'Add New Event' state.
        *
        * @returns {void}
        */
        function resetEventForm() {
            form.reset();
            idInput.value = '';
            formTitle.textContent = 'Add New Event';
            cancelBtn.style.display = 'none';
        }

        loadEvents();
    }

    // ============================================================
    // PARTNERS — Matthew
    // ============================================================
    const partnersSection = document.getElementById('admin-partners');
    if (partnersSection) {

        const form = document.getElementById('partnerForm');
        const tbody = document.getElementById('partnersTbody');
        const idInput = document.getElementById('partnerId');
        const formTitle = document.getElementById('partnerFormTitle');
        const cancelBtn = document.getElementById('partnerCancel');


        /**
         * Fetches all partners from the API and passes them to renderPartners.
         *
         * @returns {void}
         */

        function loadPartners() {
            fetch(BASE + '/api/partners.php?action=list')
                .then(r => r.json())
                .then(data => renderPartners(data.partners || []));
        }

        /**
        * Renders the list of partners into the partners table body.
        *
        * @param {Array} partners - Array of partner objects returned from the API.
        * @returns {void}
        */
        function renderPartners(partners) {
            if (!partners.length) {
                tbody.innerHTML = '<tr><td colspan="4" style="color:#999;padding:1rem;">No partners yet.</td></tr>';
                return;
            }
            tbody.innerHTML = partners.map(p => `
            <tr>
                <td>${esc(p.name)}</td>
                <td><a href="${esc(p.website_url || '#')}" target="_blank">${esc(p.website_url || '')}</a></td>
                <td>${p.display_order}</td>
                <td class="actions">
                    <button class="btn btn-sm btn-red" onclick="editPartner(${p.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deletePartner(${p.id})">Delete</button>
                </td>
            </tr>
        `).join('');
        }

        window.editPartner = function (id) {
            fetch(BASE + '/api/partners.php?action=get&id=' + id)
                .then(r => r.json())
                .then(data => {
                    const p = data.partner;
                    idInput.value = p.id;
                    document.getElementById('partnerName').value = p.name;
                    document.getElementById('partnerLogo').value = p.logo_url || '';
                    document.getElementById('partnerWebsite').value = p.website_url || '';
                    document.getElementById('partnerDesc').value = p.description || '';
                    document.getElementById('partnerOrder').value = p.display_order;
                    formTitle.textContent = 'Edit Partner';
                    cancelBtn.style.display = 'inline-block';
                    form.scrollIntoView({ behavior: 'smooth' });
                });
        };

        window.deletePartner = function (id) {
            if (!confirmDelete('Delete this partner?')) return;
            apiPost(BASE + '/api/partners.php', { action: 'delete', id })
                .then(data => {
                    flash(data.success ? 'Partner deleted.' : (data.error || 'Error.'), data.success ? 'success' : 'error');
                    if (data.success) loadPartners();
                });
        };

        form.addEventListener('submit', e => {
            e.preventDefault();
            const payload = {
                action: idInput.value ? 'update' : 'insert',
                id: idInput.value,
                name: document.getElementById('partnerName').value.trim(),
                logo_url: document.getElementById('partnerLogo').value.trim(),
                website_url: document.getElementById('partnerWebsite').value.trim(),
                description: document.getElementById('partnerDesc').value.trim(),
                display_order: document.getElementById('partnerOrder').value,
            };
            if (!payload.name) { flash('Name is required.', 'error'); return; }
            apiPost(BASE + '/api/partners.php', payload).then(data => {
                flash(data.success ? 'Partner saved.' : (data.error || 'Error.'), data.success ? 'success' : 'error');
                if (data.success) { resetPartnerForm(); loadPartners(); }
            });
        });

        if (cancelBtn) cancelBtn.addEventListener('click', resetPartnerForm);

        /**
        * Resets the partner form back to its default empty 'Add New Partner' state.
        *
        * @returns {void}
        */
        function resetPartnerForm() {
            form.reset();
            idInput.value = '';
            formTitle.textContent = 'Add New Partner';
            cancelBtn.style.display = 'none';
        }

        loadPartners();
    }

    // ============================================================
    // EXECUTIVES — Aleesha
    // ============================================================
    const execsSection = document.getElementById('admin-execs');
    if (execsSection) {

        const form = document.getElementById('execForm');
        const tbody = document.getElementById('execsTbody');
        const idInput = document.getElementById('execId');
        const formTitle = document.getElementById('execFormTitle');
        const cancelBtn = document.getElementById('execCancel');

        /**
        * Fetches all executives from the API and passes them to renderExecs.
        *
        * @returns {void}
        */
        function loadExecs() {
            fetch(BASE + '/api/executives.php?action=list')
                .then(r => r.json())
                .then(data => renderExecs(data.executives || []));
        }
        /**
        * Renders the list of executives into the executives table body.
        *
        * @param {Array} execs - Array of executive objects returned from the API.
        * @returns {void}
        */

        function renderExecs(execs) {
            if (!execs.length) {
                tbody.innerHTML = '<tr><td colspan="4" style="color:#999;padding:1rem;">No executives yet.</td></tr>';
                return;
            }
            tbody.innerHTML = execs.map(ex => `
            <tr>
                <td>${esc(ex.name)}</td>
                <td>${esc(ex.role || '')}</td>
                <td>${ex.display_order}</td>
                <td class="actions">
                    <button class="btn btn-sm btn-red" onclick="editExec(${ex.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteExec(${ex.id})">Delete</button>
                </td>
            </tr>
        `).join('');
        }

        window.editExec = function (id) {
            fetch(BASE + '/api/executives.php?action=get&id=' + id)
                .then(r => r.json())
                .then(data => {
                    const ex = data.executive;
                    idInput.value = ex.id;
                    document.getElementById('execName').value = ex.name;
                    document.getElementById('execRole').value = ex.role || '';
                    document.getElementById('execBio').value = ex.bio || '';
                    document.getElementById('execPhoto').value = ex.photo_url || '';
                    document.getElementById('execLinkedin').value = ex.linkedin_url || '';
                    document.getElementById('execInstagram').value = ex.instagram_url || '';
                    document.getElementById('execOrder').value = ex.display_order;
                    formTitle.textContent = 'Edit Executive';
                    cancelBtn.style.display = 'inline-block';
                    form.scrollIntoView({ behavior: 'smooth' });
                });
        };

        window.deleteExec = function (id) {
            if (!confirmDelete('Delete this executive?')) return;
            apiPost(BASE + '/api/executives.php', { action: 'delete', id })
                .then(data => {
                    flash(data.success ? 'Executive deleted.' : (data.error || 'Error.'), data.success ? 'success' : 'error');
                    if (data.success) loadExecs();
                });
        };

        form.addEventListener('submit', e => {
            e.preventDefault();
            const payload = {
                action: idInput.value ? 'update' : 'insert',
                id: idInput.value,
                name: document.getElementById('execName').value.trim(),
                role: document.getElementById('execRole').value.trim(),
                bio: document.getElementById('execBio').value.trim(),
                photo_url: document.getElementById('execPhoto').value.trim(),
                linkedin_url: document.getElementById('execLinkedin').value.trim(),
                instagram_url: document.getElementById('execInstagram').value.trim(),
                display_order: document.getElementById('execOrder').value,
            };
            if (!payload.name) { flash('Name is required.', 'error'); return; }
            apiPost(BASE + '/api/executives.php', payload).then(data => {
                flash(data.success ? 'Executive saved.' : (data.error || 'Error.'), data.success ? 'success' : 'error');
                if (data.success) { resetExecForm(); loadExecs(); }
            });
        });

        if (cancelBtn) cancelBtn.addEventListener('click', resetExecForm);

        /**
        * Resets the executive form back to its default empty 'Add New Executive' state.
        *
        * @returns {void}
        */
        function resetExecForm() {
            form.reset();
            idInput.value = '';
            formTitle.textContent = 'Add New Executive';
            cancelBtn.style.display = 'none';
        }

        loadExecs();
    }

});