<?php

namespace App\Http\Controllers;

use App\Helper\WardHelper;
use App\Models\Station;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /* ================= USER ================= */

    public function index(){
        $wards = WardHelper::get_ward();
        $stations = Station::with(['reviews' => function ($q) {
                $q->with('user')->latest();
            }])
            ->withCount(['bicycles', 'reviews as total_reviews'])
            ->withAvg('reviews as avg_rating', 'rating')
            ->get();
        return view('stations.index', compact('stations', 'wards'));
    }

    // Lọc trạm theo phường (trả JSON)
    public function filter(Request $request){
        $query = Station::withCount('bicycles');
        if ($request->filled('ward')) {
            DatabaseSeeder::ensureWardHasData($request->ward);
            $query->where('ward', $request->ward);
        }
        return response()->json($query->get());
    }

    /* ================= ADMIN ================= */

    public function adminIndex(){
        $stations = Station::withCount([
                'bicycles as bike_count',
                'reviews as total_reviews'
            ])
            ->withAvg('reviews as avg_rating', 'rating')
            ->paginate(10);
        return view('admin.stations.index', compact('stations'));
    }

    public function trash(){
        $stations=Station::onlyTrashed()
        ->withCount(['rentalsFrom','rentalsTo','bicycles'])
        ->paginate(10);
        return view('admin.stations.trash',compact('stations'));
    }

    public function create(){
        $wards=WardHelper::get_ward();
        return view('admin.stations.create',compact('wards'));
    }

        public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'address'=>'required',
            'ward'=> 'required',
            'capacity'=>'required|integer|min:0',
        ]);

        Station::create([
            'station_name'=>$request->name,
            'address'=>$request->address,
            'ward'=> $request->ward,
            'total_docks'=> $request->capacity,
            'status'=> 'active',
        ]);

        return redirect()->route('admin.stations.index')->with('success','Them thanh cong');
    }

    public function edit($id){
        $station=Station::findOrFail($id);
        $wards=WardHelper::get_ward();
        return view('admin.stations.edit',compact('station','wards'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'address'=>'required',
            'capacity'=>'required|integer|min:0',
        ]);

        $station= Station::findOrFail($id);
        $station->update([
            'station_name'=>$request->name,
            'address'=>$request->address,
            'ward'=> $request->ward,
            'total_docks'=> $request->capacity,
            'status'=> $request->status,
        ]);

        return redirect()->route('admin.stations.index')->with('success','Cap nhat thanh cong');
    }

    public function destroy($id){
        $station=Station::findOrFail($id);
        $station->delete();

        return redirect()->route('admin.stations.index')->with('success','Da chuyen vao thung rac');
    }

    public function restore($id){
        $station=Station::withTrashed()->findOrFail($id);
        $station->restore();

        return redirect()->route('admin.stations.trash')->with('success','Da khoi phuc');
    }

    public function restoreSelected(Request $request){
        $ids=explode(',',$request->ids);

        Station::onlyTrashed()->whereIn('id',$ids)->restore();

        return back()->with('success','Da khoi phuc cac tram da chon');
    }

    public function restoreAll(Request $request){
        Station::onlyTrashed()->restore();

        return redirect()->route('admin.stations.trash')->with('success','Khoi phuc tat ca thanh cong');

    }

    public function forceDelete($id){
        $station =Station:: withTrashed()->withCount(['rentalsFrom','rentalsTo','bicycles'])->findOrFail($id);

        $totalRentals=($station->rentalsFrom_count??0)+($station->rentalsTo_count ??0);

        if($totalRentals>0){
            return back()->with('error','Khong the xoa: tram da co lich su thue');
        }

        if ($station->bicycles_count> 0){
            return back()->with('error','Khong the khoa: tram van con xe');
        }

        $station->forceDelete();
        return redirect()->route('admin.stations.trash')->with('success','Da xoa vinh vien');

    }

    public function forceDeleteSelected(Request $request){
        $ids=explode(',',$request->ids);

        $stations=Station::onlyTrashed()->withCount(['rentalsFrom','rentalsTo','bicycles'])->whereIn('id',  $ids)->get();

        foreach ($stations as $station){
            $totalRentals=$station->rentalsFrom_count+$station->rentalsTo_count;

            if ($totalRentals ==0 && $station->bicycles_count== 0){
                $station->forceDelete();
            }
        }
        return back()->with('success','Da xoa cac tram da chon');
    }

    public function forceDeleteAll()
    {
        $stations=Station::onlyTrashed()->withCount(['rentalsFrom','rentalsTo','bicycles'])->get();

        foreach ($stations as $station){
            $totalRentals=($station->rentalsFrom_count??0)+($station->rentalsTo_count??0);
            if ($totalRentals == 0 && $station->bicycles_count== 0){
                $station->forceDelete();
            }
        }

        return redirect()->route('admin.stations.trash')->with('success','Da xoa cac tram hop le');

    }
    
}
