<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Albunud;
use Illuminate\Http\Request;
use PDOException;

class AlbunudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albunds = Albunud::all();

        return view("admin.albunuds.index", compact('albunds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.albunuds.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:albunuds,name'
        ]);

        try {
            Albunud::create([
                'name' => $request->name
            ]);
            toastr()->success('تم تعديل البيانات بنجاح');

            return redirect()->route('admin.albunuds.index');
        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع');

            return redirect()->route('admin.albunuds.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Albund = Albunud::findOrFail($id);
        return view("admin.albunuds.edit", compact('Albund'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $Albund = Albunud::findOrFail($id)->update(
                [
                    'name' => $request->name
                ]
            );
            toastr()->success('تم تحديث البيانات بنجاح  ');

            return redirect()->route('admin.albunuds.index');

        } catch (PDOException $e) {
            toastr()->error('حدث خطأ غير متوقع');

            return redirect()->route('admin.albunuds.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Albunud::findOrFail($id)->delete();
        toastr()->success('تم الحذف بنجاح');
        return redirect()->route('admin.albunuds.index');
    }
}
