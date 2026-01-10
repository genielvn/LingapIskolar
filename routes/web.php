<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post("/logout", function () {
    Auth()->logout();
    return redirect("/");
})->name("logout");

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", function () {
        return "dashboard";
    })->name("dashboard");

    Route::get("/ticket", function () {
        return view("routes.user-tickets");
    })->name("ticket");

    Route::get("/ticket/create", function () {
        return "tickets creation page";
    })->name("ticket-create");

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
