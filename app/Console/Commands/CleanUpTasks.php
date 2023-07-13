<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanUpTasks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-up-tasks {--date_lte=  : Date in format "YYYY-mm-dd"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление задач со статусом backlog';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::channel('cleanup')->info('Начали ежедневное выполнение команды');

        try {
            $date = Carbon::parse($this->option('date_lte'));
        } catch (\Exception $exception) {
            Log::channel('cleanup')->error('Дата из параметра пришла в неверном формате');

            return false;
        }

        try {
            $rows = DB::table('tasks')
                ->where('status', 'backlog')
                ->whereDate('created_at','>=', $date ?? Carbon::now()
                    ->timezone('Europe/Moscow')
                    ->subDay(30));
            $rows->delete();

        } catch (\Exception $exception) {
            Log::channel('cleanup')->error('Ошибка при удалении/чтении из БД: ' . $exception);

            return false;
        }

        if ($rows) {
            foreach ($rows as $row) {
                Log::channel('cleanup')->info('Удалили строку с именем ' . $row->name);
            }
        } else {
            Log::channel('cleanup')->info('Не нашли подходящих строк для удаления');
        }

    }
}
