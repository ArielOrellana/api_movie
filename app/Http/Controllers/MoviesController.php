<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use App\Models\Directors;
use App\Models\Actors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller
{
    public function movieShow(Request $request, $name=''){

        // consult movie

        if(!$name==''){
            $sql_movie= Movies::select('directors.*', 'movies.*')
            ->join('directors', 'movies.director_id', '=', 'directors.id')
            ->where('movies.title', 'like', '%'.$name.'%')
            ->get();
        }elseif($request->title){
            $sql_movie= Movies::select('directors.*', 'movies.*')
            ->join('directors', 'movies.director_id', '=', 'directors.id')
            ->where('movies.title', 'like', '%'.$request->title.'%')
            ->get();
        }elseif($request->genre){
            $sql_movie= Movies::select('directors.*', 'movies.*')
            ->join('directors', 'movies.director_id', '=', 'directors.id')
            ->where('movies.genre', 'like', '%'.$request->genre.'%')
            ->get();
        }
        elseif($request->id){
            $sql_movie= Movies::select('directors.*', 'movies.*')
            ->join('directors', 'movies.director_id', '=', 'directors.id')
            ->where('movies.id', $request->id)
            ->get();
        }else{
            $sql_movie= Movies::select('directors.*', 'movies.*')
            ->join('directors', 'movies.director_id', '=', 'directors.id')
            ->get();
        }


        $result_movie=[];
        //get list of actors for each movie
        foreach($sql_movie as $m){
            $sql_actors=Actors::select('actors.name', 'actors.id')
            ->join('actor_rel', 'actor_rel.actor_id', '=', 'actors.id')
            ->where('actor_rel.movie_id', '=', $m['id'])
            ->groupBy('actors.id')
            ->get();
            $list_actors=[];

            foreach($sql_actors as $a){
                $data_actor=[
                    'id_actor' => $a['id'],
                    'name' => $a['name'],
                ];
                array_push($list_actors, $data_actor);
            }
            $data_movie=[
                'id' => $m['id'],
                'title' => $m['title'],
                'director' => $m['name'],
                'actors' => $list_actors,
                'genre' => $m['genre'],
            ];
            array_push($result_movie, $data_movie);
        }

        return response()->json($result_movie);
    }
    public function addActorMovie(Request $request){
        $response=[];
        $this->validate($request,[
            'actors_id' => 'required',
            'movie_id' => 'required',
        ]);

        $actors_id=explode( ',', $request->actors_id);
        if(count($actors_id)>1){
            foreach($actors_id as $actor_id){
                $sql_actor=Actors::find($actor_id);
                if($sql_actor){
                    $sql_actor_rel=DB::table('actor_rel')->select('*')
                    ->where('actor_id', $sql_actor['id'])
                    ->where('movie_id', $request->movie_id)
                    ->count();
                    if($sql_actor_rel<=0){
                        $insert_rel=DB::table('actor_rel')->insert([
                            'actor_id' => $sql_actor['id'] ,
                            'movie_id' => $request->movie_id ,
                        ]);
                        if($insert_rel){
                            $status=['status'=>'add actor_id='. $sql_actor['id']];
                            array_push($response, $status);
                        }else{
                            $status=['status'=>'fail actor_id='. $sql_actor['id']];
                            array_push($response, $status);
                        }
                    }else{
                        $status=['status'=>'already exists actor_id='. $sql_actor['id']];
                        array_push($response, $status);
                    }
                }else{
                    $status=['status'=>'non-existent actor_id='. $actor_id];
                    array_push($response, $status);
                }
            }
        }else{
            $sql_actor=Actors::find($request->actors_id);
            if($sql_actor){
                $sql_actor_rel=DB::table('actor_rel')->select('*')
                ->where('actor_id', $sql_actor['id'])
                ->where('movie_id', $request->movie_id)
                ->count();
                if($sql_actor_rel<=0){
                    $insert_rel=DB::table('actor_rel')->insert([
                        'actor_id' => $sql_actor['id'] ,
                        'movie_id' => $request->movie_id ,
                    ]);
                    if($insert_rel){
                        $status=['status'=>'add actor_id='. $sql_actor['id']];
                        array_push($response, $status);
                    }else{
                        $status=['status'=>'fail actor_id='. $sql_actor['id']];
                        array_push($response, $status);
                    }
                }else{
                    $status=['status'=>'already exists actor_id='. $sql_actor['id']];
                    array_push($response, $status);
                }
            }else{
                $status=['status'=>'non-existent actor_id='. $request->actors_id];
                array_push($response, $status);
            }
        }

        return response()->json($response);
    }

    public function movieCreate(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'director_id' => 'required',
            'genre' => 'required',
        ]);
        $sql_director=Directors::find($request->director_id);
        if($sql_director){
            $movie = Movies::create([
                'title' => $request->title,
                'director_id'=> $request->director_id,
                'genre'=> $request->genre,
            ]);
        }else{
            $movie = ['director_id' => 'non-existent'];
        }
        return response()->json($movie);
    }
}
