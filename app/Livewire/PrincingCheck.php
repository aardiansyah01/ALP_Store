<?php

namespace App\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\On;

class PrincingCheck extends Component
{

    public string $destination;

    public array $resultDestinations = [];
    public array $prices = [];

    public ?string $errorMessage = null;

    public ?string $selectedService = null;
    public ?int $selectedCost = null;
    public ?string $selectedKey = null;
    public ?int $selectedDestinationId = null;

    public function searchDestination()
    {
        $this->errorMessage = null;
        $this->resultDestinations = [];
        $this->prices = [];

        $response = Http::withHeader('key', '62PfrSCG85fa63e3977c50a93QvhFcpW')
            ->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $this->destination,
                'limit' => 5,
                'offset' => 0,
            ])
            ->json('data');

        if (empty($response)) {
            $this->errorMessage = 'Kode pos atau daerah tidak ditemukan';
            return;
        }

        $this->resultDestinations = $response;
    }


    public function searchPrice (int $destinationId)
    {
        $this->selectedDestinationId = $destinationId;

        $this->prices = Http::withHeader('key', '62PfrSCG85fa63e3977c50a93QvhFcpW')
            ->asForm()
            ->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => 67155,
                'destination' => $destinationId,
                'weight' => 1000,
                'courier' => 'jne:sicepat:jnt',
                'price' => 'lowest'
            ])
            ->json('data');
    }
    
    public function selectShipping(string $key, string $service, int $cost)
    {
        $this->selectedKey = $key;
        $this->selectedService = $service;
        $this->selectedCost = $cost;

        $this->dispatch('shippingSelected', cost: $cost);
    }

    public function render()
    {
        return view('livewire.princing-check');
    }
}
