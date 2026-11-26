<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUlasanRequest;
use App\Models\Antrian;
use Illuminate\Http\RedirectResponse;

class UlasanController extends Controller
{
    public function store(StoreUlasanRequest $request, Antrian $antrian): RedirectResponse
    {
        $data = $request->validated();

        $ulasan = $antrian->ulasan()->firstOrCreate([], [
            'rating' => $data['rating'],
            'komentar' => $data['komentar'] ?? null,
        ]);

        return redirect()
            ->route('queue.show', ['antrian' => $antrian])
            ->with('status', $ulasan->wasRecentlyCreated
                ? __('review.flash.created')
                : __('review.flash.exists'));
    }
}
