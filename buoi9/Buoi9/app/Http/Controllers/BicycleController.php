<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Bicycle;
use Illuminate\Http\Request;

class BicycleController extends Controller
{
    /* ==================== ADMIN ==================== */

    // Danh sách bicycle
    public function adminIndex()
    {
        $bicycles = Bicycle::with('station')->paginate(10);
        return view('admin.bicycles.index', compact('bicycles'));
    }

    public function index()
    {
        return $this->adminIndex();
    }

    // Thùng rác
    public function trash()
    {
        $bicycles = Bicycle::onlyTrashed()
            ->withCount(['rentals'])
            ->paginate(10);
        return view('admin.bicycles.trash', compact('bicycles'));
    }

    /* ==================== CREATE ==================== */

    public function create()
    {
        $stations = Station::all();
        return view('admin.bicycles.create', compact('stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bike_code' => 'required|unique:bicycles',
            'station_id' => 'nullable|exists:stations,id',
            'price' => 'required|numeric|min:0'
        ]);

        Bicycle::create([
            'bike_code' => $request->bike_code,
            'station_id' => $request->station_id,
            'location_status' => 'at_station',
            'condition_status' => 'active',
            'rental_price_per_hour' => $request->price,
        ]);

        return redirect()->route('admin.bicycles.index')
            ->with('success', 'Thêm xe thành công');
    }

    /* ==================== EDIT ==================== */

    public function edit($id)
    {
        $bicycle = Bicycle::findOrFail($id);
        $stations = Station::all();
        return view('admin.bicycles.edit', compact('bicycle', 'stations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'station_id' => 'nullable|exists:stations,id',
            'price' => 'required|numeric|min:0'
        ]);

        $bicycle = Bicycle::findOrFail($id);
        $bicycle->update([
            'station_id' => $request->station_id,
            'condition_status' => $request->condition_status,
            'rental_price_per_hour' => $request->price,
        ]);

        return redirect()->route('admin.bicycles.index')
            ->with('success', 'Cập nhật thành công');
    }

    /* ==================== DELETE ==================== */

    // Xóa mềm
    public function destroy($id)
    {
        $bicycle = Bicycle::findOrFail($id);
        $bicycle->delete();

        return redirect()->route('admin.bicycles.index')
            ->with('success', 'Đã chuyển vào thùng rác');
    }

    /* ==================== RESTORE ==================== */

    public function restore($id)
    {
        $bicycle = Bicycle::withTrashed()->findOrFail($id);
        $bicycle->restore();

        return redirect()->route('admin.bicycles.trash')
            ->with('success', 'Đã khôi phục');
    }

    public function restoreSelected(Request $request)
    {
        $ids = explode(',', $request->ids);

        Bicycle::onlyTrashed()
            ->whereIn('id', $ids)
            ->restore();

        return back()->with('success', 'Đã khôi phục các xe đạp đã chọn');
    }

    public function restoreAll()
    {
        Bicycle::onlyTrashed()->restore();

        return redirect()->route('admin.bicycles.trash')
            ->with('success', 'Khôi phục tất cả xe đạp thành công');
    }

    /* ==================== FORCE DELETE ==================== */

    public function forceDelete($id)
    {
        $bicycle = Bicycle::withTrashed()
            ->withCount(['rentals'])
            ->findOrFail($id);

        if ($bicycle->rentals_count > 0) {
            return back()->with('error', 'Không thể xóa: xe đạp đã có lịch sử thuê');
        }

        $bicycle->forceDelete();
        return redirect()->route('admin.bicycles.trash')
            ->with('success', 'Đã xóa xe đạp vĩnh viễn');
    }

    public function forceDeleteSelected(Request $request)
    {
        $ids = explode(',', $request->ids);

        $bicycles = Bicycle::onlyTrashed()
            ->withCount(['rentals'])
            ->whereIn('id', $ids)
            ->get();

        foreach ($bicycles as $bicycle) {
            if ($bicycle->rentals_count == 0) {
                $bicycle->forceDelete();
            }
        }

        return back()->with('success', 'Đã xóa các xe đạp đã chọn');
    }

    public function forceDeleteAll()
    {
        $bicycles = Bicycle::onlyTrashed()
            ->withCount(['rentals'])
            ->get();

        foreach ($bicycles as $bicycle) {
            if ($bicycle->rentals_count == 0) {
                $bicycle->forceDelete();
            }
        }

        return redirect()->route('admin.bicycles.trash')
            ->with('success', 'Đã xóa các xe đạp hợp lệ');
    }
}
