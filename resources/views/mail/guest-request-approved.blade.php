<x-mail::message>
# You're in, {{ $invite->first_name }}!

Your request to come to **{{ $invite->show->name }}** on {{ $invite->show->date->format('l, F j, Y \a\t g:ia') }} was approved.

@if($invite->canSeeAddress())
**Address:** {{ $invite->show->address }}
@endif

<x-mail::button :url="route('invites.thank-you', $invite)">
View my RSVP
</x-mail::button>

Warnock Variety Show
</x-mail::message>
