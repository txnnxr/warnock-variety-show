<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Invite
 *
 * @property int $id
 * @property int $show_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $phone
 * @property string|null $email
 * @property string $key
 * @property string $status
 * @property string|null $attendance_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $link
 * @property-read \App\Models\Show $show
 * @method static \Database\Factories\InviteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereAttendanceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereUpdatedAt($value)
 */
	class Invite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MailingList
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Database\Factories\MailingListFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailingList whereUpdatedAt($value)
 */
	class MailingList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RSVP
 *
 * @property int $id
 * @property int $show_id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $phone
 * @property string|null $email
 * @property string $key
 * @property string $status
 * @property string|null $attendance_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Database\Factories\RSVPFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP query()
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereAttendanceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RSVP whereUpdatedAt($value)
 */
	class RSVP extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Schedule
 *
 * @method static \Database\Factories\ScheduleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Show
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $date
 * @property int $max_attendants
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invite[] $invites
 * @property-read int|null $invites_count
 * @method static \Database\Factories\ShowFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Show newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Show newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Show query()
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereMaxAttendants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Show whereUpdatedAt($value)
 */
	class Show extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

