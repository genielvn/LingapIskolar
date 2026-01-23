<?php

// app/Http/Controllers/TicketController.php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\TicketMessage;
use App\Models\TicketAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Show create ticket form
     */
    public function create()
    {
        $categories = TicketCategory::all();
        // $priorities = TicketPriority::all();

        return view("routes.create-ticket", [
            "categories" => $categories,
            // 'priorities' => $priorities,
        ]);
    }

    /**
     * Store new ticket
     */
    public function store(Request $request)
    {
        $this->authorize("create", Ticket::class);

        $validated = $request->validate([
            "subject" => "required|string|max:255",
            "description" => "required|string",
            "category_id" => "required|exists:ticket_categories,id",
            //'priority_id' => 'nullable|exists:ticket_priorities,id',
        ]);

        // Get "Open" status
        $openStatus = TicketStatus::where("name", "Open")->first();

        // Get default "Medium" priority
        $defaultPriority = TicketPriority::where("name", "Medium")->first();

        $ticket = Ticket::create([
            "user_id" => auth()->id(),
            "subject" => $validated["subject"],
            "description" => $validated["description"],
            "category_id" => $validated["category_id"],
            //'priority_id' => $validated['priority_id'] ?? null, //default to null
            "priority_id" => $defaultPriority->id,
            "status_id" => $openStatus->id,
        ]);

        return redirect()
            ->route("ticket-details", [
                "id" => str_pad($ticket->id, 8, "0", STR_PAD_LEFT),
            ])
            ->with("success", "Ticket created successfully!");
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return response()->json([
            "status" => 501,
            "comment" =>
                "TODO: Soft delete the ticket. Make sure only managers can do this.",
            "message" => "Not Implemented: Data still received.",
            "data" => $data,
        ]);
    }

    /**
     * Show ticket details
     */
    /*public function show(string $id)
    {
        // Remove leading zeros and convert to integer
        $ticketId = (int) ltrim($id, '0');
        
        $ticket = Ticket::with([
            'user',
            'status',
            'priority',
            'category',
            'currentAssignment.agent',
            'messages' => function($query) {
                $query->visibleTo(auth()->user())
                    ->with('sender')
                    ->oldest();
            },
        ])->findOrFail($ticketId);

        // Authorize view
        $this->authorize('view', $ticket);

        $user = auth()->user();

        // Format ticket data to match Blade expectations
        $ticketData = [
            'id' => str_pad($ticket->id, 8, '0', STR_PAD_LEFT),
            'status' => $ticket->status->name,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'category' => $ticket->category->name,
            'priority' => $ticket->priority->name,
            'requested_by' => $ticket->user->name,
            'requestor_title' => 'Student',
            'requestor_img_link' => '/img/user1.png',
            'assigned_to' => $ticket->currentAssignment?->agent?->name ?? 'Unassigned',
            'assignee_title' => $ticket->currentAssignment?->agent ? 'Agent' : '',
            'assignee_img_link' => '/img/agent1.png',
        ];

        // Route to correct view based on role
        if ($user->isAdmin()) {
            abort(404); // Admins don't view individual tickets
        }

        if ($user->isManager()) {
            $agents = User::role('agent')
                ->get()
                ->map(function ($agent) {
                    return [
                        'id' => (string)$agent->id,
                        'name' => $agent->name,
                        'email' => $agent->email,
                        'title' => 'Agent',
                        'img_link' => '/img/agent1.png',
                    ];
                })
                ->toArray();

            return view('routes.manager-ticket-details', [
                'ticket' => $ticketData,
                'agents' => $agents,
                'raw_ticket' => $ticket, // For forms that need the actual model
            ]);
        }

        if ($user->isAgent()) {
            return view('routes.agent-ticket-details', [
                'ticket' => $ticketData,
                'raw_ticket' => $ticket,
            ]);
        }

        // Regular user view
        return view('routes.user-ticket-details', [
            'ticket' => $ticketData,
            'raw_ticket' => $ticket,
        ]);
    }*/

    public function show(string $id)
    {
        // Convert ticket ID from padded string to integer
        $ticketId = (int) ltrim($id, "0");

        // Load ticket with all necessary relationships
        $ticket = Ticket::with([
            "user",
            "status",
            "priority",
            "category",
            "currentAssignment.agent", // safely get assigned agent
            "messages.sender",
        ])->findOrFail($ticketId);

        // Authorize the current user to view the ticket
        $this->authorize("view", $ticket);

        $user = auth()->user();

        // Prepare ticket data for Blade
        $ticketData = [
            "id" => str_pad($ticket->id, 8, "0", STR_PAD_LEFT),
            "status" => $ticket->status?->name ?? "Unknown",
            "subject" => $ticket->subject,
            "description" => $ticket->description,
            "category" => $ticket->category?->name ?? "Uncategorized",
            "priority" => $ticket->priority?->name ?? "None",
            "requested_by" => $ticket->user?->name ?? "Unknown",
            "requestor_title" => "Student",
            "requestor_img_link" => "/img/user1.png",
            "assigned_to" =>
                $ticket->currentAssignment?->agent?->name ?? "Unassigned",
            "assignee_title" => $ticket->currentAssignment?->agent
                ? "Agent"
                : "",
            "assignee_img_link" => $ticket->currentAssignment?->agent
                ? "/img/agent1.png"
                : "/img/unassigned.png",
        ];

        $chat = $ticket
            ->messages()
            ->with("sender")
            ->orderBy("created_at", "asc")
            ->get();

        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();

        // Route user to the correct view based on role
        if ($user->isAdmin()) {
            abort(404); // Admins don't view individual tickets
        }

        if ($user->isManager()) {
            // Get list of agents for assignment dropdown
            $agents = User::role("agent")
                ->get()
                ->map(
                    fn($agent) => [
                        "id" => (string) $agent->id,
                        "name" => $agent->name,
                        "email" => $agent->email,
                        "title" => "Agent",
                        "img_link" => "/img/agent1.png",
                    ],
                )
                ->toArray();

            return view("routes.manager-ticket-details", [
                "ticket" => $ticketData,
                "agents" => $agents,
                "raw_ticket" => $ticket, // full model for forms
                "chat" => $chat,
                "statuses" => $statuses,
                "priorities" => $priorities,
            ]);
        }

        if ($user->isAgent()) {
            return view("routes.agent-ticket-details", [
                "ticket" => $ticketData,
                "raw_ticket" => $ticket,
                "chat" => $chat,
                "statuses" => $statuses,
                "priorities" => $priorities,
            ]);
        }

        // Regular user view
        return view("routes.user-ticket-details", [
            "ticket" => $ticketData,
            "raw_ticket" => $ticket,
            "chat" => $chat,
        ]);
    }

    /**
     * Add reply/message to ticket
     */
    public function reply(Request $request, string $id)
    {
        // Remove leading zeros and convert to integer
        $ticketId = (int) ltrim($id, "0");

        $ticket = Ticket::findOrFail($ticketId);

        $this->authorize("addMessage", $ticket);

        $validated = $request->validate([
            "message" => "required|string",
        ]);

        TicketMessage::create([
            "ticket_id" => $ticket->id,
            "sender_id" => auth()->id(),
            "message" => $validated["message"],
            "is_internal" => false, // Public message
        ]);

        return back()->with("success", "Reply sent successfully!");
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, string $id)
    {
        // Prevent regular users from accessing this
        if (auth()->user()->isUser()) {
            abort(403);
        }

        // Remove leading zeros and convert to integer
        $ticketId = (int) ltrim($id, "0");

        $ticket = Ticket::findOrFail($ticketId);

        $this->authorize("updateStatus", $ticket);

        $validated = $request->validate([
            "status_id" => "nullable|exists:ticket_statuses,id",
            "priority_id" => "nullable|exists:ticket_priorities,id",
        ]);

        $updateData = [];

        if ($request->filled("status_id")) {
            $updateData["status_id"] = $validated["status_id"];
        }

        if ($request->filled("priority_id")) {
            $updateData["priority_id"] = $validated["priority_id"];
        }

        $ticket->update($updateData);

        return back()->with("success", "Status updated successfully!");
    }

    /**
     * Assign ticket to agent
     */
    public function assign(Request $request, string $id)
    {
        // Only managers can assign
        if (!auth()->user()->isManager()) {
            abort(403);
        }

        // Remove leading zeros and convert to integer
        $ticketId = (int) ltrim($id, "0");

        $ticket = Ticket::findOrFail($ticketId);

        $this->authorize("assign", $ticket);

        $validated = $request->validate([
            "agent_id" => "required|exists:users,id",
            "priority_id" => "required|exists:ticket_priorities,id",
        ]);

        // Verify the user is actually an agent
        $agent = User::findOrFail($validated["agent_id"]);
        if (!$agent->isAgent()) {
            return back()->withErrors([
                "agent_id" => "Selected user is not an agent.",
            ]);
        }

        // Delete old assignment if exists
        $ticket->currentAssignment?->delete();

        // Create new assignment
        TicketAssignment::create([
            "ticket_id" => $ticket->id,
            "agent_id" => $validated["agent_id"],
        ]);

        $ticket->update([
            "priority_id" => $validated["priority_id"],
        ]);

        // Update status to "Assigned"
        $assignedStatus = TicketStatus::where("name", "Assigned")->first();
        if ($assignedStatus) {
            $ticket->update(["status_id" => $assignedStatus->id]);
        }

        return back()->with("success", "Ticket assigned successfully!");
    }
}
