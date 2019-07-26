<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function writePage(Request $req)
    {
        return view('board/write');
    }

    public function writeProcess(Request $req)
    {
//        $file = $req->file('file');
//        $file->storeAs('app', $file->getClientOriginalName());

//        $files = $req->file('file');
//        $images = array();
//        if($req->hasFile('file')) {
//            foreach($files as $file) {
//                $file->store('app/');
//                $images[] = $file;
//            }
//        }

        //타이틀과 컨텐츠는 공백이여서는 안되고
        //타이틀은 한글, 영문 띄어쓰기만 사용 가능 -- 보너스
        $data = $req->all();
        $images = array();
        if($files = $req->file('file')) {
            foreach($files as $file) {
                $name = $file->getClientOriginalName();
                $file->storeAs('app/', $name);
                $images[] = $name;
            }
        }

        auth()->user()->boards()->create(
            [
                'title'=>$data['title'],
                'content'=>$data['content'],
                'file'=>implode("|", $images)
            ]);

        return redirect('/')->with('fm', '글이 작성되었습니다.');
    }

    public function listPage(Request $req)
    {
        $list = Board::paginate(5);
        //index페이지에서 화면에 출력
        // 페이징 처리 --- 한페이지당 글의 수 5개씩
        return view('board/index', ['list'=>$list]);
    }

    public function viewPage(Request $req, $id)
    {
        $data = Board::find($id);
        $images = explode('|', $data->file);

        if(!$data){
            return redirect('/board')->with('fm', '없는 글입니다.');
        }

        //뷰페이지 만들기
        return view('board/view', ['data'=>$data, 'image'=>$images]);
    }

    public function getImage(Request $req, $name)
    {
        $file = storage_path('app/app/' . $name);

        if(!file_exists($file)) {
            $file = storage_path('app/app/noImage.png');
        }

        // 이미지 들어갈시 띄워주기만함
        return response()->download($file);
    }
}
