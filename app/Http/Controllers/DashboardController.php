<?php

namespace App\Http\Controllers;
use DB;
use App\Report;
use App\News;
use App\News_reports;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kejadian = DB::table('reports')->where('status','waiting')->limit(5)->get();
        return view('monitoring',compact('kejadian'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function broadcast()
    {
        return view('broadcast');
    }
    public function showKejadian(Request $request)
    {
        $data = array();
        $kejadian = Report::where('id',$request->id)->first();
        return response()->json($kejadian);
    }
    public function searchLatLong(Request $request)
    {
        $data = array();
        $kejadian_id = $request->id;
        $exp = explode(',', $kejadian_id);
        foreach ($exp as $id_kejadian) {
            $report = Report::where('id',$id_kejadian)->get();
            foreach ($report as $report_latlong) {
                $data[] = $report_latlong;
            }
        }
        return response()->json($data);
    }
    public function addBroadcast(Request $request)
    {
        $kejadian_id = $request->id_kejadian;
        $exp = explode(',', $kejadian_id);
        $files = $request->file('foto');
        if ($files) {
            $allowed = array('png','jpg','gif');
            $files_ori = time() . '-' . $files->getClientOriginalName();

            $ext = pathinfo($files_ori, PATHINFO_EXTENSION);
            if(in_array($ext,$allowed) ) {
                $files->move('attachment', $files_ori);
            }
            else{
                echo '<script>alert("extension not supported")</script>';
                return redirect()->back();
            }
        }
        else{

        }
        $insert_report = new News();
        $insert_report->title = $request->judul;
        $insert_report->description = $request->deskripsi;
        $insert_report->photo = ($files) ? $files_ori : null;
        $insert_report->lat = $request->latitude;
        $insert_report->lon = $request->longitude;
        $insert_report->category_news = $request->kejadian;
        $insert_report->status = $request->status;

        $insert_report->save();
        foreach ($exp as $id_kejadian) {
            $report = Report::findOrFail($id_kejadian);
            $report->status = $request->status;
            $report->update();

            $insert_new_report = new News_reports();
            $insert_new_report->news_id = $insert_report->id;
            $insert_new_report->report_id = $id_kejadian;

            $insert_new_report->save();

            
       }
       return redirect()->back();    
    }
}
