<?php

class DataParser {

  private $popupationData = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/UID_ISO_FIPS_LookUp_Table.csv';
  private $countries = 'https://raw.githubusercontent.com/datasets/covid-19/master/data/countries-aggregated.csv';

  //$popupationCSV = file_read_contents($popupationData);

  private $populationDataCountryKey = 'Country_Region';
  private $populationDataPopuplationKey = 'Population';
  private $populationDataCountryIndex = 0;
  private $populationDataPopuplationIndex = 0;

  private $countiesDateField = 'Date';
  private $countiesCountryField = 'Country';
  private $countiesConfirmedField = 'Confirmed';
  private $countiesRecoveredField = 'Recovered';
  private $countiesDeathsField = 'Deaths';

  private $countiesDateIndex = 0;
  private $countiesCountryIndex = 0;
  private $countiesConfirmedIndex = 0;
  private $countiesRecoveredIndex = 0;
  private $countiesDeathsIndex = 0;

  private $countriesWithPopulation = [];
  private $fullParsed = [];

  function __construct(){
    if (($handle = fopen($this->popupationData, "r")) !== FALSE) {
        $row = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if($row === 0){
              for ($c=0; $c < $num; $c++) {
                if($this->populationDataCountryKey == $data[$c])
                  $this->populationDataCountryIndex = $c;


                if($this->populationDataPopuplationKey == $data[$c])
                  $this->populationDataPopuplationIndex = $c;
              }
            } else {
              if(!isset($this->countriesWithPopulation[ $data[$this->populationDataCountryIndex] ] ) ) {
                $this->countriesWithPopulation[ $data[$this->populationDataCountryIndex] ] =  intval( $data[ $this->populationDataPopuplationIndex ] );
              } else {
                $this->countriesWithPopulation[ $data[$this->populationDataCountryIndex] ] += intval( $data[ $this->populationDataPopuplationIndex ] );
              }
            }

            $row++;
        }
        fclose($handle);
    }

  }

  public function parseCountries(){
    if (($handle = fopen($this->countries, "r")) !== FALSE) {
        $row = 0;
        $prevData = null;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if($row === 0){
              for ($c=0; $c < $num; $c++) {
                if($this->countiesDateField == $data[$c])
                  $this->countiesDateIndex = $c;

                if($this->countiesCountryField == $data[$c])
                  $this->countiesCountryIndex = $c;

                if($this->countiesConfirmedField == $data[$c])
                  $this->countiesConfirmedIndex = $c;

                if($this->countiesRecoveredField == $data[$c])
                  $this->countiesRecoveredIndex = $c;

                if($this->countiesDeathsField == $data[$c])
                  $this->countiesDeathsIndex = $c;
              }
            } else {
              
              if(!isset($this->fullParsed[ $data[$this->countiesCountryIndex] ] ) ) {

                $this->fullParsed[ $data[$this->countiesCountryIndex] ] = [
                  'population' => intval($this->countriesWithPopulation[ $data[$this->countiesCountryIndex] ]),
                  'totals' => [
                    'deaths' => 0,
                    'recovered' => 0,
                    'confirmed' => 0,
                  ],
                  'avg' => [
                    'deaths' => 0,
                    'recovered' => 0,
                    'confirmed' => 0,
                  ],
                  'days' => []
                ];
              } else {
                $population = $this->fullParsed[ $data[$this->countiesCountryIndex] ]['population'];

                $population = $population ? $population : 1;


                $this->fullParsed[ $data[$this->countiesCountryIndex] ]['totals']['deaths'] +=  intval($data[$this->countiesDeathsIndex]);
                $this->fullParsed[ $data[$this->countiesCountryIndex] ]['totals']['recovered'] +=  intval($data[$this->countiesRecoveredIndex]);
                $this->fullParsed[ $data[$this->countiesCountryIndex] ]['totals']['confirmed'] +=  intval($data[$this->countiesConfirmedIndex]);

                $tmp = [
                  'date' => $data[$this->countiesDateIndex],
                  'timestamp' => strtotime($data[$this->countiesDateIndex]),
                  'incremental' => [
                    'deaths' => intval($data[$this->countiesDeathsIndex]),
                    'recovered' => intval($data[$this->countiesRecoveredIndex]),
                    'confirmed' => intval($data[$this->countiesConfirmedIndex]),
                    'avg' => [
                      'deaths' => intval($data[$this->countiesDeathsIndex]) / $population,
                      'recovered' => intval($data[$this->countiesRecoveredIndex]) / $population,
                      'confirmed' => intval($data[$this->countiesConfirmedIndex]) / $population,
                    ]
                  ],
                  'diff' => [
                    'deaths' => intval($data[$this->countiesDeathsIndex]),
                    'recovered' => intval($data[$this->countiesRecoveredIndex]),
                    'confirmed' => intval($data[$this->countiesConfirmedIndex]),
                  ],


                ];

                if($prevData !== null){
                  $tmp['diff'] = [
                    'deaths' => intval($data[$this->countiesDeathsIndex]) - intval($prevData[$this->countiesDeathsIndex]),
                    'recovered' => intval($data[$this->countiesRecoveredIndex]) - intval($prevData[$this->countiesRecoveredIndex]),
                    'confirmed' => intval($data[$this->countiesConfirmedIndex]) - intval($prevData[$this->countiesConfirmedIndex]),
                    'avg' => [
                      'deaths' => ( intval($data[$this->countiesDeathsIndex]) - intval($prevData[$this->countiesDeathsIndex]) ) / $population,
                      'recovered' => ( intval($data[$this->countiesRecoveredIndex]) - intval($prevData[$this->countiesRecoveredIndex]) ) / $population,
                      'confirmed' => ( intval($data[$this->countiesConfirmedIndex]) - intval($prevData[$this->countiesConfirmedIndex]) ) / $population,
                    ]
                  ];
                }
                $this->fullParsed[ $data[$this->countiesCountryIndex] ]['days'][] = $tmp;
              }
              $prevData = $data;
            }
            $row++;
        }
        fclose($handle);

        foreach($this->fullParsed as $country => $data){
          $population = $data['population'] ? $data['population'] : 1;
          $totals = $data['totals'];
          $this->fullParsed[$country]['avg'] = [
            'deaths' => intval($totals['deaths']) / $population,
            'recovered' => intval($totals['recovered']) / $population,
            'confirmed' => intval($totals['confirmed']) / $population,
          ];
        }

        return $this;
    }
  }

  public function save(){

    file_put_contents('data.json', json_encode( $this->fullParsed, JSON_PRETTY_PRINT ));

  }
}

$dp = new DataParser();

$dp->parseCountries()->save();