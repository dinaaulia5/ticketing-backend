<?php

namespace App\Http\Controllers\Asset;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\AssetAssignmentRequest;
use App\Http\Resources\Asset\AssetAssignmentResource;
use App\Models\Asset\Asset;
use App\Models\Asset\AssetAssignment;
use App\Supports\Pages\Asset\AssetAssignmentPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Throwable;

class AssetAssignmentController extends Controller
{
    public function index(): Response
    {
        $assignments = AssetAssignment::query()
            ->select([
                'id',
                'asset_id',
                'employee_id',
                'assigned_by',
                'assigned_at',
                'returned_at',
                'notes',
                'created_at',
            ])
            ->with([
                'asset:id,asset_code,name,brand,model',
                'employee:id,name,nup',
                'assignedBy:id,name',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Asset/Assignment/Index', [
            'pageSettings' => fn() => AssetAssignmentPage::index(),

            'assignments' => fn() => AssetAssignmentResource::collection($assignments)
                ->additional([
                    'meta' => [
                        'has_pages' => $assignments->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],

            'items' => fn() => AssetAssignmentPage::breadcrumbs(),
        ]);
    }

    /**
     * Store a new asset assignment.
     */
    public function store(
        AssetAssignmentRequest $request
    ): RedirectResponse {
        try {
            DB::transaction(function () use ($request) {
                $asset = Asset::query()
                    ->lockForUpdate()
                    ->findOrFail($request->asset_id);

                if ($asset->status !== 'available') {
                    throw ValidationException::withMessages([
                        'asset_id' => 'Aset tidak tersedia untuk diserahkan.',
                    ]);
                }

                $activeAssignment = AssetAssignment::query()
                    ->where('asset_id', $asset->id)
                    ->whereNull('returned_at')
                    ->exists();

                if ($activeAssignment) {
                    throw ValidationException::withMessages([
                        'asset_id' => 'Aset masih memiliki assignment aktif.',
                    ]);
                }

                AssetAssignment::create([
                    'asset_id' => $asset->id,
                    'employee_id' => $request->employee_id,
                    'assigned_by' => auth()->id(),
                    'assigned_at' => $request->assigned_at,
                    'notes' => $request->notes,
                ]);

                $asset->update([
                    'status' => 'assigned',
                ]);
            });

            flashMessage(
                MessageType::CREATED->message('Penyerahan Aset')
            );

            return to_route('asset-assignments.index');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-assignments.index');
        }
    }

    /**
     * Update an assignment, primarily for recording asset returns.
     */
    public function update(
        AssetAssignmentRequest $request,
        string $id
    ): RedirectResponse {
        try {
            DB::transaction(function () use ($request, $id) {
                $assignment = AssetAssignment::query()
                    ->lockForUpdate()
                    ->findOrFail($id);

                $asset = Asset::query()
                    ->lockForUpdate()
                    ->findOrFail($assignment->asset_id);

                // Asset and employee identity cannot be changed
                // after the assignment has been created.
                if (
                    $request->asset_id !== $assignment->asset_id ||
                    $request->employee_id !== $assignment->employee_id
                ) {
                    throw ValidationException::withMessages([
                        'asset_id' => 'Aset dan karyawan pada riwayat penyerahan tidak dapat diganti.',
                    ]);
                }

                if ($assignment->returned_at && !$request->returned_at) {
                    throw ValidationException::withMessages([
                        'returned_at' => 'Riwayat pengembalian yang sudah tercatat tidak dapat dibatalkan.',
                    ]);
                }

                $assignment->update([
                    'returned_at' => $request->returned_at,
                    'notes' => $request->notes,
                ]);

                if ($assignment->returned_at) {
                    $asset->update([
                        'status' => $asset->condition === 'damaged'
                            ? 'maintenance'
                            : 'available',
                    ]);
                }
            });

            flashMessage(
                MessageType::UPDATED->message('Penyerahan Aset')
            );

            return to_route('asset-assignments.index');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-assignments.index');
        }
    }

    /**
     * Assignment history cannot be deleted.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            AssetAssignment::query()->findOrFail($id);

            flashMessage(
                'Riwayat penyerahan aset tidak dapat dihapus agar histori aset tetap terjaga.',
                'error'
            );

            return to_route('asset-assignments.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-assignments.index');
        }
    }
}
