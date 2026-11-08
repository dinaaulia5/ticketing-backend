<?php

namespace App\Http\Controllers\Asset;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\AssetRequest;
use App\Http\Resources\Asset\AssetResource;
use App\Models\Asset\Asset;
use App\Supports\Pages\Asset\AssetPage;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Throwable;

class AssetController extends Controller
{
    public function index(): Response
    {
        $assets = Asset::query()
            ->select([
                'id',
                'asset_code',
                'name',
                'asset_category_id',
                'brand',
                'model',
                'serial_number',
                'location_id',
                'condition',
                'status',
                'created_at',
            ])
            ->with([
                'category:id,code,name',
                'location:id,location_name',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Asset/Index', [
            'pageSettings' => fn() => AssetPage::index(),

            'assets' => fn() => AssetResource::collection($assets)
                ->additional([
                    'meta' => [
                        'has_pages' => $assets->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],

            'items' => fn() => AssetPage::breadcrumbs(),
        ]);
    }

    public function store(AssetRequest $request): RedirectResponse
    {
        try {
            Asset::create([
                'asset_code' => $request->asset_code,
                'name' => $request->name,
                'asset_category_id' => $request->asset_category_id,
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'processor' => $request->processor,
                'ram' => $request->ram,
                'storage' => $request->storage,
                'os' => $request->os,
                'ip_address' => $request->ip_address,
                'mac_address' => $request->mac_address,
                'location_id' => $request->location_id,
                'purchase_date' => $request->purchase_date,
                'purchase_price' => $request->purchase_price,
                'warranty_expiry' => $request->warranty_expiry,
                'end_of_life' => $request->end_of_life,
                'condition' => $request->condition,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            flashMessage(MessageType::CREATED->message('Aset'));

            return to_route('assets.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('assets.index');
        }
    }

    public function update(
        AssetRequest $request,
        string $id
    ): RedirectResponse {
        try {
            $asset = Asset::query()->findOrFail($id);

            $asset->update([
                'asset_code' => $request->asset_code,
                'name' => $request->name,
                'asset_category_id' => $request->asset_category_id,
                'brand' => $request->brand,
                'model' => $request->model,
                'serial_number' => $request->serial_number,
                'processor' => $request->processor,
                'ram' => $request->ram,
                'storage' => $request->storage,
                'os' => $request->os,
                'ip_address' => $request->ip_address,
                'mac_address' => $request->mac_address,
                'location_id' => $request->location_id,
                'purchase_date' => $request->purchase_date,
                'purchase_price' => $request->purchase_price,
                'warranty_expiry' => $request->warranty_expiry,
                'end_of_life' => $request->end_of_life,
                'condition' => $request->condition,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            flashMessage(MessageType::UPDATED->message('Aset'));

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
            $asset = Asset::query()->findOrFail($id);

            $asset->delete();

            flashMessage(MessageType::DELETE->message('Aset'));

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
