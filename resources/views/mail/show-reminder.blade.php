<x-mail::message>
# See you soon, {{ $invite->first_name }}!

**{{ $invite->show->name }}** is on {{ $invite->show->date->format('l, F j, Y \a\t g:ia') }}.

**Address:** {{ $invite->show->address }}

<x-mail::button :url="route('invites.calendar', $invite)">
Add to Calendar
</x-mail::button>

Plans changed? [Update your RSVP]({{ $invite->link }}?change=1) so someone on the waitlist can take your seat.

Warnock Variety Show
</x-mail::message>
