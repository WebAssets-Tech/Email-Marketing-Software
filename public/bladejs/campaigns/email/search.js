$(document).ready(function () {
    let input = $('#search-email')

    // Storage key for persisting selected email IDs across pages/search
    const STORAGE_KEY = 'campaign_selected_email_ids';

    function getStoredIds() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function setStoredIds(ids) {
        const unique = Array.from(new Set(ids));
        localStorage.setItem(STORAGE_KEY, JSON.stringify(unique));
    }

    function addIds(idsToAdd) {
        const current = getStoredIds();
        setStoredIds(current.concat(idsToAdd));
    }

    function removeIds(idsToRemove) {
        const current = getStoredIds();
        const setToRemove = new Set(idsToRemove);
        const next = current.filter(function (id) { return !setToRemove.has(id); });
        setStoredIds(next);
    }

    // Apply stored selection to the currently rendered table rows
    function applySelectionsToPage() {
        const stored = new Set(getStoredIds());
        const pageCheckboxes = $('.checking');
        pageCheckboxes.each(function () {
            const id = $(this).data('id') + '';
            $(this).prop('checked', stored.has(id));
        });
        syncSelectAllState();
    }

    function syncSelectAllState() {
        const pageCheckboxes = $('.checking');
        if (pageCheckboxes.length === 0) {
            $('#check_all, .checkAll').prop('checked', false);
            return;
        }
        const allChecked = pageCheckboxes.length === $('.checking:checked').length;
        $('#check_all, .checkAll').prop('checked', allChecked);
    }

    // Delegated handler: individual checkbox toggle updates storage
    $(document).on('click', '.checking', function () {
        const id = $(this).data('id') + '';
        if ($(this).is(':checked')) {
            addIds([id]);
        } else {
            removeIds([id]);
        }
        syncSelectAllState();
    });

    // Delegated handler: select-all toggle updates page and storage
    $(document).on('click', '#check_all, .checkAll', function () {
        const isChecked = $(this).is(':checked');
        const pageCheckboxes = $('.checking');
        pageCheckboxes.prop('checked', isChecked);
        const idsOnPage = pageCheckboxes.map(function () { return $(this).data('id') + ''; }).get();
        if (isChecked) {
            addIds(idsOnPage);
        } else {
            removeIds(idsOnPage);
        }
        syncSelectAllState();
    });

    // Clear storage when saving the campaign
    $(document).on('click', '.campaign-email', function () {
        try { localStorage.removeItem(STORAGE_KEY); } catch (e) {}
    });

    // Restore selection on initial load
    applySelectionsToPage();

    // Re-apply selections after any AJAX update that might replace the list
    $(document).ajaxComplete(function () {
        applySelectionsToPage();
    });

    // Search handling (AJAX replaces table content). After inject, reapply selections.
    input.on('keydown', function (e) {
        if (e.key == 'Enter') {
            e.preventDefault()
            var searchQuery = $(this).val().toLowerCase();
            fetchSearchResults(searchQuery);
        }
    });

    function fetchSearchResults(searchQuery) {
        $.ajax({
            type: "GET",
            url: "/campaign/email/contacts" + '?search=' + encodeURIComponent(searchQuery),
            success: function (data) {
                $('#campaignLoadPage').empty();
                $('#campaignLoadPage').append(data);
                // Re-apply stored selections to the new page
                applySelectionsToPage();
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

    // If pagination links are clicked and loaded via full page navigation,
    // localStorage will preserve state and applySelectionsToPage will run on load.
    // If you intercept pagination via AJAX elsewhere, ensure to call applySelectionsToPage() after DOM update.
});