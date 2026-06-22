<?php

namespace App\Livewire\Gudang;

use Livewire\Component;

class Scanner extends Component
{
    public string $scanResult = '';
    public string $scanMessage = '';
    public string $scanStatus = '';
    public string $assetName = '';

    public function render()
    {
        return view('livewire.gudang.scanner')
            ->layout('layouts.scanner', ['title' => 'QR Scanner']);
    }
}
