@extends('admin.layout.layout')
@section('page_title', 'Calender')
@section('calender_select', 'active')
@section('container')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1">calendar</h2>
                    <button class="au-btn au-btn-icon au-btn--blue" onclick="openModal()">
                        <i class="zmdi zmdi-plus"></i>add event</button>
                </div>
            </div>
        </div>
        <div class="row m-t-25">
            <div class="col-lg-9">
                <div class="au-card">
                    <div class="au-card-inner">
                        <h3 class="title-2 m-b-40">Event Calendar</h3>
                        <div id="calendar" class="calendar-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- UPCOMING EVENTS -->
                @include('admin.component.upevent')

                <!-- CALENDAR LEGEND -->
                <div class="au-card">
                    <div class="au-card-inner">
                        <h3 class="title-2 m-b-30">Event Types</h3>
                        <div class="calendar-legend">
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-primary rounded me-2" style="width: 16px; height: 16px;"></div>
                                <span class="fs-6">Meetings</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-success rounded me-2" style="width: 16px; height: 16px;"></div>
                                <span class="fs-6">Tasks</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-warning rounded me-2" style="width: 16px; height: 16px;"></div>
                                <span class="fs-6">Appointments</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="legend-color bg-danger rounded me-2" style="width: 16px; height: 16px;"></div>
                                <span class="fs-6">Deadlines</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-color bg-info rounded me-2" style="width: 16px; height: 16px;"></div>
                                <span class="fs-6">Presentations</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="copyright">
                    <p>Copyright © 2025 Colorlib. All rights reserved. Template by <a href="https://colorlib.com"
                            rel="nofollow" target="_blank">Colorlib</a>.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- FullCalendar v6.1.11 -->
    {{-- <script src="vendor/fullcalendar-6.1.11/fullcalendar.min.js"></script> --}}
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     const calendarEl = document.getElementById('calendar');

        //     if (calendarEl) {
        //         // Sample events data using modern date handling
        //         const today = new Date();
        //         const tomorrow = new Date(today);
        //         tomorrow.setDate(today.getDate() + 1);

        //         const nextWeek = new Date(today);
        //         nextWeek.setDate(today.getDate() + 7);

        //         const events = [{
        //                 title: 'Team Meeting',
        //                 start: '2025-01-25T09:00:00',
        //                 end: '2025-01-25T10:30:00',
        //                 backgroundColor: '#007bff',
        //                 borderColor: '#007bff',
        //                 textColor: '#ffffff',
        //                 extendedProps: {
        //                     type: 'meeting',
        //                     description: 'Weekly team sync meeting'
        //                 }
        //             },
        //             {
        //                 title: 'Project Deadline',
        //                 start: '2025-01-28',
        //                 allDay: true,
        //                 backgroundColor: '#dc3545',
        //                 borderColor: '#dc3545',
        //                 textColor: '#ffffff',
        //                 extendedProps: {
        //                     type: 'deadline',
        //                     description: 'Final submission deadline'
        //                 }
        //             },
        //             {
        //                 title: 'Client Presentation',
        //                 start: '2025-02-02T14:00:00',
        //                 end: '2025-02-02T15:30:00',
        //                 backgroundColor: '#17a2b8',
        //                 borderColor: '#17a2b8',
        //                 textColor: '#ffffff',
        //                 extendedProps: {
        //                     type: 'presentation',
        //                     description: 'Quarterly review presentation'
        //                 }
        //             },
        //             {
        //                 title: 'Doctor Appointment',
        //                 start: '2025-01-30T11:00:00',
        //                 end: '2025-01-30T12:00:00',
        //                 backgroundColor: '#ffc107',
        //                 borderColor: '#ffc107',
        //                 textColor: '#000000',
        //                 extendedProps: {
        //                     type: 'appointment',
        //                     description: 'Annual health checkup'
        //                 }
        //             },
        //             {
        //                 title: 'Development Task',
        //                 start: '2025-01-27T10:00:00',
        //                 end: '2025-01-27T16:00:00',
        //                 backgroundColor: '#28a745',
        //                 borderColor: '#28a745',
        //                 textColor: '#ffffff',
        //                 extendedProps: {
        //                     type: 'task',
        //                     description: 'Feature implementation'
        //                 }
        //             },
        //             {
        //                 title: 'Conference Call',
        //                 start: '2025-02-05T15:00:00',
        //                 end: '2025-02-05T16:00:00',
        //                 backgroundColor: '#007bff',
        //                 borderColor: '#007bff',
        //                 textColor: '#ffffff',
        //                 extendedProps: {
        //                     type: 'meeting',
        //                     description: 'International team sync'
        //                 }
        //             }
        //         ];

        //         // Initialize FullCalendar v6+
        //         const calendar = new FullCalendar.Calendar(calendarEl, {
        //             initialView: 'dayGridMonth',
        //             headerToolbar: {
        //                 left: 'prev,next today',
        //                 center: 'title',
        //                 right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        //             },
        //             height: 'auto',
        //             events: events,
        //             eventDisplay: 'block',
        //             dayMaxEvents: 3,
        //             moreLinkText: 'more',

        //             // Event click handler
        //             eventClick: function(info) {
        //                 const event = info.event;
        //                 const props = event.extendedProps;

        //                 alert(`Event: ${event.title}\n` +
        //                     `Type: ${props.type || 'N/A'}\n` +
        //                     `Description: ${props.description || 'No description'}\n` +
        //                     `Start: ${event.start ? event.start.toLocaleString() : 'N/A'}\n` +
        //                     `End: ${event.end ? event.end.toLocaleString() : 'N/A'}`);
        //             },

        //             // Date click handler for adding new events
        //             dateClick: function(info) {
        //                 const title = prompt('Enter event title:');
        //                 if (title) {
        //                     calendar.addEvent({
        //                         title: title,
        //                         start: info.date,
        //                         allDay: info.allDay,
        //                         backgroundColor: '#6c757d',
        //                         borderColor: '#6c757d',
        //                         textColor: '#ffffff'
        //                     });
        //                 }
        //             },

        //             // Responsive behavior
        //             windowResize: function() {
        //                 calendar.updateSize();
        //             }
        //         });

        //         calendar.render();

        //         // Store calendar instance globally for external access
        //         window.calendarInstance = calendar;
        //     }
        // });

        // // Function for "Add Event" button
        // function addNewEvent() {
        //     const title = prompt('Enter event title:');
        //     if (title && window.calendarInstance) {
        //         const today = new Date();
        //         window.calendarInstance.addEvent({
        //             title: title,
        //             start: today,
        //             backgroundColor: '#6c757d',
        //             borderColor: '#6c757d',
        //             textColor: '#ffffff',
        //             extendedProps: {
        //                 type: 'custom',
        //                 description: 'User created event'
        //             }
        //         });
        //     }
        // }
    </script>
@endsection
