<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dompdf\Dompdf;

class GenerateTestReport extends Command
{
    protected $signature = 'test:report {unitTestResults} {integrationTestResults} {output} {--unit-time=} {--integration-time=}';
    protected $description = 'Convert XML test results to a consolidated PDF report';

    public function handle()
    {
        $unitTestFile = $this->argument('unitTestResults');
        $integrationTestFile = $this->argument('integrationTestResults');
        $outputFile = $this->argument('output');

        $unitTime = $this->option('unit-time') ?: 0;
        $integrationTime = $this->option('integration-time') ?: 0;

        // Charger les résultats XML
        $unitTests = $this->loadXmlResults($unitTestFile, 'Unitaires');
        $integrationTests = $this->loadXmlResults($integrationTestFile, 'Intégration');

        // Fusionner les statistiques
        $statistics = [
            'total' => $unitTests['total'] + $integrationTests['total'],
            'success' => $unitTests['success'] + $integrationTests['success'],
            'failure' => $unitTests['failure'] + $integrationTests['failure'],
            'unitTime' => number_format($unitTime, 3) . ' s',
            'integrationTime' => number_format($integrationTime, 3) . ' s',
            'totalTime' => number_format($unitTime + $integrationTime, 3) . ' s',
        ];

        // Fusionner les tests
        $tests = array_merge($unitTests['tests'], $integrationTests['tests']);

        // Génération du HTML
        $html = view('test-report', [
            'tests' => $tests,
            'statistics' => $statistics
        ])->render();

        // Génération du PDF
        return $this->generatePdf($html, $outputFile);
    }

    private function loadXmlResults(string $filePath, string $category): array
    {
        try {
            $xml = simplexml_load_file($filePath);
        } catch (\Exception $e) {
            $this->error("Erreur lors du chargement du fichier XML : " . $e->getMessage());
            throw $e;
        }

        $results = [
            'total' => 0,
            'success' => 0,
            'failure' => 0,
            'tests' => []
        ];

        foreach ($xml->xpath('//testcase') as $testcase) {
            $results['total']++;
            $status = isset($testcase->failure) ? 'Échec' : 'Succès';

            if ($status === 'Succès') {
                $results['success']++;
            } else {
                $results['failure']++;
            }

            $time = (float) $testcase['time'];

            $results['tests'][] = [
                'name' => "[{$category}] " . (string)$testcase['name'],
                'status' => $status,
                'time' => number_format($time, 3) . ' s'
            ];
        }

        return $results;
    }

    private function generatePdf(string $html, string $outputFile): int
    {
        try {
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();

            $outputDir = dirname($outputFile);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            file_put_contents($outputFile, $dompdf->output());
            $this->info("Rapport PDF généré avec succès : {$outputFile}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Erreur lors de la génération du PDF : " . $e->getMessage());
            return 1;
        }
    }
}
