<?php

namespace App\Services;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PredictionService
{
    /**
     * Modelo Predictivo Adaptativo con ML
     * Implementa:
     * - Exponential Smoothing (Holt-Winters) para tendencia y estacionalidad
     * - Detección de anomalías
     * - Validación del modelo (RMSE, MAPE)
     * - Aprendizaje adaptativo
     */
    public function getPredictions()
    {
        $medicines = Medicine::all();
        $predictions = [];

        foreach ($medicines as $medicine) {
            $history = $this->getSalesHistory($medicine->id, 12); // 12 meses para mejor detección de estacionalidad
            
            if (count($history) < 3) {
                $predictions[] = [
                    'medicine' => $medicine,
                    'history' => $history,
                    'predicted_qty' => 0,
                    'current_stock' => $medicine->stock,
                    'qty_to_buy' => 0,
                    'action_type' => 'Datos Insuficientes',
                    'accuracy' => 'N/A',
                    'trend' => 'Desconocida',
                    'coefficient' => 0,
                    'confidence_interval' => [0, 0],
                    'rmse' => null,
                    'mape' => null,
                    'has_seasonality' => false,
                    'anomalies_detected' => []
                ];
                continue;
            }

            // 1. DETECCIÓN DE ANOMALÍAS
            $anomalies = $this->detectAnomalies($history);
            
            // 2. DETECCIÓN DE ESTACIONALIDAD
            $seasonality = $this->detectSeasonality($history);
            
            // 3. PREDICCIÓN CON EXPONENTIAL SMOOTHING
            $prediction = $this->exponentialSmoothing($history, $seasonality);
            
            // 4. VALIDACIÓN DEL MODELO
            $validation = $this->validateModel($history, $seasonality);
            
            // 5. CÁLCULO DE INTERVALO DE CONFIANZA
            $confidenceInterval = $this->calculateConfidenceInterval($history, $prediction['forecast']);
            
            // Determinar tendencia
            $trend = $prediction['trend_direction'];
            
            // Lógica de Abastecimiento vs Stock Actual
            $currentStock = $medicine->stock;
            $predictedQty = max(0, round($prediction['forecast']));
            $qtyToBuy = 0;
            $action = 'Suficiente';
            
            if ($predictedQty > $currentStock) {
                $qtyToBuy = $predictedQty - $currentStock;
                $action = 'Comprar';
            } elseif ($medicine->isLowStock()) {
                $qtyToBuy = $medicine->min_stock - $currentStock + $predictedQty;
                $action = 'Crítico';
            }

            // Determinar precisión basada en métricas
            $accuracy = 'Media';
            if ($validation['mape'] !== null) {
                if ($validation['mape'] < 10) $accuracy = 'Muy Alta';
                elseif ($validation['mape'] < 20) $accuracy = 'Alta';
                elseif ($validation['mape'] < 30) $accuracy = 'Media';
                else $accuracy = 'Baja';
            }

            $predictions[] = [
                'medicine' => $medicine,
                'history' => array_slice($history, -6), // Mostrar últimos 6 meses
                'predicted_qty' => $predictedQty,
                'current_stock' => $currentStock,
                'qty_to_buy' => $qtyToBuy,
                'action_type' => $action,
                'accuracy' => $accuracy,
                'trend' => $trend,
                'coefficient' => $prediction['trend_coefficient'],
                'confidence_interval' => $confidenceInterval,
                'rmse' => $validation['rmse'],
                'mape' => $validation['mape'],
                'has_seasonality' => $seasonality['detected'],
                'seasonal_factor' => $seasonality['factor'],
                'anomalies_detected' => $anomalies
            ];
        }

        // Ordenar por mayor demanda predicha
        usort($predictions, function ($a, $b) {
            return $b['predicted_qty'] <=> $a['predicted_qty'];
        });

        return $predictions;
    }

    /**
     * Exponential Smoothing con Holt-Winters (Tendencia + Estacionalidad)
     */
    private function exponentialSmoothing($data, $seasonality)
    {
        $n = count($data);
        if ($n < 3) return ['forecast' => 0, 'trend_direction' => 'Desconocida', 'trend_coefficient' => 0];

        // Parámetros de suavizado
        $alpha = 0.3; // Nivel
        $beta = 0.1;  // Tendencia
        $gamma = 0.2; // Estacionalidad

        // Inicialización
        $level = $data[0];
        $trend = ($data[1] - $data[0]);
        
        // Aplicar suavizado
        for ($i = 1; $i < $n; $i++) {
            $prevLevel = $level;
            $level = $alpha * $data[$i] + (1 - $alpha) * ($level + $trend);
            $trend = $beta * ($level - $prevLevel) + (1 - $beta) * $trend;
        }

        // Forecast para el siguiente período
        $forecast = $level + $trend;
        
        // Ajustar por estacionalidad si se detectó
        if ($seasonality['detected']) {
            $forecast *= $seasonality['factor'];
        }

        // Determinar dirección de tendencia
        $trendDirection = 'Estable';
        if ($trend > 0.5) $trendDirection = 'En Aumento';
        elseif ($trend < -0.5) $trendDirection = 'En Descenso';

        return [
            'forecast' => max(0, $forecast),
            'trend_direction' => $trendDirection,
            'trend_coefficient' => round($trend, 2)
        ];
    }

