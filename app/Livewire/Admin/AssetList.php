<?php

namespace App\Livewire\Admin;

use App\Models\Asset;
use Livewire\Component;
use Livewire\WithPagination;

class AssetList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $categoryFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Asset::query();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->statusFilter) {
            $query->byStatus($this->statusFilter);
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(10);

        $categories = Asset::select('category')->distinct()->pluck('category');

        return view('livewire.admin.asset-list', [
            'assets' => $assets,
            'categories' => $categories,
        ])->layout('layouts.app', ['title' => 'Asset Management']);
    }
}
