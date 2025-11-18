<div class="alert-message" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false }, 5000)">
  @switch($type)
    @case('success')
      <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-5 text-sm flex items-center justify-between">
        <span>{{ $message }}</span>
        @if($dismissible)
          <button wire:click="dismiss" class="text-green-600 hover:text-green-800">&times;</button>
        @endif
      </div>
    @break

    @case('error')
      <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-5 text-sm flex items-center justify-between">
        <span>{{ $message }}</span>
        @if($dismissible)
          <button wire:click="dismiss" class="text-red-600 hover:text-red-800">&times;</button>
        @endif
      </div>
    @break

    @case('warning')
      <div class="bg-yellow-100 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-5 text-sm flex items-center justify-between">
        <span>{{ $message }}</span>
        @if($dismissible)
          <button wire:click="dismiss" class="text-yellow-600 hover:text-yellow-800">&times;</button>
        @endif
      </div>
    @break

    @case('info')
      <div class="bg-blue-100 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-5 text-sm flex items-center justify-between">
        <span>{{ $message }}</span>
        @if($dismissible)
          <button wire:click="dismiss" class="text-blue-600 hover:text-blue-800">&times;</button>
        @endif
      </div>
    @break
  @endswitch
</div>
