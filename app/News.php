<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title', 'description', 'photo', 'lat', 'lon', 'location', 'area', 'to'
    ];

    public function nearest( $latitude, $longitude, $max_distance = 5, $max_locations = 100, $units = 'kilometers')
    {
        switch ( $units ) {
            default:
            case 'miles':
                $satuan_radius = 3959;
                break;
            case 'kilometers':
                $satuan_radius = 6371;
                break;
        }
        $distance = "( $satuan_radius * acos( cos( radians($latitude) ) *
		         cos( radians( lat ) )
		            * cos( radians( lon ) - radians($longitude)) + sin( radians($latitude) ) *
		         sin( radians( lat ) ) )
	        )";
        return $this
            ->select('*')
            ->selectRaw($distance . ' AS distance') // ngambil selected distance berdasarkan $lat $long
            ->whereRaw($distance . ' < '. $max_distance) // nentuin radius
            ->take($max_locations);
    }
}
