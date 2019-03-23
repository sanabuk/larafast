<?php
namespace sanabuk\larafast;

use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

class LarafastController extends Controller
{
    public function index()
    {
        try {
            $request  = Input::all();
            $larafast = new Larafast();
            $datas    = $larafast->getDatas($request);
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'code' => $e->getCode()], $e->getCode());
        }
        return new JsonResponse($datas->paginate(Input::get('perpage'))->appends($request), 200);
    }
}
