<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class AssetCategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::query()
            ->select([
                'id',
                'code',
                'name',
                'description',
                'created_at',
            ])
            ->withCount('subsystems')
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Master/Category/Index', [
            'pageSettings' => fn() => CategorySubsystemPage::index(),

            'categories' => fn() => CategoryResource::collection($categories)
                ->additional([
                    'meta' => [
                        'has_pages' => $categories->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],

            'items' => fn() => CategorySubsystemPage::breadcrumbs(),
        ]);
    }
    public function show(string $id): Response
    {
        $category = Category::query()
            ->findOrFail($id);

        $subsystems = $category->subsystems()
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Master/Category/Detail', [
            'pageSettings' => fn() => CategorySubsystemPage::index(),

            'category' => fn() => (new CategoryResource($category))->resolve(),

            'subsystems' => fn() => SubsystemResource::collection($subsystems)
                ->additional([
                    'meta' => [
                        'has_pages' => $subsystems->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],

            'items' => fn() => [
                ...CategorySubsystemPage::breadcrumbs(),
                [
                    'label' => 'Detail Kategori',
                ],
            ],
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            Category::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description
            ]);

            flashMessage(MessageType::CREATED->message('Kategori'));
            return to_route('categories.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('categories.index');
        }
    }


    public function update(CategoryRequest $request, string $id): RedirectResponse
    {
        try {
            $category = Category::query()->findOrFail($id);

            $category->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
            ]);

            flashMessage(MessageType::UPDATED->message('Kategori'));

            return to_route('categories.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('categories.index');
        }
    }



    public function destroy(string $id)
    {
        try {
            $category = Category::query()->findOrFail($id);
            $category->delete();

            flashMessage(
                MessageType::DELETE->message('Kategori')
            );

            return to_route('categories.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');

            return to_route('categories.index');
        }
    }
}
