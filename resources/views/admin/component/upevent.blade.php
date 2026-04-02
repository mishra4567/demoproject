<!-- UPCOMING EVENTS -->
<div class="au-card m-b-30">
    <div class="au-card-inner">
        <div class="d-flex justify-content-between align-items-center m-b-30">
            <h3 class="title-2 mb-0">Upcoming Events</h3>
            <div class="d-flex gap-2">
                <button id="prev-event" class="btn btn-sm btn-outline-secondary" title="Previous">
                    <i class="zmdi zmdi-chevron-left"></i>
                </button>
                <button id="next-event" class="btn btn-sm btn-outline-secondary" title="Next">
                    <i class="zmdi zmdi-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="upcoming-events">
            @forelse ($upcomingEvents as $index => $event)
                <div class="event-item d-flex align-items-start mb-3 p-3 border rounded"
                    data-index="{{ $index }}" data-id="{{ $event->id }}" data-title="{{ $event->title }}"
                    data-type="{{ $event->type }}" data-description="{{ $event->description }}"
                    data-start="{{ \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i') }}"
                    data-end="{{ \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i') }}"
                    data-status="{{ $event->status }}" data-bg="{{ $event->backgroundcolor }}"
                    data-border="{{ $event->bordercolor }}" data-text="{{ $event->textcolor }}"
                    style="cursor: pointer;">
                    <div class="event-date text-center me-3">
                        <div class="fs-6 fw-bold" style="color: {{ $event->backgroundcolor }}">
                            {{ \Carbon\Carbon::parse($event->start_time)->format('M') }}
                        </div>
                        <div class="fs-4 fw-bold">
                            {{ \Carbon\Carbon::parse($event->start_time)->format('d') }}
                        </div>
                    </div>
                    <div class="event-details flex-grow-1">
                        <h6 class="mb-1">{{ $event->title }}</h6>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                        </small>
                        <div class="mt-1">
                            <span class="badge" style="background-color: {{ $event->backgroundcolor }}">
                                {{ ucfirst($event->type) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-3">
                    <small>No upcoming events</small>
                </div>
            @endforelse
        </div>

        @if ($upcomingEvents->count() > 0)
            <div class="text-center mt-2">
                <small class="text-muted" id="event-counter"></small>
            </div>
        @endif
    </div>
</div>
