<?php

namespace App\Models\APIModels;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Session;
use Hash;
use View;
use Input;
use Image;
use DB;

use App\Models\APIModels\Misc;

class Country extends Model
{

  public function getCountryList($param){

      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

    $query = DB::table('country as ctry')
      ->selectraw("
        ctry.CountryID,
        COALESCE(ctry.Country,'') as Country,

        COALESCE(ctry.A2,'') as A2,
        COALESCE(ctry.A3,'') as A3,
        COALESCE(ctry.DialingCode,'') as DialingCode,

        COALESCE(ctry.CurrencyCode,'') as CurrencyCode,
        COALESCE(ctry.Currency,'') as Currency
      ");

      if($SearchText != ''){
          $arSearchText = explode(" ",$SearchText);
          if(count($arSearchText) > 0){
            for($x=0; $x< count($arSearchText); $x++) {
              $query->whereraw(
              "CONCAT_WS(' ',
              COALESCE(ctry.Country,''),
              COALESCE(ctry.A2,''),
              COALESCE(ctry.A3,''),
              COALESCE(ctry.DialingCode,''),
              COALESCE(ctry.CurrencyCode,''),
                  COALESCE(ctry.Currency,'')
                      ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
            }
          }
      }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderByraw("COALESCE(ctry.Country,'') ASC");
    
    $list = $query->get();

    return $list;

  }

  public function getCountryInfo($CountryID){

    $info = DB::table('country as ctry')
      ->selectraw("
        ctry.CountryID,
        COALESCE(ctry.Country,'') as Country,

        COALESCE(ctry.A2,'') as A2,
        COALESCE(ctry.A3,'') as A3,
        COALESCE(ctry.DialingCode,'') as DialingCode,

        COALESCE(ctry.CurrencyCode,'') as CurrencyCode,
        COALESCE(ctry.Currency,'') as Currency
      ")
      ->whereRaw("ctry.CountryID = ?",[$CountryID])
      ->first();

    return $info;

  }

  public function getCountryByID($CountryID){

    $info = $this->getCountryInfo($CountryID);

    $Country = "";
    if(isset($info)){
      $Country = $info->Country;
    }

    return $Country;

  }







}
