<?php

namespace App\Http\Controllers;

use App\News;
use App\Polda;
use App\Report;
use Illuminate\Http\Request;
use Validator;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Report::latest()->get());
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
        $validator = Validator::make($request->all(), [
            'description' => 'required_if:type,normal',
            'lat' => 'required',
            'lon' => 'required',
//            'photo1' => 'required_unless:video,required_if:type,normal',
            'type' => 'required',
            'location' => 'required',
            'area' => 'required',
            'province' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }


        $input = $request->all();
        $input['user_id'] = \Auth::user()->id;
        $report = Report::create($input);
        if($request->exists('photo1')) {
            $file1 = $request->file('photo1');
            $fileName1 = time() . $file1->getClientOriginalName();
            $format = time() . $file1->getClientMimeType();
            $file1->move('reports', $fileName1);
            $report->attachment()->create([
                'report_id' => $report->id,
                'file' => $fileName1,
                'format' => $format
            ]);
        }
        if($request->exists('photo2')) {
            $file2 = $request->file('photo2');
            $fileName2 = "";
            if (!empty($file2)) {
                $fileName2 = time() . $file2->getClientOriginalName();
                $format = time() . $file2->getClientMimeType();
                $file2->move('reports', $fileName2);
            }
            $report->attachment()->create([
                'report_id' => $report->id,
                'file' => $fileName2,
                'format' => $format
            ]);
        }
        if($request->exists('photo3')) {
            $file3 = $request->file('photo3');
            $fileName3 = "";
            if (!empty($file3)) {
                $fileName3 = time() . $file3->getClientOriginalName();
                $format = time() . $file3->getClientMimeType();
                $file3->move('reports', $fileName3);
            }
            $report->attachment()->create([
                'report_id' => $report->id,
                'file' => $fileName3,
                'format' => $format
            ]);
        }
        if($request->exists('video')) {
            $file4 = $request->file('video');
            $fileName = "";
            if (!empty($file4)) {
                $fileName = time() . $file4->getClientOriginalName();
                $format = time() . $file4->getClientMimeType();
                $file4->move('reports', $fileName);
            }
            $report->attachment()->create([
                'report_id' => $report->id,
                'file' => $fileName,
                'format' => $format
            ]);
        }
        if (preg_match("/jakarta/i", $request->province)) {
            $polda = "Daerah Khusus Ibukota Jakarta";
        }
        else if (preg_match("/banten/i", $request->province)) {
            $polda = "Banten";
        }else {
            $polda = $request->province;
        }
        $report['polda_id'] = Polda::where('province', 'like', "%".$polda."%")->first()->id;

        return response()->json(['msg' => 'Laporan anda telah diterima','data' => $report->load('attachment')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Report::where('user_id', $id)->with('attachment')->latest()->paginate(10);
        if ( count($data) == 0 ) {
            return response()->json(['msg' => 'Tidak ada data']);
        }
        return response()->json($data);
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

    public function near(News $report, Request $request)
    {
        $latitude = $request->lat;
        $longitude = $request->lon;

        return response()->json($report->nearest($latitude, $longitude)->paginate(10));
    }
}
