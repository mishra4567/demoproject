// This is for the calendar page in the admin panal
/**
 * Start Calendar
 */

// 🔹 OPEN MODAL — global so upcoming events can access it
function openModal(eventData = null, date = null) {
    const modal = new bootstrap.Modal(document.getElementById('addEventModal'));
    const form  = document.getElementById('eventForm');

    form.action = '/admin/calender/store';

    if (eventData) {
        // ✏️ EDIT
        setValue('event_id',          eventData.extendedProps.id);
        setValue('event_title',       eventData.title);
        setValue('event_description', eventData.extendedProps?.description || '');
        setValue('event_type',        eventData.extendedProps?.type || '');
        setValue('event_start',       formatDate(eventData.start));
        setValue('event_end',         formatDate(eventData.end));
        setValue('event_status',      eventData.extendedProps?.status || 'active');
        setValue('backgroundcolor',   eventData.backgroundColor);
        setValue('bordercolor',       eventData.borderColor);
        setValue('textcolor',         eventData.textColor);

        document.getElementById('deleteBtn').classList.remove('d-none');
        document.getElementById('addEventModalLabel').textContent = 'Edit Event';
        document.getElementById('modal-submit-btn').textContent   = 'Update Event';

    } else {
        // ➕ ADD
        document.getElementById('eventForm').reset();
        setValue('event_id',        '');
        setValue('backgroundcolor', '#6c757d');
        setValue('bordercolor',     '#6c757d');
        setValue('textcolor',       '#ffffff');

        const now = date ? new Date(date) : new Date();
        setValue('event_start', formatDate(now));
        setValue('event_end',   formatDate(now));

        document.getElementById('deleteBtn').classList.add('d-none');
        document.getElementById('addEventModalLabel').textContent = 'Add New Event';
        document.getElementById('modal-submit-btn').textContent   = 'Save Event';
    }

    modal.show();
}

// 🔹 HELPERS — global
function setValue(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value || '';
}

function getValue(id) {
    return document.getElementById(id)?.value || '';
}

function formatDate(date) {
    if (!date) return '';
    const d = new Date(date);
    const pad = n => String(n).padStart(2, '0');
    return d.getFullYear() + '-' +
           pad(d.getMonth() + 1) + '-' +
           pad(d.getDate()) + 'T' +
           pad(d.getHours()) + ':' +
           pad(d.getMinutes());
}

document.addEventListener('DOMContentLoaded', function () {

    // 🔹 FULLCALENDAR
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today addEventBtn',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            customButtons: {
                addEventBtn: {
                    text: '+ Add',
                    click: () => openModal()
                }
            },
            height: 'auto',
            events: {
                url: '/admin/calendar/fetch',
                method: 'GET',
                failure: () => alert('Failed to load events')
            },
            eventClick: function (info) {
                openModal(info.event);
            },
            dateClick: function (info) {
                openModal(null, info.date);
            }
        });

        calendar.render();
    }

    // 🔹 DELETE
    const deleteBtn = document.getElementById('deleteBtn');
    if (deleteBtn) {
        deleteBtn.onclick = function () {
            const id = getValue('event_id');
            if (!id || !confirm('Delete this event?')) return;

            fetch('/admin/calender/delete/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        };
    }

    // 🔹 AUTO COLOR ON TYPE CHANGE
    const typeSelect = document.getElementById('event_type');
    if (typeSelect) {
        typeSelect.addEventListener('change', function () {
            const colors = {
                meeting:      ['#007bff', '#007bff', '#ffffff'],
                task:         ['#28a745', '#28a745', '#ffffff'],
                appointment:  ['#ffc107', '#ffc107', '#000000'],
                deadline:     ['#dc3545', '#dc3545', '#ffffff'],
                presentation: ['#17a2b8', '#17a2b8', '#ffffff'],
            };
            const c = colors[this.value] || ['#6c757d', '#6c757d', '#ffffff'];
            setValue('backgroundcolor', c[0]);
            setValue('bordercolor',     c[1]);
            setValue('textcolor',       c[2]);
        });
    }

    // 🔹 UPCOMING EVENT CARD CLICK → reuse same modal
    document.querySelectorAll('.event-item[data-id]').forEach(function (item) {
        item.addEventListener('click', function () {
            const d = this.dataset;

            openModal({
                extendedProps: {
                    id:          d.id,
                    type:        d.type,
                    description: d.description,
                    status:      d.status,
                },
                title:           d.title,
                start:           new Date(d.start),
                end:             new Date(d.end),
                backgroundColor: d.bg,
                borderColor:     d.border,
                textColor:       d.text,
            });
        });
    });

    // 🔹 UPCOMING EVENTS PAGINATION
    const items   = document.querySelectorAll('.event-item[data-index]');
    const prevBtn = document.getElementById('prev-event');
    const nextBtn = document.getElementById('next-event');
    const counter = document.getElementById('event-counter');

    if (items.length > 0) {
        const perPage    = 3;
        let currentPage  = 0;
        const totalPages = Math.ceil(items.length / perPage);

        function showPage(page) {
            const start = page * perPage;
            const end   = start + perPage;

            items.forEach((item, index) => {
                item.style.setProperty('display', index >= start && index < end ? 'flex' : 'none', 'important');
            });

            if (counter) counter.textContent = `${start + 1} - ${Math.min(end, items.length)} / ${items.length}`;
            if (prevBtn) prevBtn.disabled = page === 0;
            if (nextBtn) nextBtn.disabled = page === totalPages - 1;
        }

        showPage(0);

        if (nextBtn) nextBtn.addEventListener('click', () => { if (currentPage < totalPages - 1) showPage(++currentPage); });
        if (prevBtn) prevBtn.addEventListener('click', () => { if (currentPage > 0) showPage(--currentPage); });
    }

});







/**
 * End Calendar
 */

        // Function for "Add Event" button
        // function addNewEvent() {
        //             const modal = new bootstrap.Modal(document.getElementById('addEventModal'));
        // const form  = document.getElementById('eventForm');
        //     form.action = '/admin/calendar/store';  // ✅ always same route
        //     form.reset();
        //     setValue('event_id', '');   // ✅ must clear explicitly

        //     const now = new Date();
        //     setValue('event_start', formatDate(now));
        //     setValue('event_end',   formatDate(now));

        //     document.getElementById('deleteBtn').classList.add('d-none');
        //     document.getElementById('addEventModalLabel').textContent = 'Add New Event';
        //     document.getElementById('modal-submit-btn').textContent   = 'Save Event';

        //     modal.show();
        // }






// This is upcoming event js pagination
/**
 * Start Upcoming Envent
 */

document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.event-item[data-index]');
    const prevBtn = document.getElementById('prev-event');
    const nextBtn = document.getElementById('next-event');
    const counter = document.getElementById('event-counter');

    if (items.length === 0) return;

    const perPage = 3;
    let currentPage = 0;
    const totalPages = Math.ceil(items.length / perPage);

    function showPage(page) {
        const start = page * perPage;
        const end = start + perPage;

        items.forEach((item, index) => {
            if (index >= start && index < end) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });

        if (counter) {
            counter.textContent = `${start + 1} - ${Math.min(end, items.length)} / ${items.length}`;
        }

        prevBtn.disabled = page === 0;
        nextBtn.disabled = page === totalPages - 1;
    }

    // Init — show first 3
    showPage(0);

    nextBtn.addEventListener('click', function () {
        if (currentPage < totalPages - 1) showPage(++currentPage);
    });

    prevBtn.addEventListener('click', function () {
        if (currentPage > 0) showPage(--currentPage);
    });
});

/**
 * Start Upcoming Envent
 */
