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
            $this->error("Input file does not exist: {$inputFile}");
            return 1;
        }

        $xml = simplexml_load_file($inputFile);
        $html = view('test-report', ['tests' => $xml])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        file_put_contents($outputFile, $dompdf->output());

        $this->info("PDF report generated: {$outputFile}");
        return 0;
    }
}
