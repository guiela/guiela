<?php

namespace App\Services;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Exceptions\CsvException;
use Illuminate\Support\Collection;

/**
 * FileService class
 * 
 * @package App\Services
 * @author Nascent Africa <nascent.afrique@gmail.com>
 */
class CsvService
{
    /**
     * Domestic data
     *
     * @var array
     */
    protected $domestic = [];

    /**
     * Foreign data
     *
     * @var array
     */
    protected $foreign = [];

    /**
     * The column to identify the row with.
     *
     * @var array
     */
    protected $identifiers = [];

    /**
     * The columns to to compare against rows
     *
     * @var string
     */
    protected $matcher = '';

    /**
     * Identifier value pair for a more specific search.
     *
     * @var array
     */
    protected $pairs = []; 

    /**
     * Set domestic data
     *
     * @param  array  $domestic  Domestic data
     *
     * @return  self
     */ 
    public function setDomestic(array $domestic)
    {
        $this->domestic = $domestic;
        $this->domestic['records'] = collect($domestic['records']);

        return $this;
    }

    /**
     * Set foreign data
     *
     * @param  array  $foreign  Foreign data
     *
     * @return  self
     */ 
    public function setForeign(array $foreign)
    {
        $this->foreign = $foreign;
        $this->foreign['records'] = collect($foreign['records']);

        return $this;
    }

    /**
     * Set the column to identify the row with.
     *
     * @param  array  $identifiers  The column to identify the row with.
     *
     * @return  self
     */ 
    public function setIdentifiers(array $identifiers)
    {
        $this->identifiers = $identifiers;

        return $this;
    }

    /**
     * Set identifier value pair for a more specific search.
     *
     * @param  array  $pairs  Identifier value pair for a more specific search.
     *
     * @return  self
     */ 
    public function setPairs(array $pairs)
    {
        $this->pairs = $pairs;

        return $this;
    }

    /**
     * Set the columns to to compare against rows
     *
     * @param  string  $matchers  The columns to to compare against rows
     *
     * @return  self
     */ 
    public function setMatcher(string $matcher)
    {
        $this->matcher = strtolower($matcher);

        return $this;
    }
    
    /**
     * Extract data from file path into an object
     *
     * @param mixed $file
     * @param string $delimiter
     * @param string $originalName
     * @return array
     */
    public function extractDataFromFile($file, string $delimiter, string $originalName): array
    {
        //load the CSV document from a stream
        $stream = fopen($file, 'r');
        $file = Reader::createFromStream($stream);
        $file->setDelimiter($delimiter);
        $file->setHeaderOffset(0);

        //build a statement
        $stmt = new Statement();

        //query your records from the document
        $records = $stmt->process($file);

        $result = $this->getConvertedCSV($records, $originalName);

        return $result;
    }

    /**
     * Create a collection from the from the records.
     * 
     * @param mixed $records
     * @param string $originalName
     * @return array
     */
    protected function getConvertedCSV($records, string $originalName): array
    {
        $data = [];

        // Loop through
        foreach ($records->getRecords() as $row) {
            
            //  1.  Trim the values of the array keys and values to remove white spaces 
            //      which could cause a bug in our code when applying a where clause.
            $keys = array_map('trim', array_keys($row));
            $value = array_map('trim', $row);
            $stripResults = array_combine($keys, $value);
    
            //  2.  Making sure array keys are always lowercase regardless the 
            //      file format to have uniform format.
            $lowercaseResult = array_change_key_case(array_map('trim', $stripResults), CASE_LOWER);

            $merged = array_merge([
                'uuid' => Uuid::uuid4(),
                'original_data' => $lowercaseResult
            ], $lowercaseResult);

            // Wrap the data in an eloquent model.
            $data[] = $merged;
        }

        return [
            'header'    => $records->getHeader(),
            'records'      => $data,
            'meta'      => [
                // 'name' => $request->file->getClientOriginalName(),
                'name' => $originalName,
                'uploaded_at' => Carbon::now(),
                'header' => [
                    'count' => count($records->getHeader())
                ],
                'records' => [
                    'count' => count($data)
                ]
            ]
        ];
    }

