<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('todos', function ($user) {
    return true;
});
