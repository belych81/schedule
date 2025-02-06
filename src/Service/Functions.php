<?php
namespace App\Service;

class Functions
{

  public function getParamUrl($param, $url)
  {
      $paramsUrl = [];
      $urlComponents = parse_url($url);

      if (\array_key_exists('query', $urlComponents)) {
          parse_str(($urlComponents['query'] ?? ''), $paramsUrl);
          if(\array_key_exists($param, $paramsUrl)) {
            return $paramsUrl[$param];
          }
      }
      return false;
  }

  public function filterToUrl(array $filter) :string
  {
    $str = '';
    foreach ($filter as $key => $value) {
      if($value){
        $str .= '&'.$key.'='.$value;
      }
    }
    return $str;
  }


  public function morph(int $n, string $f1, string $f2, string $f5) :string
  {
      $n = abs(intval($n)) % 100;
      if ($n > 10 && $n < 20) return $f5;
      $n = $n % 10;
      if ($n > 1 && $n < 5) return $f2;
      if ($n == 1) return $f1;
      return $f5;
  }

  public function getMonth(string $m): string
    {
      $months = [
        '01' => 'января',
        '02' => 'февраля',
        '03' => 'марта',
        '04' => 'апреля',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'августа',
        '09' => 'сентября',
        '10' => 'октября',
        '11' => 'ноября',
        '12' => 'декабря'
      ];

      return $months[$m] ?? '';
    }

    public function getMonthName(string $m): string
    {
      $months = [
        '01' => 'январь',
        '02' => 'февраль',
        '03' => 'март',
        '04' => 'апрель',
        '05' => 'май',
        '06' => 'июнь',
        '07' => 'июль',
        '08' => 'август',
        '09' => 'сентябрь',
        '10' => 'октябрь',
        '11' => 'ноябрь',
        '12' => 'декабрь'
      ];

      return $months[$m] ?? '';
    }

}
