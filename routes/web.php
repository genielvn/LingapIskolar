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
        "priority" => "Urgent",
        "requested_by" => "Sample User",
        "assigned_to" => "Reimu Hakurei",
        "assignee_title" => "Shrine Maiden",
    ],
    [
        "id" => "0000-0002",
        "status" => "Open",
        "subject" => "Request for scholarship?",
        "description" =>
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "category" => "Scholarship",
        "priority" => "High",
        "requested_by" => "Sample User",
        "assigned_to" => "Marisa Kirisame",
        "assignee_title" => "Human Magician",
    ],
    [
        "id" => "0000-0003",
        "status" => "Closed",
        "subject" => "Idiot found on the bathroom",
        "description" =>
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "category" => "Scholarship",
        "priority" => "Medium",
        "requested_by" => "Sample User",
        "assigned_to" => "Cirno",
        "assignee_title" => "Stupid Fairy",
    ],
];

$agents = [
    [
        "id" => "1",
        "name" => "Reimu Hakurei",
        "email" => "reimu@touhou.com",
        "title" => "Shrine Maiden",
    ],
    [
        "id" => "2",
        "name" => "Marisa Kirisame",
        "email" => "marisa@touhou.com",
        "title" => "Ordinary Magician",
    ],
    [
        "id" => "3",
        "name" => "Sakuya Izayoi",
        "email" => "sakuya@touhou.com",
        "title" => "Chief Maid",
    ],
    [
        "id" => "4",
        "name" => "Youmu Konpaku",
        "email" => "youmu@touhou.com",
        "title" => "Half-Ghost Gardener",
    ],
    [
        "id" => "5",
        "name" => "Sanae Kochiya",
        "email" => "sanae@touhou.com",
        "title" => "Deified Human",
    ],
    [
        "id" => "6",
        "name" => "Remilia Scarlet",
        "email" => "remilia@touhou.com",
        "title" => "Vampire Lord",
    ],
    [
        "id" => "7",
        "name" => "Fujiwara no Mokou",
        "email" => "mokou@touhou.com",
        "title" => "Figure of the Person of Hourai",
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
Route::middleware("auth")->group(function () use ($agents, $tickets) {
    Route::get("/dashboard", function () use ($tickets, $agents) {
        if (auth()->user()->isAdmin()) {
            abort(
                501,
                "TODO: Show dashboard page where admin can create accounts with manager or agent roles",
            );
        }
        if (auth()->user()->isManager()) {
            return view("routes.manager-ticket-dashboard", [
                "tickets" => $tickets,
                "agents" => $agents,
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

    Route::get("/ticket/{id}", function (string $id) use ($tickets, $agents) {
        // TODO: Check if user owned the ticket or ticket exists. Do this in controller.
        $indexed_records = array_column($tickets, null, "id");

        if (!array_key_exists($id, $indexed_records)) {
            abort(404, "Ticket not found in local sample data.");
        }

        if (auth()->user()->isAdmin()) {
            abort(404); // return to admin dashboard instead
        }
        if (auth()->user()->isManager()) {
            return view("routes.manager-ticket-details", [
                "agents" => $agents,
                "ticket" => $indexed_records[$id],
                
            ]);
        }
        if (auth()->user()->isAgent()) {
            return view("routes.agent-ticket-details", [
                "ticket" => $indexed_records[$id],
            ]);
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

    Route::put("/ticket/{id}/status", function (Request $request, string $id) {
        if (auth()->user()->isUser()) {
            abort(401);
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
            abort(401);
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
});
