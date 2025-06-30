import './bootstrap';

import Alpine from 'alpinejs';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin],
            initialView: 'dayGridMonth',
            events: '/events'
        });

        calendar.render();
    }
});



window.Alpine = Alpine;

Alpine.start();
