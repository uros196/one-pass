<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DesignStaticController extends Controller
{
    /**
     * Render design static React components.
     *
     * @param string $page
     * @return Response
     */
    public function __invoke(string $page): Response
    {
        return Inertia::render('DesignStatic/'. ucfirst(Str::camel($page)));
    }
}
