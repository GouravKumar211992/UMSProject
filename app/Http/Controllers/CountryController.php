<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function countries(Request $request)
    {
        try{
            $countries = Country::select('id AS value', 'name AS label') -> get();
            return response() -> json([
                'data' => array(
                    'countries' => $countries
                )
            ]);
        } catch(\Exception $ex) {
            return response() -> json([
                'message' => $ex -> getMessage()
            ]);
        }
    }

    public function states(Request $request, String $countryId)
    {
        try{
            $states = State::where('country_id', $countryId) -> select('id AS value', 'name AS label') -> get();
            return response() -> json([
                'data' => array(
                    'states' => $states
                )
            ]);
        } catch(\Exception $ex) {
            return response() -> json([
                'message' => $ex -> getMessage()
            ]);
        }
    }
    public function cities(Request $request, String $stateId)
    {
        try{
            $cities = City::where('state_id', $stateId) -> select('id AS value', 'name AS label') -> get();
            return response() -> json([
                'data' => array(
                    'cities' => $cities
                )
            ]);
        } catch(\Exception $ex) {
            return response() -> json([
                'message' => $ex -> getMessage()
            ]);
        }
    }
}
