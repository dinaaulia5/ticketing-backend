<?php

namespace App\Http\Controllers\Asset;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\AssetCategoryRequest;
use App\Http\Resources\Asset\AssetCategoryResource;
use App\Models\Asset\AssetCategory;
use App\Supports\Pages\Asset\AssetCategoryPage;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Throwable;

class AssetCategoryController extends Controller
{
    public function index(): Response
    {
        $categories = AssetCategory::query()
            ->select([
                'id',
                'code',
                'name',
                'description',
                'created_at',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Asset/Category/Index', [
            'pageSettings' => fn() => AssetCategoryPage::index(),

            'categories' => fn() => AssetCategoryResource::collection($categories)
                ->additional([
                    'meta' => [
                        'has_pages' => $categories->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],

            'items' => fn() => AssetCategoryPage::breadcrumbs(),
        ]);
    }

    public function store(AssetCategoryRequest $request): RedirectResponse
    {
        try {
            AssetCategory::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ]);

            flashMessage(MessageType::CREATED->message('Kategori Aset'));

            return to_route('asset-categories.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-categories.index');
        }
    }

    public function update(
        AssetCategoryRequest $request,
        string $id
    ): RedirectResponse {
        try {
            $category = AssetCategory::query()->findOrFail($id);

            $category->update([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ]);

            flashMessage(MessageType::UPDATED->message('Kategori Aset'));

            return to_route('asset-categories.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-categories.index');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $category = AssetCategory::query()->findOrFail($id);

            if ($category->assets()->exists()) {
                flashMessage(
                    'Kategori tidak dapat dihapus karena masih digunakan oleh aset.',
                    'error'
                );

                return to_route('asset-categories.index');
            }

            $category->delete();

            flashMessage(MessageType::DELETE->message('Kategori Aset'));

            return to_route('asset-categories.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-categories.index');
        }
    }
}