    public function matchCSV()
    {
        $domesticRecords = $this->filerCsvData($this->domestic);
        $filteredForeignRecords = $this->filerCsvData($this->foreign);

        $newForeignRecords = [];
        $newDomesticRecords = [];

        foreach ($domesticRecords as $domesticKey => $domesticRecord) {

            $foreignRecords = $this->sortForeignData($filteredForeignRecords, $domesticRecord);

            /**
             * Despite we didn't request for a single record, we however require
             * one record in the collection. The reason for this is to be able
             * to retrieve the record index in the original collection to 
             * avoid duplication in the end result.
             */
            foreach ($foreignRecords as $foreignKey => $foreignRecord) {
                
                if (empty($foreignRecord)) {
                    
                    $newDomesticRecords[$domesticKey] = $this->mutateRecord($domesticRecord, 'absent');

                    continue;
                } 
                
                if ($domesticRecord[$this->matcher] === $foreignRecord[$this->matcher]) {
                    
                    $newDomesticRecords[$domesticKey] = $this->mutateRecord($domesticRecord, 'matched', $this->foreign['header'], $foreignRecord);
                    $newForeignRecords[$foreignKey] = $this->mutateRecord($foreignRecord, 'matched', $this->domestic['header'], $domesticRecord);

                    continue;
                } 
                
                if ($domesticRecord[$this->matcher] !== $foreignRecord[$this->matcher]) {

                    $newDomesticRecords[$domesticKey] = $this->mutateRecord($domesticRecord, 'unmatched', $this->foreign['header'], $foreignRecord);
                    $newForeignRecords[$foreignKey] = $this->mutateRecord($foreignRecord, 'unmatched', $this->domestic['header'], $domesticRecord);

                    continue;
                }
            }
        }

        $this->domestic['records'] = $this->replace($this->domestic['records'], $newDomesticRecords);
        $this->foreign['records'] = $this->replace($this->foreign['records'], $newForeignRecords);

        return $this;
    }

    /**
     * Get process result.
     *
     * @return array
     */
    public function getResult(): array
    {
        return [
            'domestic' => $this->domestic,
            'foreign' => $this->foreign
        ];
    }

    protected function replace($oldRecord, $newRecord) 
    {
        if ($oldRecord instanceof Collection) {
            $merged = $oldRecord->replace($newRecord);

            return $merged->all();
        } else {
            $collection = collect($oldRecord);

            $merged = $collection->replace($newRecord);

            return $merged->all();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $record
     * @param string $info
     * @param array $header
     * @param array $matchingRecord
     * @return Collection
     */
    protected function mutateRecord(array $record, string $info, array $header, array $matchingRecord = [])
    {

        if (empty($record)) {
            throw new CsvException('Record passed to "mutateRecord" is empty.');
        }

        $information = [
            'absent' => [
                'error' => null,
                'css_style' => 'table-warning',
                'info' => 'absent',
                'match' => [
                    'header' => $header,
                    'record' => $matchingRecord
                ]
            ],

            'matched' => [
                'error' => null,
                'css_style' => 'table-active',
                'info' => 'matched',
                'match' => [
                    'header' => $header,
                    'record' => $matchingRecord
                ]
            ],

            'unmatched' => [
                'error' => null,
                'css_style' => 'table-warning',
                'info' => 'unmatched',
                'match' => [
                    'header' => $header,
                    'record' => $matchingRecord
                ]
            ]
        ];

        $record['meta'] = $information[$info];

        return $record;
    }

    /**
     * Return csv records as it is if the user
     * does not specify row to compare against the
     * foreign records.
     * 
     * @return void
     */
    protected function filerCsvData($csv)
    {
        $records = $csv['records'];

        if (empty($this->pairs)) {
            return $records;
        }

        $margeIdentifiersWithPairs = $this->margeIdentifiersWithPairs();

        foreach ($margeIdentifiersWithPairs as $field => $value) {
            $records = $records->where($field, '=', $value);
        }

        if (empty($records)) {
            throw new CsvException('Unable to retrieve record from '.$csv['meta']['name'].' file.');
        }

        return $records;
    }

    /**
     * Undocumented function
     *
     * @param mixed $foreignRecords
     * @return void
     */
    protected function sortForeignData($foreignRecords, $domesticRecord)
    {
        $records = collect($foreignRecords);

        if (empty($this->pairs)) {
            // Loop through the identifiers and apply constraints.
            foreach ($this->identifiers as $identifier) {
                $records = $records->where(strtolower($identifier), $domesticRecord[strtolower($identifier)]);
            }
        } else {
            $identifier = $this->margeIdentifiersWithPairs();

            foreach ($identifier as $key => $value) {
                $records = $records->where(strtolower($key), $domesticRecord[strtolower($key)]);
            }
        }

        return $records->all();
    }

    /**
     * Merge identifiers with it's pairs.
     *
     * @return array
     */
    protected function margeIdentifiersWithPairs(): array
    {
        if (empty($this->pairs)) {
            throw CsvException('There is no pair values to merge with the identifiers');
        }

        return array_change_key_case(array_combine($this->identifiers, $this->pairs), CASE_LOWER);
    }
}