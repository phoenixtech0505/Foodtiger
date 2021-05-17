<div class="card-body">
    <div class="timeline timeline-one-side" id="status-history" data-timeline-content="axis" data-timeline-axis-style="dashed">
    @foreach($order->stakeholders as $key=>$stakeholder)
        <div class="timeline-block">
            <span class="timeline-step badge-success">
                <i class="ni ni-bell-55"></i>
            </span>
            <div class="timeline-content">
                <div class="d-flex justify-content-between pt-1">
                    <div>
                        <span class="text-muted text-sm font-weight-bold">{{ __($statuses[$stakeholder->pivot->status_id]) }}</span>
                    </div>
                    <div class="text-right">
                        <small class="text-muted"><i class="fas fa-clock mr-1"></i>{{ $stakeholder->pivot->created_at->format(config('settings.datetime_display_format')) }}</small>
                    </div>
                </div>
                <h6 class="text-sm mt-1 mb-0">{{ __('Status from') }}: {{$stakeholder->name }}</h6>
            </div>
        </div>
    @endforeach
    </div>
</div>