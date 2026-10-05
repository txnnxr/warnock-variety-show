<x-mail::message>
# Good news, {{ $invite->first_name }}!

A seat opened up and you're off the waitlist for **{{ $invite->show->name }}** on {{ $invite->show->date->format('l, F j, Y \a\t g:ia') }}.

@if($invite->canSeeAddress())
**Address:** {{ $invite->show->address }}
@endif

<x-mail::button :url="route('invites.thank-you', $invite)">
View my RSVP
</x-mail::button>

Can't make it anymore? [Update your RSVP]({{ $invite->link }}?change=1) so the seat goes to the next person.

Warnock Variety Show
</x-mail::message>
