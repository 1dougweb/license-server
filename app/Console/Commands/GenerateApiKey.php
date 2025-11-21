<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiKey;
use Carbon\Carbon;

class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-key:generate {name?} {--expires=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera uma nova API key para autenticação';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name') ?? $this->ask('Nome da API key', 'Default API Key');

        $expiresAt = null;
        if ($this->option('expires')) {
            try {
                $expiresAt = Carbon::parse($this->option('expires'));
            } catch (\Exception $e) {
                $this->error('Data de expiração inválida. Use formato: YYYY-MM-DD ou YYYY-MM-DD HH:MM:SS');
                return Command::FAILURE;
            }
        }

        $result = ApiKey::generate($name, $expiresAt);

        $this->info('✅ API Key gerada com sucesso!');
        $this->newLine();
        $this->line('ID: ' . $result['id']);
        $this->line('Nome: ' . $result['name']);
        $this->line('Criada em: ' . $result['created_at']);
        if ($expiresAt) {
            $this->line('Expira em: ' . $expiresAt->format('Y-m-d H:i:s'));
        }
        $this->newLine();
        $this->warn('⚠️  IMPORTANTE: Copie a API key abaixo. Ela não será exibida novamente!');
        $this->newLine();
        $this->line('API Key: ' . $result['key']);
        $this->newLine();
        $this->info('Use esta chave no header X-API-Key ou Authorization: Bearer {key}');

        return Command::SUCCESS;
    }
}
