<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                "subject" => "Test Low Ticket",
                "description" => "This is a test ticket.",
                "user_id" => User::where("email", "user@example.com")->first()
                    ->id,
                "priority_id" => TicketPriority::where("name", "Low")->first()
                    ->id,
                "status_id" => TicketStatus::where("name", "Open")->first()->id,
                "category_id" => TicketCategory::where(
                    "name",
                    "General Inquiry",
                )->first()->id,
            ],
            [
                "subject" => "Test Urgent Ticket",
                "description" => "This is a test ticket.",
                "user_id" => User::where("email", "user@example.com")->first()
                    ->id,
                "priority_id" => TicketPriority::where(
                    "name",
                    "Urgent",
                )->first()->id,
                "status_id" => TicketStatus::where("name", "Open")->first()->id,
                "category_id" => TicketCategory::where(
                    "name",
                    "General Inquiry",
                )->first()->id,
            ],
        ];

        DB::table("tickets")->insert($tickets);
    }
}
