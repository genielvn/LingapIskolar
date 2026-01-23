<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        if ($user->isManager()) {
            return $this->managerDashboard();
        }
        if ($user->isAgent()) {
            return $this->agentDashboard();
        }

        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        $allTickets = Ticket::with([
            "user",
            "status",
            "priority",
            "category",
            "currentAssignment.agent",
        ])
            ->filter()
            ->get();

        $tickets = $this->mapTickets($allTickets);

        $agents = $this->getAllAgents();

        $stats = [
            "total" => $allTickets->count(),
            "open" => $allTickets->where("status.name", "Open")->count(),
            "closed" => $allTickets
                ->whereIn("status.name", ["Closed", "Resolved"])
                ->count(),
            "agents_total" => User::role("agent")->count(),
            "managers_total" => User::role("support-manager")->count(),
            "inactive" => User::role("user")->count(),
        ];

        return view(
            "routes.admin-dashboard",
            compact("tickets", "agents", "stats"),
        );
    }

    private function managerDashboard()
    {
        $allTickets = Ticket::with([
            "user",
            "status",
            "priority",
            "category",
            "currentAssignment.agent",
        ])
            ->leftJoin(
                "ticket_assignments",
                "tickets.id",
                "=",
                "ticket_assignments.ticket_id",
            )
            ->orderByRaw(
                "CASE WHEN ticket_assignments.agent_id IS NULL THEN 0 ELSE 1 END ASC",
            )
            ->orderBy("created_at", "desc")
            ->select("tickets.*")
            ->filter()
            ->get();

        $tickets = $this->mapTickets($allTickets);

        $agents = $this->getAllAgents();

        $stats = [
            "open" => $allTickets->where("status.name", "Open")->count(),
            "unassigned" => Ticket::doesntHave("currentAssignment")->count(),
            "escalated" => $allTickets
                ->where("status.name", "Escalated")
                ->count(),
            "resolved" => $allTickets
                ->where("status.name", "Resolved")
                ->count(),
        ];
        return view(
            "routes.manager-ticket-dashboard",
            compact("tickets", "agents", "stats"),
        );
    }

    private function agentDashboard()
    {
        $agent = auth()->user();

        $allTickets = Ticket::forAgent($agent)
            ->with(["user", "status", "priority", "category"])
            ->orderBy("created_at", "desc")
            ->filter()
            ->get();

        $tickets = $this->mapTickets($allTickets, $agent->name);

        $stats = [
            "open" => $allTickets->where("status.name", "Open")->count(),
            "pending" => $allTickets
                ->where("status.name", "Pending User Response")
                ->count(),
            "escalated" => $allTickets
                ->where("status.name", "Escalated")
                ->count(),
            "resolved" => $allTickets
                ->where("status.name", "Resolved")
                ->count(),
        ];

        return view(
            "routes.agent-ticket-dashboard",
            compact("tickets", "stats"),
        );
    }

    private function userDashboard()
    {
        $user = auth()->user();

        $allTickets = Ticket::forUser($user)
            ->with([
                "status",
                "priority",
                "category",
                "currentAssignment.agent",
            ])
            ->orderBy("created_at", "desc")
            ->filter()
            ->get();

        $tickets = $this->mapTickets($allTickets, null, $user);

        $stats = [
            "open" => $allTickets->where("status.name", "Open")->count(),
            "in_progress" => $allTickets
                ->where("status.name", "In Progress")
                ->count(),
            "closed" => $allTickets
                ->whereIn("status.name", ["Closed", "Resolved"])
                ->count(),
        ];

        return view(
            "routes.user-ticket-dashboard",
            compact("tickets", "stats"),
        );
    }

    public function resolvedTickets()
    {
        $user = auth()->user();

        $allTickets = Ticket::with([
            "user",
            "status",
            "currentAssignment.agent",
        ])
            ->whereHas("status", function ($query) {
                $query->where("name", "Resolved");
            })
            ->orderBy("created_at", "desc")
            ->filter()
            ->get();
        $tickets = $this->mapTickets($allTickets);

        return view("routes.resolved-tickets", compact("tickets"));
    }

    // Helper: map tickets to array
    private function mapTickets($allTickets, $agentName = null, $user = null)
    {
        return $allTickets
            ->map(function ($ticket) use ($agentName, $user) {
                return [
                    "id" => str_pad($ticket->id, 8, "0", STR_PAD_LEFT),
                    "status" => $ticket->status?->name ?? "Unknown",
                    "subject" => $ticket->subject,
                    "description" => $ticket->description,
                    "category" => $ticket->category?->name ?? "Unknown",
                    "priority" => $ticket->priority?->name ?? "Unknown",
                    "requested_by" =>
                        $user?->name ?? ($ticket->user?->name ?? "Unknown"),
                    "requestor_title" => "Student",
                    "requestor_img_link" => "/img/user1.png",
                    "assigned_to" =>
                        $agentName ??
                        ($ticket->currentAssignment?->agent?->name ??
                            "Unassigned"),
                    "assignee_title" => $ticket->currentAssignment?->agent
                        ? "Agent"
                        : "",
                    "assignee_img_link" => "/img/agent1.png",
                ];
            })
            ->toArray();
    }

    // Helper: get all agents
    private function getAllAgents()
    {
        return User::role("agent")
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
    }
}
