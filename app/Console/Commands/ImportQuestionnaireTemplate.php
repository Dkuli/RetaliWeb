<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionnairesImport;

class ImportQuestionnaireTemplate extends Command
{
    protected $signature = 'questionnaire:import {file}';
    protected $description = 'Import questionnaire template from Excel file';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        try {
            DB::beginTransaction();

            // Implementation for Excel import
            // You'll need to install and configure Laravel Excel package
            Excel::import(new QuestionnairesImport, $file);

            DB::commit();
            $this->info('Questionnaire template imported successfully');
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: {$e->getMessage()}");
            return 1;
        }
    }
}