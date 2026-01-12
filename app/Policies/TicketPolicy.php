<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view any tickets.
     */
    public function viewAny(User $user): bool
    {
        //All authenticated users can view tickets (filtered by model scope)
        return true; //I'll check on this later
    }

    /**
     * Determine whether the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        //Admin and managers can view all tickets
        if($user->isAdmin() || $user->isManager())  //for checking later, although we can continue with admin having access to tickets but can't do anything to them
        {
            return true;   
        }

        //Agents can view the tickets assigned to them
        if($user->isAgent())
        {
            return $ticket->isAssignedTo($user);
        }
        
        //Regular users can only view their tickets
        return $ticket->isOwnedBy($user);
    }

    /**
     * Determine whether the user can create tickets.
     */
    public function create(User $user): bool
    {
        //All users can create tickets
        return $user->hasPermissionTo('create-tickets');
    }

    /**
     * Determine whether the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        //Admin and manager can update any ticket
        if($user->isAdmin() || $user->isManager())
        {
            return $user->hasPermissionTo('update-any-ticket');
        }
        
        //Agents can update tickets assigned to them
        if($user->isAgent())
        {
            return $ticket->isAssignedTo($user) &&
                $user->hasPermissionTo('update-assigned-tickets');
        }

        //students cannot update tickets (read-only after creation)
        return false;
    }

    /**
     * Determine whether the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {   
        //only admins can delete tickets
        return $user->isAdmin() && $user->hasPermissionTo('delete-tickets');
    }

    /**
     * Determine whether the user can assign the ticket.
     */
    public function assign(User $user, Ticket $ticket): bool
    {       
        //Only managers can assign tickets
        return ($user->isManager() && $user->hasPermissionTo('assign-tickets'));
    }

    /**
     * Determine whether the user can reassign the ticket.
     */
    public function reassign(User $user, Ticket $ticket): bool
    {
        //only managers can reassign tickets
        return ($user->isManager() && $user->hasPermissionTo('assign-tickets'));
    }

    /**
     * Determine whether the user can REQUEST escalation on their assigned ticket.
     */

    public function requestEscalation(User $user, Ticket $ticket): bool
    {
        //Agents can request Escalation 
        if($user->isAgent())
        {
            return $ticket->isAssignedTo($user);
        }       

        //Managers can also escalate directly
        return ($user->isManager() && $user->hasPermissionTo('escalate-tickets'));

    }

    /**
     * Determine whether the user can handle the escalation .
     */

    public function handleEscalation(User $user, Ticket $ticket): bool
    {
        
        //Managers can also escalate directly
        return ($user->isManager() && $user->hasPermissionTo('escalate-tickets'));

    }


    /**
     * Determine whether the user can update the ticket status .
     */

    public function updateStatus(User $user, Ticket $ticket): bool
    {
        //managers can update any ticket status
        if($user->isManager())
        {
            return $user->hasPermissionTo('update-ticket-status');
        }

        //Agents can update status of assigned tickets only
        if($user->isAgent()){
            return $ticket->isAssignedTo($user) && 
                    $user->hasPermissionTo('update-ticket-status');
        }

        //students cannot update status
        return false;

    }

    /**
     * Determine if the user can update ticket priority.
     */
    public function updatePriority(User $user, Ticket $ticket): bool
    {
        //managers can update any ticket priority
        if ($user->isManager()) {
            return $user->hasPermissionTo('update-ticket-priority');
        }

        // Agents can update priority of assigned tickets only
        if ($user->isAgent()) {
            return $ticket->isAssignedTo($user) && 
                   $user->hasPermissionTo('update-ticket-priority');
        }

        // students cannot update priority
        return false;
    }


    /**
     * Determine if the user can add public messages to the ticket.
     */
    public function addMessage(User $user, Ticket $ticket): bool
    {
        // managers can message any ticket
        if ($user->isManager()) {
            return $user->hasPermissionTo('add-public-messages');
        }

        // Agents can message assigned tickets
        if ($user->isAgent()) {
            return $ticket->isAssignedTo($user) && 
                   $user->hasPermissionTo('add-public-messages');
        }

        // Users can only message their own tickets
        return $ticket->isOwnedBy($user) && 
               $user->hasPermissionTo('add-public-messages');
    }

    /**
     * Determine if the user can add internal notes to the ticket.
     */
    public function addInternalNote(User $user, Ticket $ticket): bool
    {
        // Only staff (agents, managers) can add internal notes
        if ($user->isManager()) {
            return $user->hasPermissionTo('add-internal-notes');
        }

        // Agents can add notes to assigned tickets only
        if ($user->isAgent()) {
            return $ticket->isAssignedTo($user) && 
                   $user->hasPermissionTo('add-internal-notes');
        }

        //Students cannot add internal notes
        return false;
    }

    /**
     * Determine if the user can view internal notes.
     */
    public function viewInternalNotes(User $user, Ticket $ticket): bool
    {
        // Managers can view all internal notes
        if ($user->isManager()) {
            return $user->hasPermissionTo('view-internal-notes');
        }

        // Agents can view internal notes on assigned tickets
        if ($user->isAgent()) {
            return $ticket->isAssignedTo($user) && 
                   $user->hasPermissionTo('view-internal-notes');
        }

        // Students cannot view internal notes
        return false;
    }

    /**
     * Determine if the user can view activity logs.
     */
    public function viewActivityLogs(User $user, Ticket $ticket): bool
    {
        //Admin and mangaers can view all activity logs
        if ($user->isAdmin() || $user->isManager()) {
            return $user->hasPermissionTo('view-activity-logs');
        }

        // Agents can view internal notes on assigned tickets
        if ($user->isAgent()) {
            return $ticket->isAssignedTo($user) && 
                   $user->hasPermissionTo('view-internal-notes');
        }

        // Students can view logs on their own tickets
        return $ticket->isOwnedBy($user);
    }

}
