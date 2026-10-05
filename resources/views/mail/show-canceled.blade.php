<x-mail::message>
# The show is canceled

Hi {{ $invite->first_name }}, we're sorry to say **{{ $invite->show->name }}** on {{ $invite->show->date->format('l, F j') }} is canceled.

@if($note)
<x-mail::panel>
{{ $note }}
</x-mail::panel>
@endif

You don't need to do anything. We hope to see you at the next one.

<x-mail::button :url="route('shows.archive')">
See past shows
</x-mail::button>

Warnock Variety Show
</x-mail::message>
