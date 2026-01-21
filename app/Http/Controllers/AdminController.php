<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * List all managers
     */
    public function listManagers()
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->hasRole("admin")) {
            abort(403);
        }

        $managers = User::role("support-manager")
            ->get()
            ->map(function ($manager) {
                return [
                    "id" => (string) $manager->id,
                    "name" => $manager->name,
                    "email" => $manager->email,
                    "title" => "Support Manager",
                    "img_link" => "/img/manager1.png",
                ];
            })
            ->toArray();

        return view("routes.admin-manager-list", [
            "managers" => $managers,
        ]);
    }

    /**
     * Promote user to manager
     */
    public function addManager(Request $request)
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->hasRole("admin")) {
            abort(403);
        }

        $validated = $request->validate([
            "email" => "required|exists:users,email",
        ]);

        $user = User::where("email", $validated["email"])->firstOrFail();

        // Remove existing roles and assign manager role
        $user->syncRoles(["support-manager"]);

        return redirect()
            ->route("agent-list")
            ->with("success", "Successfully promoted the user to agent.");
    }

    /**
     * Revoke manager role (demote to user)
     */
    public function revokeManager(Request $request)
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->hasRole("admin")) {
            abort(403);
        }

        $validated = $request->validate([
            "email" => "required|exists:users,email",
        ]);

        $user = User::where("email", $validated["email"])->firstOrFail();

        // Demote to regular user
        $user->syncRoles(["user"]);

        return response()->json([
            "status" => 200,
            "message" => "Manager role revoked successfully.",
            "data" => [
                "user_id" => $user->id,
                "user_name" => $user->name,
                "new_role" => "user",
            ],
        ]);
    }

    /**
     * List all agents
     */
    public function listAgents()
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->hasRole("admin")) {
            abort(403);
        }

        $agents = User::role("agent")
            ->withCount("ticketAssignments")
            ->get()
            ->map(function ($agent) {
                return [
                    "id" => (string) $agent->id,
                    "name" => $agent->name,
                    "email" => $agent->email,
                    "title" => "Support Agent",
                    "img_link" => "/img/agent1.png",
                    "assigned_tickets" => $agent->ticket_assignments_count ?? 0,
                ];
            })
            ->toArray();

        return view("routes.admin-agent-list", [
            "agents" => $agents,
        ]);
    }

    /**
     * Promote user to agent
     */
    public function addAgent(Request $request)
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->hasRole("admin")) {
            abort(403);
        }

        $validated = $request->validate([
            "email" => "required|exists:users,email",
        ]);

        $user = User::where("email", $validated["email"])->firstOrFail();

        // Remove existing roles and assign agent role
        $user->syncRoles(["agent"]);

        return redirect()
            ->route("agent-list")
            ->with("success", "Successfully promoted the user to agent.");
    }

    /**
     * Revoke agent role (demote to user)
     */
    public function revokeAgent(Request $request)
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->hasRole("admin")) {
            abort(403);
        }

        $validated = $request->validate([
            "email" => "required|exists:users,email",
        ]);

        $user = User::where("email", $validated["email"])->firstOrFail();

        // Check if agent has active ticket assignments
        $activeAssignments = $user->ticketAssignments()->count();

        if ($activeAssignments > 0) {
            return response()->json(
                [
                    "status" => 400,
                    "message" =>
                        "Cannot revoke agent role. Agent has active ticket assignments.",
                    "data" => [
                        "user_id" => $user->id,
                        "active_assignments" => $activeAssignments,
                    ],
                ],
                400,
            );
        }

        // Demote to regular user
        $user->syncRoles(["user"]);

        return response()->json([
            "status" => 200,
            "message" => "Agent role revoked successfully.",
            "data" => [
                "user_id" => $user->id,
                "user_name" => $user->name,
                "new_role" => "user",
            ],
        ]);
    }
}
