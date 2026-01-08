<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("routes.root");
});

Route::get("/login", function () {
    return "login";
})->name("login");

Route::get("/signup", function () {
    return "signup";
})->name("signup");

Route::get("/dashboard", function () {
    return "dashboard";
})->name("dashboard");

Route::get("/ticket", function () {
    return "ticket";
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
