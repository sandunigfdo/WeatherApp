<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBAccessController extends Controller
{
    public function getDBData() {

        $topics = DB::table('topics')->get();

        return view('topics', [
            'topics' => $topics,
        ]);
    }

    public function destroyTopic($id) {
        DB::table('topics')->where('id', $id)->delete();

        if (env('APP_ENV') === 'production') {
                    return redirect('/index.php/topics');
        } else {
            return redirect('/topics');
        }
    }
}
