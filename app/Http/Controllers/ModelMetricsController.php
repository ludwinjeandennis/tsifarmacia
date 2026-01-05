<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Services\PredictionService;
use Illuminate\Http\Request;

class ModelMetricsController extends Controller
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

        // Obtener todas las predicciones
        $predictions = $this->predictionService->getPredictions();

        // Calcular métricas agregadas del modelo
        $metrics = $this->calculateAggregatedMetrics($predictions);
        
        // Distribución de precisión
        $accuracyDistribution = $this->getAccuracyDistribution($predictions);
        
        // Top medicamentos por precisión
        $topAccurate = $this->getTopAccuratePredictions($predictions, 10);
        $leastAccurate = $this->getLeastAccuratePredictions($predictions, 10);
        
        // Detección de estacionalidad y anomalías
        $seasonalityStats = $this->getSeasonalityStats($predictions);
        $anomalyStats = $this->getAnomalyStats($predictions);
        
        // Tendencias del modelo
        $trendDistribution = $this->getTrendDistribution($predictions);

        return view('model_metrics.index', compact(
            'metrics',
            'accuracyDistribution',
            'topAccurate',
            'leastAccurate',
            'seasonalityStats',
            'anomalyStats',
            'trendDistribution',
            'predictions'
        ));
    }

    private function calculateAggregatedMetrics($predictions)
    {
        $validPredictions = array_filter($predictions, function($p) {
            return $p['mape'] !== null && $p['rmse'] !== null;
        });

        if (empty($validPredictions)) {
            return [
                'avg_mape' => 0,
                'avg_rmse' => 0,
                'total_predictions' => 0,
                'high_accuracy_count' => 0,
                'low_accuracy_count' => 0,
                'avg_confidence_range' => 0
            ];
        }

        $totalMape = 0;
        $totalRmse = 0;
        $highAccuracy = 0;
        $lowAccuracy = 0;
        $totalConfidenceRange = 0;

        foreach ($validPredictions as $p) {
            $totalMape += $p['mape'];
            $totalRmse += $p['rmse'];
            
            if ($p['accuracy'] == 'Muy Alta' || $p['accuracy'] == 'Alta') {
                $highAccuracy++;
            } elseif ($p['accuracy'] == 'Baja') {
                $lowAccuracy++;
            }

            if (is_array($p['confidence_interval']) && isset($p['confidence_interval']['upper'])) {
                $range = $p['confidence_interval']['upper'] - $p['confidence_interval']['lower'];
                $totalConfidenceRange += $range;
            }
        }

        $count = count($validPredictions);

        return [
            'avg_mape' => round($totalMape / $count, 2),
            'avg_rmse' => round($totalRmse / $count, 2),
            'total_predictions' => count($predictions),
            'valid_predictions' => $count,
            'high_accuracy_count' => $highAccuracy,
            'high_accuracy_percent' => round(($highAccuracy / $count) * 100, 1),
            'low_accuracy_count' => $lowAccuracy,
            'low_accuracy_percent' => round(($lowAccuracy / $count) * 100, 1),
            'avg_confidence_range' => round($totalConfidenceRange / $count, 1)
        ];
    }

    private function getAccuracyDistribution($predictions)
    {
        $distribution = [
            'Muy Alta' => 0,
            'Alta' => 0,
            'Media' => 0,
            'Baja' => 0,
            'N/A' => 0
        ];

        foreach ($predictions as $p) {
            $accuracy = $p['accuracy'];
            if (isset($distribution[$accuracy])) {
                $distribution[$accuracy]++;
            }
        }

        return $distribution;
    }

    private function getTopAccuratePredictions($predictions, $limit = 10)
    {
        $validPredictions = array_filter($predictions, function($p) {
            return $p['mape'] !== null;
        });

        usort($validPredictions, function($a, $b) {
            return $a['mape'] <=> $b['mape'];
        });

        return array_slice($validPredictions, 0, $limit);
    }

    private function getLeastAccuratePredictions($predictions, $limit = 10)
    {
        $validPredictions = array_filter($predictions, function($p) {
            return $p['mape'] !== null;
        });

        usort($validPredictions, function($a, $b) {
            return $b['mape'] <=> $a['mape'];
        });

        return array_slice($validPredictions, 0, $limit);
    }

    private function getSeasonalityStats($predictions)
    {
        $seasonal = 0;
        $nonSeasonal = 0;

        foreach ($predictions as $p) {
            if ($p['has_seasonality']) {
                $seasonal++;
            } else {
                $nonSeasonal++;
            }
        }

        return [
            'seasonal_count' => $seasonal,
            'non_seasonal_count' => $nonSeasonal,
            'seasonal_percent' => count($predictions) > 0 ? round(($seasonal / count($predictions)) * 100, 1) : 0
        ];
    }

    private function getAnomalyStats($predictions)
    {
        $totalAnomalies = 0;
        $medicinesWithAnomalies = 0;

        foreach ($predictions as $p) {
            $anomalyCount = count($p['anomalies_detected']);
            if ($anomalyCount > 0) {
                $totalAnomalies += $anomalyCount;
                $medicinesWithAnomalies++;
            }
        }

        return [
            'total_anomalies' => $totalAnomalies,
            'medicines_with_anomalies' => $medicinesWithAnomalies,
            'anomaly_percent' => count($predictions) > 0 ? round(($medicinesWithAnomalies / count($predictions)) * 100, 1) : 0
        ];
    }

    private function getTrendDistribution($predictions)
    {
        $distribution = [
            'En Aumento' => 0,
            'Estable' => 0,
            'En Descenso' => 0,
            'Desconocida' => 0
        ];

        foreach ($predictions as $p) {
            $trend = $p['trend'];
            if (isset($distribution[$trend])) {
                $distribution[$trend]++;
            }
        }

        return $distribution;
    }
}
