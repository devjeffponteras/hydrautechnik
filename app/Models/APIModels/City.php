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

class City extends Model
{
  
  public function getNewCityList(){

    $list = DB::table('ph_cities')->get();                                
     return $list;             
           
  }

  public function getCityList($param){

      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

    $query = DB::table('ph_cities as cty')
      ->selectraw("
        cty.CityID,
        COALESCE(cty.City,'') as City,
        COALESCE(cty.Province,'') as Province,
        COALESCE(cty.Region,'') as Region,
        COALESCE(cty.ZipCode,'') as ZipCode,
        COALESCE(cty.IslandGroup,'') as IslandGroup
      ");

      if($SearchText != ''){
          $arSearchText = explode(" ",$SearchText);
          if(count($arSearchText) > 0){
              for($x=0; $x< count($arSearchText); $x++) {
                  $query->whereraw(
                      "CONCAT_WS(' ',
              COALESCE(cty.City,''),
              COALESCE(cty.Province,''),
              COALESCE(cty.ZipCode,''),
              COALESCE(cty.IslandGroup,'')
                      ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
              }
          }
      }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderByraw("COALESCE(cty.City,'') ASC");
    $query->orderByraw("COALESCE(cty.Province,'') ASC");
    
    $list = $query->get();

    return $list;

  }

  public function getCityInfo($CityID){

    $info = DB::table('countrycities as cty')
      ->selectraw("
        cty.CityID,
        COALESCE(cty.City,'') as City,
        COALESCE(cty.Province,'') as Province,
        COALESCE(cty.Region,'') as Region,
        COALESCE(cty.ZipCode,'') as ZipCode,
        COALESCE(cty.IslandGroup,'') as IslandGroup
      ")
      ->whereRaw("cty.CityID = ?",[$CityID])
      ->first();

    return $info;

  }

  public function getCityByID($CityID){

    $info = $this->getCityInfo($CityID);

    $City = "";
    if(isset($info)){
      $City = $info->City;
    }

    return $City;

  }






}