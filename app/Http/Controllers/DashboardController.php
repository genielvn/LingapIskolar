<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show role-based dashboard
     */
    public function index()
    {
        $user = auth()->user();
       

        // Route to correct dashboard based on role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isManager()) {
            return $this->managerDashboard();
        }

        if ($user->isAgent()) {
            return $this->agentDashboard();
        }

        // Default: regular user dashboard
        return $this->userDashboard();
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        // Get all tickets for admin view
        $allTickets = Ticket::with(['user', 'status', 'priority', 'category', 'currentAssignment.agent'])->get();
        
        $tickets = $allTickets->map(function ($ticket) {
            return [
                'id' => str_pad($ticket->id, 8, '0', STR_PAD_LEFT),
                'status' => $ticket->status?->name ?? 'Unknown',
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'category' => $ticket->category?->name ?? 'Unknown',
                'priority' => $ticket->priority?->name ?? 'Unknown',
                'requested_by' => $ticket->user?->name ?? 'Unknown',
                'requestor_title' => 'Student',
                'requestor_img_link' => '/img/user1.png',
                'assigned_to' => $ticket->currentAssignment?->agent?->name ?? 'Unassigned',
                'assignee_title' => $ticket->currentAssignment?->agent ? 'Agent' : '',
                'assignee_img_link' => '/img/agent1.png',
            ];
        })->toArray();

        // Get all agents
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

        // Calculate stats for counters
        $stats = [
            'total' => $allTickets->count(),
            'open' => $allTickets->where('status.name', 'Open')->count(),
            'closed' => $allTickets->whereIn('status.name', ['Closed', 'Resolved'])->count(),
            'agents_total' => User::role('agent')->count(),
            'managers_total' => User::role('support-manager')->count(),
            'inactive' => User::role('user')->count(), // Regular users as "inactive" count
        ];

        return view('routes.admin-dashboard', [
            'tickets' => $tickets,
            'agents' => $agents,
            'stats' => $stats,
        ]);
    }

    /**
     * Manager Dashboard
     */
    private function managerDashboard()
    {
        // Managers see all tickets
        $allTickets = Ticket::with(['user', 'status', 'priority', 'category', 'currentAssignment.agent'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        $tickets = $allTickets->map(function ($ticket) {
            return [
                'id' => str_pad($ticket->id, 8, '0', STR_PAD_LEFT),
                'status' => $ticket->status?->name ?? 'Unknown',
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'category' => $ticket->category?->name ?? 'Unknown',
                'priority' => $ticket->priority?->name ?? 'Unknown',
                'requested_by' => $ticket->user?->name ?? 'Unknown',
                'requestor_title' => 'Student',
                'requestor_img_link' => '/img/user1.png',
                'assigned_to' => $ticket->currentAssignment?->agent?->name ?? 'Unassigned',
                'assignee_title' => $ticket->currentAssignment?->agent ? 'Agent' : '',
                'assignee_img_link' => '/img/agent1.png',
            ];
        })->toArray();

        // Get all agents
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

        // Calculate stats
        $stats = [
            'open' => $allTickets->where('status.name', 'Open')->count(),
            'unassigned' => Ticket::doesntHave('currentAssignment')->count(),
            'escalated' => $allTickets->where('status.name', 'Escalated')->count(),
            'resolved' => $allTickets->where('status.name', 'Resolved')->count(),
        ];

        return view('routes.manager-ticket-dashboard', [
            'tickets' => $tickets,
            'agents' => $agents,
            'stats' => $stats,
        ]);
    }

    /**
     * Agent Dashboard
     */
    private function agentDashboard()
    {
        $agent = auth()->user();

        // Agents only see tickets assigned to them
        $allTickets = Ticket::forAgent($agent)
            ->with(['user', 'status', 'priority', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $tickets = $allTickets->map(function ($ticket) {
            return [
                'id' => str_pad($ticket->id, 8, '0', STR_PAD_LEFT),
                'status' => $ticket->status?->name ?? 'Unknown',
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'category' => $ticket->category?->name ?? 'Unknown',
                'priority' => $ticket->priority?->name ?? 'Unknown',
                'requested_by' => $ticket->user?->name ?? 'Unknown',
                'requestor_title' => 'Student',
                'requestor_img_link' => '/img/user1.png',
                'assigned_to' => auth()->user()->name,
                'assignee_title' => 'Agent',
                'assignee_img_link' => '/img/agent1.png',
            ];
        })->toArray();

        // Calculate stats
        $stats = [
            'open' => $allTickets->where('status.name', 'Open')->count(),
            'pending' => $allTickets->where('status.name', 'Pending User Response')->count(),
            'escalated' => $allTickets->where('status.name', 'Escalated')->count(),
            'resolved' => $allTickets->where('status.name', 'Resolved')->count(),
        ];

        return view('routes.agent-ticket-dashboard', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }

    /**
     * User Dashboard
     */
    private function userDashboard()
    {
        $user = auth()->user();

        // Users only see their own tickets
        $allTickets = Ticket::forUser($user)
            ->with(['status', 'priority', 'category', 'currentAssignment.agent'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $tickets = $allTickets->map(function ($ticket) use ($user) {
            return [
                'id' => str_pad($ticket->id, 8, '0', STR_PAD_LEFT),
                'status' => $ticket->status?->name ?? 'Unknown',
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'category' => $ticket->category?->name ?? 'Unknown',
                'priority' => $ticket->priority?->name ?? 'Unknown',
                'requested_by' => $user->name,
                'requestor_title' => 'Student',
                'requestor_img_link' => '/img/user1.png',
                'assigned_to' => $ticket->currentAssignment?->agent?->name ?? 'Pending Assignment',
                'assignee_title' => $ticket->currentAssignment?->agent ? 'Agent' : '',
                'assignee_img_link' => '/img/agent1.png',
            ];
        })->toArray();

        // Calculate stats
        $stats = [
            'open' => $allTickets->where('status.name', 'Open')->count(),
            'in_progress' => $allTickets->where('status.name', 'In Progress')->count(),
            'closed' => $allTickets->whereIn('status.name', ['Closed', 'Resolved'])->count(),
        ];

        return view('routes.user-ticket-dashboard', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }
}