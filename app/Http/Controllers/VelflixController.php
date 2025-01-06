<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VelflixController extends Controller
{
    /**
     * @param  mixed  $genreId
     * @return mixed
     */
    private function getMoviesByGenre($genreId)
    {
        
        return Http::get('https://api.themoviedb.org/3/discover/movie', [
            'api_key' => config('services.tmdb.token'),
            'with_genres' => $genreId,
        ])->json()['results'];
       
    }

    public function index(): View|Factory
    {
       
    
            $popular=  Http::get('https://api.themoviedb.org/3/movie/popular?api_key='.config('services.tmdb.token'))
                ->json()['results'];
    
     

        $trending = Http::get('https://api.themoviedb.org/3/trending/movie/day?api_key='.config('services.tmdb.token'))
                ->json()['results'];
              
                $velflixgenres = Http::get('https://api.themoviedb.org/3/genre/movie/list?api_key='.config('services.tmdb.token'))
                ->json()['genres'];
        
               ;
            
        $comedies = $this->getMoviesByGenre(35);
        $action = $this->getMoviesByGenre(28);
        $western = $this->getMoviesByGenre(37);
        $horror = $this->getMoviesByGenre(27);
        $thriller = $this->getMoviesByGenre(53);
        $animation = $this->getMoviesByGenre(16);
        
       
        /** @psalm-suppress UndefinedClass **/
        $genres = collect($velflixgenres)->mapWithKeys(function ($genre) {  /** @phpstan-ignore-line */
            return [$genre['id'] => $genre['name']];
        });
       
        return view('main', [
            'popular' => $popular,
            'genres' => $genres,
            'trending' => $trending,
            'comedies' => $comedies,
            'western' => $western,
            'action' => $action,
            'horror' => $horror,
            'thriller' => $thriller,
            'animation' => $animation,
        ]);
    }

    /**
     * @param  mixed  $id
     * @return View|Factory
     */
    public function show($id): View|Factory
    {
        
        $playMovie = 
           Http::get('https://api.themoviedb.org/3/movie/'.$id.'?append_to_response=credits,videos,images&api_key='.config('services.tmdb.token'))
            ->json();
       

        return view('components.movies.show', [
            'movies' => $playMovie,
        ]);
    }
}
