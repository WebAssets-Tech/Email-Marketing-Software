<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CSV {
    public $path;

    public function __construct(?string $path = null) {
        $this->path = $path;
    }

    public function upload(UploadedFile $file): string {
        $path = $this->saveFile(file: $file, disk: 'temp', dir: 'csv');
        $this->path = public_path('temp/'.$path);

        return $path;
    }

    public function parse() {
        $rows = [];
        $handle = fopen($this->path, 'r');
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);
        // Remove the first one that contains headers
        $headers = array_shift($rows);

        return $this->mapParsedItems($headers, $rows);

    }

    public function mapParsedItems($headers, $rows) {
        $result = [];
        foreach ($rows as $value) {
            $array = [];
            foreach ($headers as $key => $column) {
                $array = array_merge($array, [$column => $value[$key]]);
                // dd($column);
            }
            $result[] = $array;
        }

        return $result;

    }

    /**
     * Stores an image given an image request and a directory
     *
     * @param  UploadedFile  $file
     * @param  string  $old_path
     * @param  string  $prefix  skip if you need clientOriginalName
     * @param  string  $disk  default = public
     * @return string $new_path
     */
    public function saveFile($file, string $dir, ?string $prefix = '', string $disk = 'public') {
        if ($file) {
            if ($prefix === '' || $prefix === null) {
                $prefix = Str::slug($file->getClientOriginalName());
            }
            $ext = $file->extension();
            $name = $prefix.'-'.$this->timestamp().'.'.$ext;
            $path = $file->storeAs("uploads/$dir", $name, $disk);

            return $path;
        } else {
            return $file;
        }
    }

    /**
     * Returns current timestamp in format = 'Y-m-d-H-m-s-u'
     *
     * @return string
     */
    public function timestamp() {
        return Carbon::now()->format('Y-m-d-H-m-s-u');
    }
}
