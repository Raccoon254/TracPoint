<div class="mt-44">
    <input type="text" wire:model.live="search" placeholder="Search users..."/>
    @foreach($users as $user)
        <div>
            <p>{{ $user->name }}</p>
            <p>{{ $user->email }}</p>
        </div>
    @endforeach
</div>
