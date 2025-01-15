<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dompdf\Dompdf;

class GenerateTestReport extends Command
{
    protected $signature = 'test:report {input} {output}';
    protected $description = 'Convert XML test results to PDF';

    public function handle()
    {
        $inputFile = $this->argument('input');
        $outputFile = $this->argument('output');

        if (!file_exists($inputFile)) {
            $this->error("Fichier d'entrée introuvable : {$inputFile}");
            return 1;
        }

        try {
            $this->info("Chargement du fichier XML...");
            $xml = simplexml_load_file($inputFile);
        } catch (\Exception $e) {
            $this->error("Erreur lors du chargement du fichier XML : " . $e->getMessage());
            return 1;
        }

        // Extraction des statistiques
        $totalTests = 0;
        $successCount = 0;
        $failureCount = 0;
        $totalTime = 0.0;
        $tests = [];

        foreach ($xml->xpath('//testcase') as $testcase) {
            $totalTests++;
            $status = isset($testcase->failure) ? 'Échec' : 'Succès';

            if ($status === 'Succès') {
                $successCount++;
            } else {
                $failureCount++;
            }

            // Ajout du temps du test au total
            $time = (float) $testcase['time'];
            $totalTime += $time;

            $tests[] = [
                'name' => (string) $testcase['name'],
                'status' => $status,
                'time' => number_format($time, 3) . ' s'
            ];
        }

        $statistics = [
            'total' => $totalTests,
            'success' => $successCount,
            'failure' => $failureCount,
            'totalTime' => number_format($totalTime, 3) . ' s'
        ];

        // Rendu du HTML
        $html = view('test-report', [
            'tests' => $tests,
            'statistics' => $statistics
        ])->render();

        // Génération du PDF
        try {
            $this->info("Génération du fichier PDF...");
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();

            $outputDir = dirname($outputFile);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            file_put_contents($outputFile, $dompdf->output());
            $this->info("Rapport PDF généré avec succès : {$outputFile}");
        } catch (\Exception $e) {
            $this->error("Erreur lors de la génération du PDF : " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
