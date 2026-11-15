<?php

namespace App\Http\Controllers;

use App\Models\Bicycle;
use App\Models\BicycleReview;
use App\Models\Rental;
use App\Models\Station;
use App\Models\StationReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Trang tổng hợp Đánh giá & Bình luận (Trạm xe & Xe đạp)
     */
    public function index()
    {
        $stations = Station::where('status', 'active')->orderBy('station_name')->get();
        $bicycles = Bicycle::orderBy('bike_code')->get();

        $stationReviews = StationReview::with(['user', 'station'])
            ->latest()
            ->paginate(10, ['*'], 'station_page');

        $bicycleReviews = BicycleReview::with(['user', 'bicycle.station'])
            ->latest()
            ->paginate(10, ['*'], 'bicycle_page');

        return view('reviews.index', compact(
            'stations',
            'bicycles',
            'stationReviews',
            'bicycleReviews'
        ));
    }

    /**
     * Trang đánh giá chuyến thuê xe (từ Lịch sử thuê xe)
     */
    public function createFromRental($id)
    {
        $rental = Rental::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['bicycle', 'rentStation', 'returnStation'])
            ->firstOrFail();

        return view('reviews.rental', compact('rental'));
    }

    /**
     * Lưu đánh giá từ trang chuyến thuê xe (Đánh giá cả Xe đạp + Trạm xe)
     */
    public function storeRentalReview(Request $request, $id)
    {
        $rental = Rental::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'bike_rating'     => 'required|integer|min:1|max:5',
            'bike_comment'    => 'required|string|max:500',
            'station_id'      => 'nullable|exists:stations,id',
            'station_rating'  => 'nullable|integer|min:1|max:5',
            'station_comment' => 'nullable|string|max:500',
        ], [
            'bike_rating.required'  => 'Vui lòng chọn số sao cho xe đạp.',
            'bike_comment.required' => 'Vui lòng nhập nhận xét về xe đạp.',
        ]);

        // 1. Lưu đánh giá xe đạp
        if ($rental->bicycle_id) {
            BicycleReview::create([
                'user_id'    => Auth::id(),
                'bicycle_id' => $rental->bicycle_id,
                'rating'     => $request->bike_rating,
                'comment'    => $request->bike_comment,
            ]);
        }

        // 2. Lưu đánh giá trạm xe (nếu người dùng có nhập)
        if ($request->filled('station_id') && $request->filled('station_rating') && $request->filled('station_comment')) {
            StationReview::create([
                'user_id'    => Auth::id(),
                'station_id' => $request->station_id,
                'rating'     => $request->station_rating,
                'comment'    => $request->station_comment,
            ]);
        }

        return redirect()->route('rental.history')
            ->with('success', 'Cảm ơn bạn đã gửi đánh giá và bình luận cho chuyến đi!');
    }

    /**
     * Lưu đánh giá & bình luận cho Trạm xe
     */
    public function storeStationReview(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:500',
        ], [
            'station_id.required' => 'Vui lòng chọn trạm xe.',
            'rating.required'     => 'Vui lòng chọn số sao đánh giá (1 - 5 sao).',
            'comment.required'    => 'Vui lòng nhập nội dung bình luận.',
        ]);

        StationReview::create([
            'user_id'    => Auth::id(),
            'station_id' => $request->station_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Đã gửi đánh giá trạm xe thành công!');
    }

    /**
     * Lưu đánh giá & bình luận cho Xe đạp
     */
    public function storeBicycleReview(Request $request)
    {
        $request->validate([
            'bicycle_id' => 'required|exists:bicycles,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:500',
        ], [
            'bicycle_id.required' => 'Vui lòng chọn xe đạp.',
            'rating.required'     => 'Vui lòng chọn số sao đánh giá (1 - 5 sao).',
            'comment.required'    => 'Vui lòng nhập nội dung bình luận.',
        ]);

        BicycleReview::create([
            'user_id'    => Auth::id(),
            'bicycle_id' => $request->bicycle_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Đã gửi đánh giá xe đạp thành công!');
    }

    /**
     * Xóa bình luận trạm xe (chủ bình luận hoặc Admin)
     */
    public function destroyStationReview($id)
    {
        $review = StationReview::findOrFail($id);

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || ($review->user_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Bạn không có quyền xóa bình luận này.');
        }

        $review->delete();
        return back()->with('success', 'Đã xóa bình luận trạm xe.');
    }

    /**
     * Xóa bình luận xe đạp (chủ bình luận hoặc Admin)
     */
    public function destroyBicycleReview($id)
    {
        $review = BicycleReview::findOrFail($id);

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || ($review->user_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Bạn không có quyền xóa bình luận này.');
        }

        $review->delete();
        return back()->with('success', 'Đã xóa bình luận xe đạp.');
    }
}

