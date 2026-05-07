<?php

namespace App\Livewire;

use App\Models\Option;
use App\Models\Poll;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class Polls extends Component
{
    use WithPagination;

    protected $listeners = ['pollCreated' => 'render'];

    public function render(Request $request)
    {
        $polls = Poll::with('options.votes')->latest()->paginate(3);
        return view('livewire.polls', ['polls' => $polls]);
    }

    public function vote($optionId)
    {
        $option = Option::findOrFail($optionId);
        $option->votes()->create();
    }
}
