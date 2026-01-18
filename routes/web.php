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
        "requested_by" => "Makoto Yuki",
        "requestor_title" => "Student",
        "requestor_img_link" => "/img/emu.jpg",
        "assigned_to" => "Reimu Hakurei",
        "assignee_title" => "Shrine Maiden",
        "assignee_img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "0000-0002",
        "status" => "Open",
        "subject" => "Request for scholarship?",
        "description" =>
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "category" => "Scholarship",
        "priority" => "High",
        "requested_by" => "Yukari Takeba",
        "requestor_title" => "Student",
        "requestor_img_link" => "/img/emu.jpg",
        "assigned_to" => "Marisa Kirisame",
        "assignee_title" => "Human Magician",
        "assignee_img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "0000-0003",
        "status" => "Closed",
        "subject" => "Idiot found on the bathroom",
        "description" =>
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "category" => "Scholarship",
        "priority" => "Medium",
        "requested_by" => "Junpei Iori",
        "requestor_title" => "Student",
        "requestor_img_link" => "/img/emu.jpg",
        "assigned_to" => "Cirno",
        "assignee_title" => "Stupid Fairy",
        "assignee_img_link" => "/img/emu.jpg",
    ],
];

$sample_members = [
    [
        "id" => "1",
        "name" => "Reimu Hakurei",
        "email" => "reimu@touhou.com",
        "title" => "Shrine Maiden",
        "img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "2",
        "name" => "Marisa Kirisame",
        "email" => "marisa@touhou.com",
        "title" => "Ordinary Magician",
        "img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "3",
        "name" => "Sakuya Izayoi",
        "email" => "sakuya@touhou.com",
        "title" => "Chief Maid",
        "img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "4",
        "name" => "Youmu Konpaku",
        "email" => "youmu@touhou.com",
        "title" => "Half-Ghost Gardener",
        "img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "5",
        "name" => "Sanae Kochiya",
        "email" => "sanae@touhou.com",
        "title" => "Deified Human",
        "img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "6",
        "name" => "Remilia Scarlet",
        "email" => "remilia@touhou.com",
        "title" => "Vampire Lord",
        "img_link" => "/img/emu.jpg",
    ],
    [
        "id" => "7",
        "name" => "Fujiwara no Mokou",
        "email" => "mokou@touhou.com",
        "title" => "Figure of the Person of Hourai",
        "img_link" => "/img/emu.jpg",
    ],
];

$chat = [
    [
        "id" => "1",
        "name" => "Reimu Hakurei",
        "email" => "reimu@touhou.com",
        "title" => "Shrine Maiden",
        "img_link" => "/img/emu.jpg",
        "message" => "Ping?",
        "date" => "24 Aug 2026",
        "me" => false,
    ],
    [
        "id" => "2",
        "name" => "Emu Otori",
        "email" => "emu@pjsk.com",
        "title" => "Student",
        "img_link" => "/img/emu.jpg",
        "message" => "POOOOONNNNNNNGGGG!!!!",
        "date" => "24 Aug 2026",
        "me" => true,
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
Route::post("/logout", function () {
    Auth()->logout();
    return redirect("/");
});

// TODO: remove $tickets when implementing the ticket controller now
Route::middleware("auth")->group(function () use (
    $sample_members,
    $tickets,
    $chat,
) {
    Route::get("/dashboard", function () use ($tickets, $sample_members) {
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
    })->name("dashboard");

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
    });
});
