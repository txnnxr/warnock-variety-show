<?php

namespace App\Http\Controllers;

use App\Models\Show;

class CardController extends Controller
{
    /**
     * The business card QR code points here: send people to the next show.
     */
    public function show()
    {
        $show = Show::upcoming()->where('canceled', false)->orderBy('date')->first();

        if (! $show) {
            return redirect('/');
        }

        return redirect(route('shows.show', ['show' => $show]));
    }
}
