<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

/**
 * Class SirutaImportSeeder
 */
class SirutaImportSeeder extends CsvSeeder
{
    /**
     * SirutaImportSeeder constructor.
     */
    public function __construct()
    {
        $this->insert_chunk_size = 100;
        $this->csv_delimiter = ',';
        $this->table = 'siruta';
        $this->filename = base_path() . '/database/seeds/csv/siruta.csv';
    }

    public function run()
    {

        parent::run();
    }
}
