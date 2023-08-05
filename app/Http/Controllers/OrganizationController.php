<?php

namespace App\Http\Controllers;

use App\Models\Directors;
use App\Models\Actors;
use App\Models\Movies;
use App\Models\TvShow;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function actorCreate(Request $request){
        $this->validate($request,[
            'name'=>'required'
        ]);
        $actor= Actors::create([
            'name' => $request->name,
        ]);
        return response()->json($actor);
    }
    public function directorCreate(Request $request){
        $this->validate($request,[
            'name'=>'required'
        ]);
        $director= Directors::create([
            'name' => $request->name,
        ]);
        return response()->json($director);
    }
    public function directors(Request $request){
        $response=[];

        if($request->director_id){
            $sql_director=Directors::where('id', $request->director_id)->get();
        }elseif($request->name){
            $sql_director=Directors::where('name','like' , '%'.$request->name.'%')->get();
        }else{
            $sql_director=Directors::all();
        }

        foreach($sql_director as $d){
            $sql_movie = Movies::where('director_id', $d['id'])->get();
            $list_movie=[];

            foreach($sql_movie as $m){
                $data_movie=[
                    'movie_id' => $m['id'],
                    'name_movie' => $m['title']
                ];
                array_push($list_movie, $data_movie);
            }

            $sql_tv = TvShow::where('director_id', $d['id'])->get();
            $list_tv=[];

            foreach($sql_tv as $tv){
                $data_tv=[
                    'tv_show_id' => $tv['id'],
                    'name_tv_show' => $tv['title']
                ];
                array_push($list_tv, $data_tv);
            }

            $data_director=[
                'director_id' => $d['id'],
                'director' => $d['name'],
                'movies' => $list_movie,
                'tv_shows' => $list_tv,
                'created_at'=>$d['created_at'],
                'updated_at'=>$d['updated_at'],
            ];
            array_push($response, $data_director);
        }

        return response()->json($response);
    }
    public function actors(Request $request){
        $response=[];

        if($request->actor_id){
            $sql_actor=Actors::where('id', $request->actor_id)->get();
        }elseif($request->name){
            $sql_actor=Actors::where('name','like' , '%'.$request->name.'%')->get();
        }else{
            $sql_actor=Actors::all();
        }

        foreach($sql_actor as $a){
            $sql_movie = Movies::join('actor_rel', 'actor_rel.movie_id', '=', 'movies.id')->where('actor_rel.actor_id', $a['id'])->get();
            $list_movie=[];

            foreach($sql_movie as $m){
                $data_movie=[
                    'movie_id' => $m['id'],
                    'name_movie' => $m['title']
                ];
                array_push($list_movie, $data_movie);
            }

            $sql_tv = TvShow::join('actor_rel', 'actor_rel.tv_show_id', '=', 'tv_shows.id')->where('actor_rel.actor_id', $a['id'])->get();
            $list_tv=[];

            foreach($sql_tv as $tv){
                $data_tv=[
                    'tv_show_id' => $tv['id'],
                    'name_tv_show' => $tv['title']
                ];
                array_push($list_tv, $data_tv);
            }

            $data_actor=[
                'actor_id' => $a['id'],
                'actor' => $a['name'],
                'movies' => $list_movie,
                'tv_shows' => $list_tv,
                'created_at'=>$a['created_at'],
                'updated_at'=>$a['updated_at'],
            ];
            array_push($response, $data_actor);
        }

        return response()->json($response);
    }
}
