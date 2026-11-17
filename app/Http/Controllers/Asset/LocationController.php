<?php

namespace App\Http\Controllers\Asset;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\LocationRequest;
use App\Http\Resources\Asset\LocationResource;
use App\Models\Asset\Location;
use App\Supports\Pages\Asset\LocationPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Throwable;

class LocationController extends Controller
{
    public function index(): Response
    {
        $locations = Location::query()
            ->select([
                'id',
                'code',
                'location_name',
                'building',
                'floor',
                'room',
                'description',
                'created_at',
            ])
            ->withCount('assets')
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Asset/Location/Index', [
            'pageSettings' => fn() => LocationPage::index(),

            'locations' => fn() => LocationResource::collection($locations)
                ->additional([
                    'meta' => [
                        'has_pages' => $locations->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],

            'items' => fn() => LocationPage::breadcrumbs(),
        ]);
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                Location::create([
                    'code' => $request->code,
                    'location_name' => $request->location_name,
                    'building' => $request->building,
                    'floor' => $request->floor,
                    'room' => $request->room,
                    'description' => $request->description,
                ]);
            });

            flashMessage(MessageType::CREATED->message('Lokasi'));

            return to_route('assets.index', ['tab' => 'location']);
        } catch (Throwable $e) {
            report($e);

            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return back()->withInput();
        }
    }

    public function update(LocationRequest $request, string $id): RedirectResponse
    {
        try {
            $location = Location::query()->findOrFail($id);

            $location->update([
                'code' => $request->code,
                'location_name' => $request->location_name,
                'building' => $request->building,
                'floor' => $request->floor,
                'room' => $request->room,
                'description' => $request->description,
            ]);

            flashMessage(MessageType::UPDATED->message('Lokasi'));

            return to_route('assets.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('assets.index');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $location = Location::query()->findOrFail($id);

            if ($location->assets()->exists()) {
                flashMessage(
                    MessageType::ERROR->message(
                        error: 'Lokasi masih digunakan oleh aset.'
                    ),
                    'error'
                );

                return to_route('assets.index');
            }

            $location->delete();

            flashMessage(MessageType::DELETE->message('Lokasi'));

            return to_route('assets.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('assets.index');
        }
    }
}
