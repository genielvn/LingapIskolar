<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
        return redirect("dashboard");
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

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", function () {
        return "dashboard";
    })->name("dashboard");

    Route::get("/ticket", function () {
        return view("routes.user-tickets");
    })->name("ticket");

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

    Route::get("/ticket/assign", function () {
        return "tickets assign page";
    })->name("ticket-assign");

    Route::get("/ticket/{id}", function (string $id) {
        return "ticket#" . $id;
    })->name("ticket-details");

    Route::get("/ticket/{id}/review", function (string $id) {
        return "ticket#" . $id . "-review";
    })->name("ticket-detail-review");

    Route::get("/ticket/{id}/inquire", function (string $id) {
        return "ticket#" . $id . "-message";
    })->name("ticket-detail-inquire");
});
