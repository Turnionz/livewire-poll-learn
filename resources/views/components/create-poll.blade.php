<?php

use Livewire\Component;
use App\Models\Poll;

new class extends Component
{
    public $title = '';
    public $options = [''];

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'options' => 'required|array|min:2|max:10',
        'options.*' => 'required|min:1|max:255'
    ];

    protected $messages = [
        'options.min' => 'You need to have at least 2 options',
        'options.*.required' => "The option can't be empty"
    ];

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createPoll()
    {
        $this->validate();

        Poll::create([
            'title' => $this->title
        ])->options()->createMany(
            collect($this->options)
                ->map(fn($option) => ['name' => $option])
                ->all()
        );

        $this->reset(['title', 'options']);

        $this->dispatch('pollCreated');
    }
};
?>

<div>
    <form wire:submit.prevent="createPoll">
        <label>Poll title</label>
        <input type="text" wire:model.live="title" />

        @error('title')
            <div class="text-red-500">{{ $message }}</div>
        @enderror

        <div class="mt-4 mb-4">
            <button class="btn" wire:click.prevent="addOption">Add option</button>
        </div>

        <div>
            @error("options")
                <div class="text-red-500">{{ $message }}</div>
            @enderror
            @foreach ($options as $index => $option)
                <div class="mb-4">
                    <label>Option {{ $index + 1 }}</label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="options.{{ $index }}"/>
                        <button class="btn" wire:click.prevent="removeOption({{ $index }})">Remove</button>
                    </div>
                    @error("options.{$index}")
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn">Create poll</button>
    </form>
</div>