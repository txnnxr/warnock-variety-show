<x-mail::message>
# The lineup is here!

Hi {{ $invite->first_name }}, here's who's taking the stage at **{{ $invite->show->name }}** on {{ $invite->show->date->format('l, F j \a\t g:ia') }}:

<x-mail::table>
| # | Act | Performer |
|:-:|:----|:----------|
@foreach($lineup as $act)
| {{ $loop->iteration }} | *{{ $act->exhibition_description }}* | {{ $act->person->name }} |
@endforeach
</x-mail::table>

@if($invite->response_status === \App\Models\Invite::MAYBE)
You said "maybe". Does this lineup tip the scales?
@elseif(str_starts_with((string) $invite->response_status, 'PENDING'))
We haven't heard from you yet. Can you make it?
@elseif($invite->response_status === \App\Models\Invite::WAITLIST)
You're on the waitlist. If a seat opens up you'll move in automatically and we'll email you.
@else
See you there!
@endif

<x-mail::button :url="$invite->response_status === \App\Models\Invite::ATTENDING ? route('invites.thank-you', $invite) : $invite->link . '?change=1'">
{{ $invite->response_status === \App\Models\Invite::ATTENDING ? 'View my RSVP' : 'RSVP' }}
</x-mail::button>

Warnock Variety Show
</x-mail::message>
