<?php

namespace Sandhu\NearestStoreRedirect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Core\Models\Channel;

class LocationController
{
    public function getNearestStore(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        if (!$lat || !$lng) {
            return response()->json(['redirect_url' => null]);
        }

        $sources = InventorySource::all();

        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($sources as $source) {
            if ($source->latitude && $source->longitude) {
                $distance = $this->haversineDistance($lat, $lng, $source->latitude, $source->longitude);
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearest = $source;
                }
            }
        }

        if ($nearest) {
            // Find the channel linked to this inventory source
            $channelId = DB::table('channel_inventory_sources')
                ->where('inventory_source_id', $nearest->id)
                ->value('channel_id');

            if ($channelId) {
                $channel = Channel::find($channelId);
                if ($channel && $channel->hostname) {
                    // Ensure hostname has protocol
                    $url = $channel->hostname;
                    if (!preg_match('/^https?:\/\//', $url)) {
                        $url = 'https://' . $url;
                    }

                    return response()->json([
                        'redirect_url' => $url
                    ]);
                }
            }
        }

        return response()->json(['redirect_url' => null]);
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
