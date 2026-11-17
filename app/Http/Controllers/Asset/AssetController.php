<?php

namespace App\Http\Controllers\Asset;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\AssetRequest;
use App\Http\Resources\Asset\AssetCategoryResource;
use App\Http\Resources\Asset\AssetResource;
use App\Http\Resources\Asset\LocationResource;
use App\Models\Asset\Asset;
use App\Models\Asset\AssetCategory;
use App\Models\Asset\Location;
use App\Services\Asset\AssetAttachmentService;
use App\Supports\Pages\Asset\AssetPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Throwable;

class AssetController extends Controller
{
    public function index(): Response
    {

        $assets = Asset::query()
            ->with([
                'category:id,code,name',
                'location:id,location_name',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        $categories = AssetCategory::query()
            ->withCount('assets')
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();


        $locations = Location::query()
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

            'categories' => fn() => AssetCategoryResource::collection($categories)
                ->additional([
                    'meta' => [
                        'has_pages' => $categories->hasPages(),
                    ],
                ]),

            'locations' => fn() => LocationResource::collection($locations)
                ->additional([
                    'meta' => [
                        'has_pages' => $locations->hasPages(),
                    ],
                ]),



            'state' => fn() => [
                // Asset
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',

                // Category
                'category_page' => request()->category_page ?? 1,
                'category_search' => request()->category_search ?? '',
                'category_load' => request()->category_load ?? 10,
                'category_field' => request()->category_field ?? 'created_at',
                'category_direction' => request()->category_direction ?? 'desc',

                // Location
                'location_page' => request()->location_page ?? 1,
                'location_search' => request()->location_search ?? '',
                'location_load' => request()->location_load ?? 10,
                'location_field' => request()->location_field ?? 'created_at',
                'location_direction' => request()->location_direction ?? 'desc',
            ],

            'items' => fn() => AssetPage::breadcrumbs(),
        ]);
    }

    public function store(AssetRequest $request, AssetAttachmentService $attachmentService): RedirectResponse
    {
        $uploadedFiles = [];

        try {
            DB::transaction(function () use ($request, $attachmentService, &$uploadedFiles) {
                $user = Auth::user();

                $asset = Asset::create([
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

                if ($request->hasFile('attachments')) {
                    $uploadedFiles = $attachmentService->store(
                        $asset,
                        $request->file('attachments'),
                        $user
                    );
                }
            });

            flashMessage(MessageType::CREATED->message('Aset'));
            return to_route('assets.index', ['tab' => 'asset']);
        } catch (Throwable $e) {
            $attachmentService->deleteFiles($uploadedFiles);
            report($e);

            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back()->withInput();
        }
    }

    public function update(AssetRequest $request, string $id): RedirectResponse
    {
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
