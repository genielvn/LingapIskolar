<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Ticket extends Model
{
    protected $fillable = [
        "user_id",
        "category_id",
        "status_id",
        "priority_id",
        "subject",
        "description",
    ];

    protected $casts = [
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class);
    }

    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(TicketAssignment::class)->latest("assigned_at");
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, "agent_id")
            ->join("ticket_assignments", function ($join) {
                $join
                    ->on("users.id", "=", "ticket_assignments.agent_id")
                    ->on("tickets.id", "=", "ticket_assignments.ticket_id");
            })
            ->latest("ticket_assignments.assigned_at");
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function publicMessages()
    {
        return $this->hasMany(TicketMessage::class)->where(
            "is_internal",
            false,
        );
    }

    public function internalNotes()
    {
        return $this->hasMany(TicketMessage::class)->where("is_internal", true);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(TicketActivityLog::class);
    }

    // RBAC Query Scopes

    // Users can only see their own tickets
    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where("user_id", $user->id);
    }

    // Agents can only see tickets assigned to them
    public function scopeForAgent(Builder $query, User $agent)
    {
        return $query->whereHas("currentAssignment", function ($q) use (
            $agent,
        ) {
            $q->where("agent_id", $agent->id);
        });
    }

    // Managers and admins see all tickets (no scope needed)
    public function scopeForManagerOrAdmin(Builder $query)
    {
        return $query; // No restriction
    }

    // General scope that applies correct filter based on user role
    public function scopeVisibleTo(Builder $query, User $user)
    {
        if ($user->isAdmin() || $user->isManager()) {
            return $query; // See all tickets
        }

        if ($user->isAgent()) {
            return $query->forAgent($user);
        }

        // Default: regular user sees only their tickets
        return $query->forUser($user);
    }

    // Other useful scopes
    public function scopeByStatus(Builder $query, $statusId)
    {
        return $query->where("status_id", $statusId);
    }

    public function scopeByPriority(Builder $query, $priorityId)
    {
        return $query->where("priority_id", $priorityId);
    }

    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->where("category_id", $categoryId);
    }

    public function scopeOpen(Builder $query)
    {
        return $query->whereHas("status", function ($q) {
            $q->where("name", "Open");
        });
    }

    public function scopeUnassigned(Builder $query)
    {
        return $query->doesntHave("currentAssignment");
    }

    // Helper methods
    public function isAssignedTo(User $agent)
    {
        return $this->currentAssignment?->agent_id === $agent->id;
    }

    public function isOwnedBy(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function isOpen()
    {
        return $this->status->name === "Open";
    }

    public function isClosed()
    {
        return in_array($this->status->name, ["Resolved", "Closed"]);
    }


    public function scopeFilter($query)
    {
        $status = request('status');
        $category = request('category');
        $priority = request('priority');
        $search = request('search');

        return $query
            ->when($status && $status !== 'All', fn($q) => $q->whereHas('status', fn($q2) => $q2->where('name', $status)))
            ->when($category && $category !== 'All', fn($q) => $q->whereHas('category', fn($q2) => $q2->where('name', $category)))
            ->when($priority && $priority !== 'All', fn($q) => $q->whereHas('priority', fn($q2) => $q2->where('name', $priority)))
            ->when($search, fn($q) => $q->where('subject', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%"));
    }

    
}
