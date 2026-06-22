<?php

namespace App\Livewire\Admin;

use App\Models\Asset;
use App\Models\AssetLog;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalAssets = 0;
    public int $availableAssets = 0;
    public int $assignedAssets = 0;
    public int $underRepairAssets = 0;
    public int $brokenAssets = 0;
    public int $disposedAssets = 0;
    public int $recentLogs = 0;

    public function mount(): void
    {
        $this->totalAssets = Asset::count();
        $this->availableAssets = Asset::where('status', 'available')->count();
        $this->assignedAssets = Asset::where('status', 'assigned')->count();
        $this->underRepairAssets = Asset::where('status', 'under_repair')->count();
        $this->brokenAssets = Asset::where('status', 'broken')->count();
        $this->disposedAssets = Asset::where('status', 'disposed')->count();
        $this->recentLogs = AssetLog::whereDate('created_at', today())->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
