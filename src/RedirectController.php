<?php

namespace Lar\Roads;

use Illuminate\Http\Request;

/**
 * Class RedirectController
 *
 * @package Lar\Roads
 */
class RedirectController {

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(Request $request)
    {
        $query = $request->query();

        $query = count($query) ? '' . http_build_query($query) : '';

        $path = implode('/', $request->segments());

        return redirect(\App::getLocale() . '/' . $path . $query);
    }
}
