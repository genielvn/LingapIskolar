<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// sample data
$tickets = [
    [
        "id" => "0000-0001",
        "status" => "Pending User Response",
        "subject" => "Paano ako gagraduate ng may INC?",
        "description" =>
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "category" => "Scholarship",
    ],
    [
        "id" => "0000-0002",
        "status" => "Open",
        "subject" => "Request for scholarship?",
        "description" =>
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "category" => "Scholarship",
    ],
];

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

Route::get("/logout", function () {
    return view("routes.logout");
})->name("logout");

Route::post("/logout", function () {
    Auth()->logout();
    return redirect("/");
});

// TODO: remove $tickets when implementing the ticket controller now
Route::middleware("auth")->group(function () use ($tickets) {
    Route::get("/dashboard", function () use ($tickets) {
        if (auth()->user()->isAdmin()) {
            abort(
                501,
                "TODO: Show dashboard page where admin can create accounts with manager or agent roles",
            );
        }
        if (auth()->user()->isManager()) {
            abort(
                501,
                "TODO: Show dashboard page where they can see all tickets and assign them to agents",
            );
        }
        if (auth()->user()->isAgent()) {
            // TODO: Only give tickets agents are handled
            return view("routes.agent-tickets", ["tickets" => $tickets]);
        }

        // TODO: Check owned tickets
        return view("routes.user-tickets", ["tickets" => $tickets]);
    })->name("dashboard");

    Route::get("/ticket/create", function () {
        return view("routes.create-ticket");
    })->name("ticket-create");

    Route::post("/ticket/create", function (Request $request) {
        return response()->json(
            [
                "status" => 501,
                "comment" =>
                    "TODO: Create the ticket and redirect to the ticket itself",
                "message" => "Not Implemented: Data still received.",
                "data" => $request->all(),
            ],
            501,
        );
    });

    Route::get("/ticket/{id}", function (string $id) use ($tickets) {
        if (auth()->user()->isAdmin()) {
            abort(404); // return to admin dashboard instead
        }
        if (auth()->user()->isManager() || auth()->user()->isAgent()) {
            abort(501, "TODO: Show ticket details page in superior mode.");
        }

        // TODO: Check if user owned the ticket.
        $indexed_records = array_column($tickets, null, "id");

        if (!array_key_exists($id, $indexed_records)) {
            abort(404, "Ticket not found in local sample data.");
        }

        return view("routes.user-ticket-details", [
            "ticket" => $indexed_records[$id],
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
});
