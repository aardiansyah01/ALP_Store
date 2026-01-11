<div>
    <div class="max-w-screen-md mx-auto">

        <div class="mb-3">
            <label class="form-label fw-semibold">{{ __('ui.pos_code') }}</label>

            <div class="input-group">
                <input
                    wire:model.defer="destination"
                    class="form-control"
                    placeholder="{{ __('ui.enter_pos_code') }}"
                >
                <button
                    type="button"
                    wire:click="searchDestination"
                    class="btn btn-primary"
                >
                    {{ __('ui.find') }}
                </button>
            </div>
        </div>


        {{-- lopping lokasi --}}
        @if(!empty($resultDestinations))
            <div class="mb-4">
                <h6 class="fw-semibold">{{ __('ui.find_location') }}</h6>

                @foreach($resultDestinations as $destination)
                @php
                    $isSelected = $selectedDestinationId === $destination['id'];
                @endphp

                <button
                    type="button"
                    wire:click="searchPrice({{ $destination['id'] }})"
                    class="list-group-item list-group-item-action mb-1
                        {{ $isSelected ? 'active' : '' }}"
                >
                    <div class="d-flex justify-content-between">
                        <span>{{ $destination['label'] }}</span>
                        @if($isSelected)
                            <span class="badge bg-light text-primary">{{ __('ui.true') }}</span>
                        @endif
                    </div>
                </button>
                @endforeach
            </div>
        @endif


        {{-- alert error --}}
        @if($errorMessage)
            <div class="alert alert-danger mt-2">
                {{ $errorMessage }}
            </div>
        @endif

        {{-- looping harga --}}
        @if(!empty($prices))
            <div class="mt-4">
                <h6 class="fw-semibold mb-3">{{ __('ui.shipping') }}</h6>

                <div class="row g-2">
                    @foreach($prices as $price)
                    @php
                        $key = $price['name'].'-'.$price['service'];
                        $isSelected = $selectedKey === $key;
                    @endphp

                    <div class="col-md-6">
                        <button
                            type="button"
                            wire:click="selectShipping(
                                '{{ $key }}',
                                '{{ $price['name'].' - '.$price['service'].' - '.$price['etd'] }}',
                                {{ $price['cost'] }}
                            )"
                            class="w-100 text-start p-3 rounded border
                            {{ $isSelected ? 'border-primary bg-primary-subtle' : 'border-secondary-subtle bg-white' }}"
                        >
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $price['name'] }}</div>
                                    <small class="text-muted">
                                        {{ $price['service'] }} • {{ $price['etd'] }}
                                    </small>
                                </div>

                                <div class="fw-bold text-end">
                                    Rp {{ number_format($price['cost']) }}
                                    @if($isSelected)
                                        <div class="text-primary small">✔ {{ __('ui.true') }}</div>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <input type="hidden" name="shipping_service" value="{{ $selectedService }}">
    <input type="hidden" name="shipping_cost" value="{{ $selectedCost }}">

</div>


