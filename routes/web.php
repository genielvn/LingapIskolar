<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// sample data

Route::get("/", function () {
    if (Auth()->guest()) {
        return redirect("login");
    }
    return redirect("dashboard");
})->name("root");

Route::get("/login", function () {
    if (Auth()->check()) {
        return redirect("dashboard");
    }
    return view("routes.login");
})->name("login");

Route::post("/login", [UserController::class, "log"]);

Route::get("/signup", function () {
    if (Auth()->check()) {
        return redirect("ticket");
    }
    return view("routes.signup");
})->name("signup");

Route::post("/signup", [UserController::class, "sign"]);
Route::post("/logout", function () {
    Auth()->logout();
    return redirect("/");
});

// TODO: remove $tickets when implementing the ticket controller now
Route::middleware("auth")->group(function () {
    /*Route::get("/dashboard", function () use ($tickets, $sample_members) {
        if (auth()->user()->isAdmin()) {
            return view("routes.admin-dashboard", [
                "tickets" => $tickets,
                "agents" => $sample_members,
            ]);
        }
        if (auth()->user()->isManager()) {
            return view("routes.manager-ticket-dashboard", [
                "tickets" => $tickets,
                "agents" => $sample_members,
            ]);
        }
        if (auth()->user()->isAgent()) {
            // TODO: Only give tickets agents are handled
            return view("routes.agent-ticket-dashboard", [
                "tickets" => $tickets,
            ]);
        }

        // TODO: Check owned tickets
        return view("routes.user-ticket-dashboard", ["tickets" => $tickets]);
    })->name("dashboard"); */

    /*  Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");


    Route::get("/ticket/create", function () {
        return view("routes.create-ticket");
    })->name("ticket-create");

    Route::post("/ticket/create", function (Request $request) {
        $data = $request->all();
        $data["has_file"] = $request->hasFile("upload");
        $data["file_name"] = $request->file("upload")?->getClientOriginalName();

        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Create the ticket and redirect to the ticket itself",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::get("/ticket/{id}", function (string $id) use (
        $tickets,
        $sample_members,
        $chat,
    ) {
        // TODO: Check if user owned the ticket, user is higher than a standard role, or ticket exists. Do this in controller.
        $indexed_records = array_column($tickets, null, "id");

        if (!array_key_exists($id, $indexed_records)) {
            abort(404, "Ticket not found in local sample data.");
        }

        if (auth()->user()->isAdmin()) {
            abort(404); // return to admin dashboard instead
        }
        if (auth()->user()->isManager()) {
            return view("routes.manager-ticket-details", [
                "agents" => $sample_members,
                "ticket" => $indexed_records[$id],
                "chat" => $chat,
            ]);
        }
        if (auth()->user()->isAgent()) {
            return view("routes.agent-ticket-details", [
                "ticket" => $indexed_records[$id],
                "chat" => $chat,
            ]);
        }

        return view("routes.user-ticket-details", [
            "ticket" => $indexed_records[$id],
            "chat" => $chat,
        ]);
    })->name("ticket-details");

    Route::post("/ticket/{id}/reply", function (Request $request, string $id) {
        $data = $request->all();

        $data["ticket_id"] = $id;
        $data["user_id"] = auth()->user()->id;
        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Create the reply, refresh, and show the reply thread",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::put("/ticket/{id}/status", function (Request $request, string $id) {
        if (auth()->user()->isUser()) {
            abort(404);
        }
        $data = $request->all();
        $data["ticket_id"] = $id;
        $data["user_id"] = auth()->user()->id;

        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Put everything here on controller, including the authorization.",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::put("/ticket/{id}/assign", function (Request $request, string $id) {
        if (!auth()->user()->isManager()) {
            abort(404);
        }

        $data = $request->all();
        $data["ticket_id"] = $id;
        $data["user_id"] = auth()->user()->id;

        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Put everything here on controller, including the authorization.",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::get("/manager", function (Request $request) use ($sample_members) {
        if (!auth()->user()->isAdmin()) {
            abort(404);
        }
        return view("routes.admin-manager-list", [
            "managers" => $sample_members,
        ]);
    })->name("manager-list");

    Route::put("/manager/add", function (Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(404);
        }
        $data = $request->all();

        return response()->json(
            [
                "status" => 501,
                "comment" => "TODO: Convert the user to manager role.",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::put("/manager/revoke", function (Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(404);
        }
        $data = $request->all();

        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Revoke the user's special permissions and convert to simple role.",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::get("/agent", function (Request $request) use ($sample_members) {
        if (!auth()->user()->isAdmin()) {
            abort(404);
        }
        return view("routes.admin-agent-list", ["agents" => $sample_members]);
    })->name("agent-list");

    Route::put("/agent/add", function (Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(404);
        }
        $data = $request->all();

        return response()->json(
            [
                "status" => 501,
                "comment" => "TODO: Convert the user to agent role.",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });

    Route::put("/agent/revoke", function (Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(404);
        }
        $data = $request->all();

        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Revoke the user's special permissions and convert to simple role.",
                "message" => "Not Implemented: Data still received.",
                "data" => $data,
            ],
            501,
        );
    });*/

    // Dashboard
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );
    Route::get("/dashboard/resolved", [
        DashboardController::class,
        "resolvedTickets",
    ])->name("resolved-tickets");

    // Ticket routes
    Route::get("/ticket/create", [TicketController::class, "create"])->name(
        "ticket-create",
    );
    Route::post("/ticket/create", [TicketController::class, "store"]);
    Route::delete("/ticket/delete", [TicketController::class, "delete"]);
    Route::get("/ticket/{id}", [TicketController::class, "show"])->name(
        "ticket-details",
    );
    Route::post("/ticket/{id}/reply", [TicketController::class, "reply"]);
    Route::put("/ticket/{id}/status", [
        TicketController::class,
        "updateStatus",
    ]);
    Route::put("/ticket/{id}/assign", [TicketController::class, "assign"]);

    // Admin routes - Manager management
    Route::get("/manager", [AdminController::class, "listManagers"])->name(
        "manager-list",
    );
    Route::put("/manager/add", [AdminController::class, "addManager"]);
    Route::put("/manager/revoke", [AdminController::class, "revokeManager"]);

    // Admin routes - Agent management
    Route::get("/agent", [AdminController::class, "listAgents"])->name(
        "agent-list",
    );
    Route::put("/agent/add", [AdminController::class, "addAgent"]);
    Route::put("/agent/revoke", [AdminController::class, "revokeAgent"]);
});
