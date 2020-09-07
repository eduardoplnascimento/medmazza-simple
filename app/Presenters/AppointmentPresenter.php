<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class AppointmentPresenter extends Presenter
{
    public function status()
    {
        if ($this->status === 'confirmed') {
            return 'Confirmado';
        }
        return 'Cancelado';
    }
}
