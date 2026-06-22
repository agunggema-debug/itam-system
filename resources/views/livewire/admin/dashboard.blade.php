<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-blue-600">{{ $totalAssets }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Aset</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-green-600">{{ $availableAssets }}</div>
            <div class="text-sm text-gray-500 mt-1">Available</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-yellow-600">{{ $assignedAssets }}</div>
            <div class="text-sm text-gray-500 mt-1">Assigned</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-purple-600">{{ $recentLogs }}</div>
            <div class="text-sm text-gray-500 mt-1">Scan Hari Ini</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-xl font-bold text-red-600">{{ $underRepairAssets }}</div>
            <div class="text-sm text-gray-500 mt-1">Under Repair</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-xl font-bold text-red-800">{{ $brokenAssets }}</div>
            <div class="text-sm text-gray-500 mt-1">Broken</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-xl font-bold text-gray-600">{{ $disposedAssets }}</div>
            <div class="text-sm text-gray-500 mt-1">Disposed</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Akses Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.assets') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Kelola Aset
            </a>
            <a href="{{ route('scanner') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                Mulai Stock Opname
            </a>
        </div>
    </div>
</div>
