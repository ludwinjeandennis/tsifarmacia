<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PredictionService;

class PredictionController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    public function index()
    {
        if(!auth()->user()->hasRole('admin|pharmacy')){
            abort(403);
        }

        $predictions = $this->predictionService->getPredictions();

        return view('predictions.index', compact('predictions'));
    }
}
