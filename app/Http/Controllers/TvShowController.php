<?php

namespace App\Http\Controllers;

use App\Models\TvShow;
use App\Models\Directors;
use App\Models\Actors;
use App\Models\Episodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TvShowController extends Controller
{
    public function tvShow(Request $request){
        // consult tv show
        if($request->id && $request->title){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.id', $request->id)
            ->where('tv_shows.title', 'like', '%'.$request->title.'%')
            ->get();
        }
        elseif($request->id && $request->genre){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.id', $request->id)
            ->where('tv_shows.genre', 'like', '%'.$request->genre.'%')
            ->get();
        }
        elseif($request->title && $request->genre){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.genre', 'like', '%'.$request->genre.'%')
            ->where('tv_shows.title', 'like', '%'.$request->title.'%')
            ->get();
        }
        elseif($request->id && $request->title && $request->genre){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.id', $request->id)
            ->where('tv_shows.genre', 'like', '%'.$request->genre.'%')
            ->where('tv_shows.title', 'like', '%'.$request->title.'%')
            ->get();
        }elseif($request->title){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.title', 'like', '%'.$request->title.'%')
            ->get();
        }elseif($request->genre){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.genre', 'like', '%'.$request->genre.'%')
            ->get();
        }
        elseif($request->id){
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->where('tv_shows.id', $request->id)
            ->get();
        }else{
            $sql_tv_shows= TvShow::select('directors.*', 'tv_shows.*')
            ->join('directors', 'tv_shows.director_id', '=', 'directors.id')
            ->get();
        }

        $result_tv=[];
        //get list of actors for each movie
        foreach($sql_tv_shows as $m){
            $sql_actors=Actors::select('actors.name', 'actors.id')
            ->join('actor_rel', 'actor_rel.actor_id', '=', 'actors.id')
            ->where('actor_rel.tv_show_id', '=', $m['id'])
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

            $sql_episodes=Episodes::where('tv_show_id','=',$m['id'])->count();

            $data_tv=[
                'id' => $m['id'],
                'title' => $m['title'],
                'director_id' => $m['director_id'],
                'director' => $m['name'],
                'actors' => $list_actors,
                'genre' => $m['genre'],
                'episodes' => $sql_episodes,
                'created_at'=>$m['created_at'],
                'updated_at'=>$m['updated_at'],
            ];
            array_push($result_tv, $data_tv);
        }

        return response()->json($result_tv);
    }

    public function addActorTvShow(Request $request){
        $response=[];
        $this->validate($request,[
            'actors_id' => 'required',
            'tv_show_id' => 'required',
        ]);

        $actors_id=explode( ',', $request->actors_id);
        if(count($actors_id)>1){
            foreach($actors_id as $actor_id){
                //check if the actor exists
                $sql_actor=Actors::find($actor_id);
                if($sql_actor){
                    // check if the actor is already in the movie
                    $sql_actor_rel=DB::table('actor_rel')->select('*')
                    ->where('actor_id', $sql_actor['id'])
                    ->where('tv_show_id', $request->tv_show_id)
                    ->count();
                    if($sql_actor_rel<=0){
                        $insert_rel=DB::table('actor_rel')->insert([
                            'actor_id' => $sql_actor['id'] ,
                            'tv_show_id' => $request->tv_show_id ,
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
            //check if the actor exists
            $sql_actor=Actors::find($request->actors_id);
            if($sql_actor){
                //check if the actor is already in the movie
                $sql_actor_rel=DB::table('actor_rel')->select('*')
                ->where('actor_id', $sql_actor['id'])
                ->where('tv_show_id', $request->tv_show_id)
                ->count();
                if($sql_actor_rel<=0){
                    $insert_rel=DB::table('actor_rel')->insert([
                        'actor_id' => $sql_actor['id'] ,
                        'tv_show_id' => $request->tv_show_id ,
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

    public function tvShowCreate(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'director_id' => 'required',
            'genre' => 'required',
        ]);

        $sql_director=Directors::find($request->director_id);
        if($sql_director){
            $tv_show = TvShow::create([
                'title' => $request->title,
                'director_id'=> $request->director_id,
                'genre'=> $request->genre,
            ]);
        }else{
            $tv_show = ['director_id' => 'non-existent'];
        }

        return response()->json($tv_show);
    }

    public function episodesCreate(Request $request){
        $this->validate($request,[
            'tv_show_id' => 'required',
            'name_episode' => 'required',
            'season_number' => 'required',
            'episode_number' => 'required',
        ]);

        $sql_tv=TvShow::find($request->tv_show_id);
        if($sql_tv){
            $episodes = Episodes::create([
                'tv_show_id' => $request->tv_show_id,
                'name_episode'=> $request->name_episode,
                'season_number'=> $request->season_number,
                'episode_number'=> $request->episode_number,
            ]);
        }else{
            $episodes = ['tv_show_id' => 'non-existent'];
        }

        return response()->json($episodes);
    }

    public function episodes(Request $request){
        $this->validate($request,[
            'tv_show_id' => 'required',
        ]);
        if($request->tv_show_id && $request->season_number && $request->episode_number){
            $sql_episodes=Episodes::where('tv_show_id', $request->tv_show_id)
            ->where('season_number', $request->season_number)
            ->where('episode_number', $request->episode_number)
            ->get();
        }elseif($request->tv_show_id && $request->season_number){
            $sql_episodes=Episodes::where('tv_show_id', $request->tv_show_id)
            ->where('season_number', $request->season_number)
            ->get();
        }elseif($request->tv_show_id && $request->episode_number){
            $sql_episodes=Episodes::where('tv_show_id', $request->tv_show_id)
            ->where('episode_number', $request->episode_number)
            ->get();
        }elseif($request->tv_show_id){
            $sql_episodes=Episodes::where('tv_show_id', $request->tv_show_id)
            ->get();
        }
        return response()->json($sql_episodes);
    }
}
