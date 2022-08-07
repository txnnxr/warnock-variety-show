<?php

namespace App\Http\Livewire;

use Livewire\Component;

class InvitationForm extends Component
{
    public $show;
    public $invitation = [
        'first_name',
        'last_name',
        'has_plus_one_option',
    ];
    public $first_name;
    public $last_name;
    public $has_plus_one_option;

    protected $rules = [
        'first_name' => 'required',
    ];

    public function render()
    {
        return view('livewire.invitation-form');
    }

    public function store()
    {
        action('App\Http\Controllers\InviteController@store', [$this->invitation]);
    }
}