    /**
     * Detección de Estacionalidad usando autocorrelación
     */
    private function detectSeasonality($data)
    {
        $n = count($data);
        if ($n < 6) return ['detected' => false, 'factor' => 1.0];

        // Calcular promedio
        $mean = array_sum($data) / $n;
        
        // Calcular varianza
        $variance = 0;
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        $variance /= $n;

        if ($variance == 0) return ['detected' => false, 'factor' => 1.0];

        // Autocorrelación simple para detectar patrones
        $lag = min(3, floor($n / 2)); // Buscar patrones cada 3 meses
        $autocorr = 0;
        
        for ($i = 0; $i < $n - $lag; $i++) {
            $autocorr += ($data[$i] - $mean) * ($data[$i + $lag] - $mean);
        }
        $autocorr /= (($n - $lag) * $variance);

        // Si autocorrelación > 0.3, hay estacionalidad
        $hasSeasonality = abs($autocorr) > 0.3;
        
        // Factor estacional basado en últimos vs primeros datos
        $recentAvg = array_sum(array_slice($data, -3)) / 3;
        $earlyAvg = array_sum(array_slice($data, 0, 3)) / 3;
        $seasonalFactor = $earlyAvg > 0 ? $recentAvg / $earlyAvg : 1.0;

        return [
            'detected' => $hasSeasonality,
            'factor' => $seasonalFactor,
            'autocorrelation' => round($autocorr, 3)
        ];
    }

    /**
     * Detección de Anomalías usando Z-Score
     */
    private function detectAnomalies($data)
    {
        $n = count($data);
        if ($n < 3) return [];

        $mean = array_sum($data) / $n;
        
        // Desviación estándar
        $variance = 0;
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        $stdDev = sqrt($variance / $n);

        if ($stdDev == 0) return [];

        $anomalies = [];
        foreach ($data as $index => $value) {
            $zScore = ($value - $mean) / $stdDev;
            
            // Si |Z-Score| > 2, es una anomalía
            if (abs($zScore) > 2) {
                $anomalies[] = [
                    'index' => $index,
                    'value' => $value,
                    'z_score' => round($zScore, 2),
                    'type' => $zScore > 0 ? 'spike' : 'drop'
                ];
            }
        }

        return $anomalies;
    }

    /**
     * Validación del Modelo: RMSE y MAPE
     */
    private function validateModel($data, $seasonality)
    {
        $n = count($data);
        if ($n < 4) return ['rmse' => null, 'mape' => null];

        // Usar los últimos datos para validación
        $trainSize = floor($n * 0.8);
        $trainData = array_slice($data, 0, $trainSize);
        $testData = array_slice($data, $trainSize);

        $predictions = [];
        foreach ($testData as $i => $actual) {
            $pred = $this->exponentialSmoothing($trainData, $seasonality);
            $predictions[] = $pred['forecast'];
            $trainData[] = $actual; // Aprendizaje incremental
        }

        // Calcular RMSE
        $squaredErrors = 0;
        $absolutePercentErrors = 0;
        $validCount = 0;

        foreach ($testData as $i => $actual) {
            if (isset($predictions[$i])) {
                $error = $actual - $predictions[$i];
                $squaredErrors += pow($error, 2);
                
                if ($actual > 0) {
                    $absolutePercentErrors += abs($error / $actual) * 100;
                    $validCount++;
                }
            }
        }

        $rmse = sqrt($squaredErrors / count($testData));
        $mape = $validCount > 0 ? $absolutePercentErrors / $validCount : null;

        return [
            'rmse' => round($rmse, 2),
            'mape' => $mape !== null ? round($mape, 2) : null
        ];
    }

    /**
     * Calcular Intervalo de Confianza (95%)
     */
    private function calculateConfidenceInterval($data, $forecast)
    {
        $n = count($data);
        if ($n < 3) return [0, 0];

        $mean = array_sum($data) / $n;
        $variance = 0;
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        $stdDev = sqrt($variance / $n);

        // Intervalo de confianza del 95% (±1.96 desviaciones estándar)
        $margin = 1.96 * $stdDev;
        
        return [
            'lower' => max(0, round($forecast - $margin)),
            'upper' => round($forecast + $margin)
        ];
    }

    /**
     * Obtener historial de ventas (extendido a N meses)
     */
    private function getSalesHistory($medicineId, $months = 12)
    {
        $data = DB::table('medicines_orders')
            ->join('orders', 'medicines_orders.order_id', '=', 'orders.id')
            ->select(
                DB::raw('YEAR(orders.created_at) as year'),
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(quantity) as total_qty')
            )
            ->where('medicine_id', $medicineId)
            ->where('orders.created_at', '>=', Carbon::now()->subMonths($months))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Formatear para rellenar meses vacíos con 0
        $history = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            
            $found = $data->first(function($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $history[$key] = $found ? $found->total_qty : 0;
        }

        return array_values($history);
    }
}
