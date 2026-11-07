<?php

namespace App\Http\Resources\Ticket;

use App\Http\Resources\Master\CategoryResource;
use App\Http\Resources\Master\SubsystemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'user_id' => $this->user_id,
            'created_by' => $this->created_by,
            'ticket_type' => $this->ticket_type,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'subsystem_id' => $this->subsystem_id,
            'subsystem' => new SubsystemResource($this->whenLoaded('subsystem')),
            'attachments' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(function ($attachment) {
                    return (new TicketAttachmentResource($attachment))->resolve();
                })->values();
            }),
            'histories' => $this->whenLoaded('histories', function () {
                return $this->histories->map(function ($histories) {
                    return (new TicketHistoryResource($histories))->resolve();
                })->values();
            }),
            'comments' => $this->whenLoaded('comments', function () {
                return $this->comments->map(function ($comments) {
                    return (new TicketCommentResource($comments))->resolve();
                })->values();
            }),



            'title' => $this->title,
            'description' => $this->description,
            'impact' => $this->impact,
            'urgency' => $this->urgency,
            'priority' => $this->priority,
            'status' => $this->status,
            'source' => $this->source,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
            'creator' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'closed_at' => $this->closed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
