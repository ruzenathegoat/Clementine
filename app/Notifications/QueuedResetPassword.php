<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;

class QueuedResetPassword extends ResetPassword
{
    // Sends synchronously — no queue worker needed
}
