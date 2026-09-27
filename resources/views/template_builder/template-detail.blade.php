<div style="margin: 0 auto;">

    {!! $page->html !!}

    <div style="text-align: center; display: flex; justify-content: center; gap: 10px;">
        <a href="{{ route('campaign.contacts.unsubscribe', [$tracker->campaign_id, $tracker->email_id]) }}"
            target="_blank" style="text-decoration: none; color: blue;">
            Unsubscribe
        </a>
        <a href="{{ route('campaign.contacts.unsubscribe', [$tracker->campaign_id, $tracker->email_id]) . '?all=true' }}"
            target="_blank" style="text-decoration: none; color: red;">
            Don't want to receive any emails from {{ config('app.name') }}
        </a>
    </div>

</div>

<img src="{{ route('tracker.emails.store') }}/?tracker={{ $tracker->tracker }}&email_id={{ $tracker->email_id }}&campaign_id={{ $tracker->campaign_id }}&record=OPENED"
    width="1" height="1">
