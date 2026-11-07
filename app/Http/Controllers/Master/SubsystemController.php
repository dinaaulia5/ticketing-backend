<?php

namespace App\Http\Controllers\Master;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\SubsystemRequest;
use App\Http\Resources\Master\SubsystemResource;
use App\Models\Master\Subsystem;
use App\Supports\Pages\Master\CategorySubsystemPage;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Throwable;

class SubsystemController extends Controller
{
    public function index(): Response
    {
        $categories = Subsystem::query()->with('category')
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Master/Category/Index', [
            'pageSettings' => fn() => CategorySubsystemPage::index(),

            'categories' => fn() => SubsystemResource::collection($categories)
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

    public function store(SubsystemRequest $request): RedirectResponse
    {
        try {
            $subsystem = Subsystem::create([
                'category_id' => $request->category_id,
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
            ]);

            flashMessage(MessageType::CREATED->message('Subsystem'));

            return to_route('categories.show', $subsystem->category_id);
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );
            return back();
        }
    }


    public function update(SubsystemRequest $request, string $id): RedirectResponse
    {
        try {
            $subsystem = Subsystem::query()->findOrFail($id);

            $subsystem->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
            ]);

            flashMessage(MessageType::UPDATED->message('Subsystem'));

            return to_route('categories.show', $subsystem->category_id);
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return back();
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = Subsystem::query()->findOrFail($id);
            $category->delete();

            flashMessage(
                MessageType::DELETE->message('Subsystem')
            );

            return to_route('subsystems.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');

            return to_route('subsystems.index');
        }
    }
}
