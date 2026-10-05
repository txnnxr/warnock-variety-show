<x-mail::message>
# You're in, {{ $invite->first_name }}!

Your request to come to **{{ $invite->show->name }}** on {{ $invite->show->date->format('l, F j, Y \a\t g:ia') }} was approved.

@if($invite->canSeeAddress())
**Address:** {{ $invite->show->address }}
@elseif($invite->response_status === \App\Models\Invite::WAITLIST)
The show is full right now, so you're on the waitlist. If a seat opens up you'll move in automatically and we'll email you.
@endif

<x-mail::button :url="route('invites.thank-you', $invite)">
View my RSVP
</x-mail::button>

Warnock Variety Show
</x-mail::message>
