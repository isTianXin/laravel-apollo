<?php

namespace IsTianXin\Apollo\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use IsTianXin\Apollo\ApolloService;

class StartApollo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apollo:start {--restart-when-error : restart when error occurred}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start apollo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        /** @var ApolloService $apollo */
        $apollo = app()->make(ApolloService::class);

        //start
        $this->line('PID: [' . getmypid() . '] start');
        $restart = $this->option('restart-when-error');
        do {
            try {
                $error = $apollo->getClient()->start($apollo->listener());
            } catch (Exception $ex) {
                $error = $ex->getMessage();
            }
            if ($error) {
                $this->line($error);
            }
        } while ($restart && $error);
    }
}
