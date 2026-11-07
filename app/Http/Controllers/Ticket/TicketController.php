<?php

namespace App\Http\Controllers\Ticket;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketRequest;
use App\Http\Resources\Master\CategoryResource;
use App\Http\Resources\Ticket\TicketResource;
use App\Models\Master\Category;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketHistory;
use App\Services\Ticket\TicketAttachmentService;
use App\Supports\Pages\Ticket\TicketPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Throwable;

class TicketController extends Controller
{

    public function index(): Response
    {
        $query = Ticket::query();

        // Data Statistik
        $statistics = [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->where('status', 'open')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'resolved' => (clone $query)->where('status', 'resolved')->count(),
            'closed' => (clone $query)->where('status', 'closed')->count(),
        ];

        // Data Kategori dan Subsystem
        $categories = Category::query()
            ->select(['id', 'name'])
            ->with([
                'subsystems:id,category_id,name',
            ])
            ->get();

        $tickets = Ticket::query()
            ->with([
                'user',
                'creator',
                'category',
                'subsystem',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Tickets/Index', [
            'pageSettings' => fn() => TicketPage::index(),

            'tickets' => fn() => TicketResource::collection($tickets)
                ->additional([
                    'meta' => [
                        'has_pages' => $tickets->hasPages(),
                    ],
                ]),

            'statistics' => fn() => $statistics,

            'categories' => fn() => $categories,

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
            ],

            'items' => fn() => TicketPage::breadcrumbs(),
        ]);
    }

    public function show(Ticket $ticket): Response
    {

        $ticket->load([
            'user',
            'creator',
            'category',
            'subsystem',
            'attachments.uploader',
            'assignments.technician',
            'assignments.assigner',
            'comments.user',
            'histories.user',
            'serviceRequest',
        ]);

        return inertia('Tickets/Detail', [
            'ticket' => fn() => (new TicketResource($ticket))->resolve(),
            'pageSettings' => fn() => TicketPage::show($ticket),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],

            'items' => fn() => TicketPage::showBreadcrumbs()
        ]);
    }


    public function store(TicketRequest $request, TicketAttachmentService $attachmentService): RedirectResponse
    {
        $uploadedFiles = [];

        try {
            DB::transaction(function () use ($request, $attachmentService, &$uploadedFiles) {
                $user = Auth::user();

                $ticket = Ticket::create([
                    'ticket_number' => Ticket::generateTicketNumber(),
                    'user_id' => $user->id,
                    'created_by' => $user->id,
                    'ticket_type' => $request->ticket_type,
                    'category_id' => $request->category_id,
                    'subsystem_id' => $request->subsystem_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'impact' => $request->impact,
                    'urgency' => $request->urgency,
                    'priority' => null,
                    'status' => 'open',
                    'source' => 'web',
                ]);


                TicketHistory::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'activity' => 'created',
                    'field' => 'status',
                    'old_value' => null,
                    'new_value' => 'open',
                    'description' => 'Tiket berhasil dibuat.',
                ]);

                // Uploaded Files
                if ($request->hasFile('attachments')) {
                    $uploadedFiles = $attachmentService->store($ticket, $request->file('attachments'), $user);
                }
            });

            flashMessage(MessageType::CREATED->message('Ticket'));
            return to_route('tickets.index');
        } catch (Throwable $e) {
            $attachmentService->deleteFiles($uploadedFiles);

            report($e);
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back()->withInput();
        }
    }


    public function update(TicketRequest $request, string $id): RedirectResponse
    {
        try {
            $ticket = Ticket::query()->findOrFail($id);

            $ticket->update([
                'ticket_type' => $request->ticket_type,
                'category_id' => $request->category_id,
                'subsystem_id' => $request->subsystem_id,
                'title' => $request->title,
                'description' => $request->description,
                'impact' => $request->impact,
                'urgency' => $request->urgency,
            ]);

            flashMessage(MessageType::UPDATED->message('Tiket'));

            return to_route('tickets.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('tickets.index');
        }
    }



    public function destroy(string $id)
    {
        try {
            $ticket = Ticket::query()->findOrFail($id);
            $ticket->delete();

            flashMessage(
                MessageType::DELETE->message('Tiket')
            );

            return to_route('tickets.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('tickets.index');
        }
    }
}
