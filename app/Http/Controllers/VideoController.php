<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
       return view('upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(!$request->hasFile('file'))
            return redirect()->back()->with('error', 'No file');

        $file = $request->file('file');

        if(!$file->isValid()
        || $file->getExtension() != '.webm'
        || $file->getMimeType() != 'video/webm') return redirect()->back()->with('error', 'Invalid file');

        if(($v = Video::where('hash', '=', sha1_file($file->getRealPath()))->first()) !== null)
            return redirect($v->id)->with('error', 'Video already exists');

        $file = $file->move(public_path() . '/b/', time() . '.webm');
        $hash = sha1_file($file->getRealPath());


        $video = new Video();
        $video->file = basename($file->getRealPath());
        $video->interpret = $request->get('interpret', null);
        $video->songtitle = $request->get('songtitle', null);
        $video->imgsource = $request->get('imgsource', null);
        $video->user = auth()->user();
        $video->category = Category::findOrFail($request->get('category'));
        $video->hash = $hash;
        $video->save();

        return redirect($video->id)->with('success', 'Upload successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
