<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Aset</h1>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari asset code, serial number, atau nama..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="available">Available</option>
                    <option value="assigned">Assigned</option>
                    <option value="under_repair">Under Repair</option>
                    <option value="broken">Broken</option>
                    <option value="disposed">Disposed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($assets as $asset)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono text-blue-600">{{ $asset->asset_code }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $asset->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $asset->category }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $asset->serial_number }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $asset->location ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($asset->status === 'available') bg-green-100 text-green-800
                                @elseif($asset->status === 'assigned') bg-blue-100 text-blue-800
                                @elseif($asset->status === 'under_repair') bg-yellow-100 text-yellow-800
                                @elseif($asset->status === 'broken') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $asset->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data aset</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t">
            {{ $assets->links() }}
        </div>
    </div>
</div>
