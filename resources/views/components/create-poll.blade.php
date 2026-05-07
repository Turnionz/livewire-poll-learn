<?php

use Livewire\Component;
use App\Models\Poll;

new class extends Component
{
    public $title = '';
    public $options = [''];

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function createPoll()
    {
        Poll::create([
            'title' => $this->title
        ])->options()->createMany(
            collect($this->options)
                ->map(fn($option) => ['name' => $option])
                ->all()
        );

        $this->reset(['title', 'options']);
    }
};
?>

<div>
    <form wire:submit.prevent="createPoll">
        <label>Poll title</label>
        <input type="text" wire:model.live="title" />

        <div class="mt-4 mb-4">
            <button class="btn" wire:click.prevent="addOption">Add option</button>
        </div>

        <div>
            @foreach ($options as $index => $option)
                <div class="mb-4">
                    <label>Option {{ $index + 1 }}</label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="options.{{ $index }}"/>
                        <button class="btn" wire:click.prevent="removeOption({{ $index }})">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn">Create poll</button>
    </form>
</div>