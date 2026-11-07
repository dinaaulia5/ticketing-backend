<?php

namespace App\Http\Controllers\Ticket;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketCommentRequest;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class TicketCommentController extends Controller
{
    public function store(TicketCommentRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $ticket) {
                $user = Auth::user();

                $ticket->comments()->create([
                    'user_id' => $user->id,
                    'comment' => $request->comment,
                    'is_internal' => $request->boolean('is_internal'),
                ]);

                TicketHistory::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'activity' => 'comment_added',
                    'field' => 'comment',
                    'description' => 'Komentar ditambahkan.',
                ]);
            });

            flashMessage(MessageType::CREATED->message('Komentar'));
            return back();
        } catch (Throwable $e) {
            report($e);
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back()->withInput();
        }
    }
}
