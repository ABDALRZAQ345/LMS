<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\HomePageRequest;

class HomePageController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function __invoke(HomePageRequest $request)
    {

        try {

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
