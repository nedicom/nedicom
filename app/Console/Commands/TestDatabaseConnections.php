<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseConnections extends Command
{
    protected $signature = 'db:test-all';
    protected $description = 'Test all database connections';

    public function handle()
    {
        $this->info('Testing database connections...');
        
        // Тест MySQL
        $this->testConnection('mysql', 'MySQL');
        
        // Тест PostgreSQL
        $this->testConnection('pgsql_stats', 'PostgreSQL (stats)');
        
        return Command::SUCCESS;
    }
    
    private function testConnection($connection, $name)
    {
        $this->line("\n<fg=cyan>Testing {$name} connection...</>");
        
        try {
            $pdo = DB::connection($connection)->getPdo();
            
            // Получаем информацию о БД
            $databaseName = DB::connection($connection)->getDatabaseName();
            $version = DB::connection($connection)->select('SELECT version() as version')[0]->version;
            
            $this->info("✓ {$name} connected successfully!");
            $this->line("  Database: {$databaseName}");
            $this->line("  Version: " . substr($version, 0, 50) . "...");
            
        } catch (\Exception $e) {
            $this->error("✗ {$name} connection failed!");
            $this->line("  Error: " . $e->getMessage());
        }
    }
}