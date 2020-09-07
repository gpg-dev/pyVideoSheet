<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\VideoCategory;

class removeAllCategoryExcludetop30 extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'categpry:keep30';

	private $timeout = 7200;
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove all category exclude top 30.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [];
	}

	public function handle()
	{
		$top30 = Category::whereRaw('isDeleted IS NULL OR isDeleted = 0')
                      ->orderBy('numOfClicks', 'DESC')->limit(30)->get();
        $idsTop30 = array_column($top30->toArray(), 'id');
        \Log::info('Keep top 30 categories: '. json_encode($idsTop30));

        $categories = Category::whereNotIn('id', $idsTop30)->get();
        $idsDelete = array_column($categories->toArray(), 'id');
        \Log::info('Remove categories: '. json_encode($idsDelete));

        $statusRemoveVC = VideoCategory::whereIn('catId', $idsDelete)->delete();

        if($statusRemoveVC !== false) {
        	foreach ($categories as $cat) {
	        	$cat->delete();
        		\Log::info('Remove categories: '. $cat->id);
        		$this->info('Remove categories: '. $cat->id);
        	}
        }
        $this->info('Finish!');
	}

}
