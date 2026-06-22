<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\LogAssetActivity;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetScanController extends Controller
{
    /**
     * Handle the incoming scan request from mobile PWA.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'asset_code' => ['required', 'string', 'max:255'],
            'current_location' => ['nullable', 'string', 'max:255'],
        ]);

        $asset = Asset::where('asset_code', $validated['asset_code'])->firstOrFail();

        $changes = [];

        if (!empty($validated['current_location']) && $asset->location !== $validated['current_location']) {
            $asset->location = $validated['current_location'];
            $changes[] = 'location';
        }

        if ($asset->status === 'available') {
            $asset->status = 'assigned';
            $changes[] = 'status';
        }

        if (!empty($changes)) {
            $asset->save();
        }

        // Dispatch async job to log activity (non-blocking)
        LogAssetActivity::dispatch(
            $asset,
            $request->user()?->id,
            'scanned',
            'Asset scanned via QR. ' . (!empty($changes) ? 'Updated: ' . implode(', ', $changes) : 'No changes.')
        );

        return response()->json([
            'status' => 'success',
            'asset_name' => $asset->name,
            'asset_code' => $asset->asset_code,
            'message' => 'Verified',
        ]);
    }
}
